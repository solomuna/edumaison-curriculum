<?php

namespace Tests\Feature;

use App\Jobs\GenerateStatementVoice;
use App\Models\Exercise;
use App\Models\SpeechClip;
use App\Services\StatementVoice;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StatementVoiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        config(['services.elevenlabs.key' => 'test-key', 'services.elevenlabs.voice_id' => 'voice123', 'services.elevenlabs.statement_model' => 'eleven_multilingual_v2', 'services.elevenlabs.statement_monthly_chars' => 0]);
        Http::fake(['api.elevenlabs.io/*' => Http::response('ID3-fake-mp3', 200, ['Content-Type' => 'audio/mpeg'])]);

        foreach (['levels' => ['name'], 'subjects' => ['level_id', 'name'], 'integrated_themes' => ['subject_id'], 'units' => ['integrated_theme_id'], 'lessons' => ['unit_id']] as $table => $columns) {
            Schema::create($table, function (Blueprint $t) use ($columns) {
                $t->id();
                foreach ($columns as $column) str_ends_with($column, '_id') ? $t->unsignedBigInteger($column) : $t->string($column);
                $t->timestamps();
                $t->softDeletes();
            });
        }
        Schema::create('exercises', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('lesson_id');
            $t->string('title');
            $t->text('instructions')->nullable();
            $t->text('content');
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->softDeletes();
        });
        Schema::create('speech_clips', function (Blueprint $t) {
            $t->id();
            $t->string('hash', 64)->unique();
            $t->string('language', 2);
            $t->text('text');
            $t->unsignedInteger('characters');
            $t->string('model', 64);
            $t->string('file_path');
            $t->timestamps();
        });
        DB::table('levels')->insert([['id' => 7, 'name' => 'Class 3'], ['id' => 9, 'name' => 'Class 5']]);
        DB::table('subjects')->insert([['id' => 1, 'level_id' => 7, 'name' => 'English'], ['id' => 2, 'level_id' => 9, 'name' => 'French']]);
        DB::table('integrated_themes')->insert([['id' => 1, 'subject_id' => 1], ['id' => 2, 'subject_id' => 2]]);
        DB::table('units')->insert([['id' => 1, 'integrated_theme_id' => 1], ['id' => 2, 'integrated_theme_id' => 2]]);
        DB::table('lessons')->insert([['id' => 1, 'unit_id' => 1], ['id' => 2, 'unit_id' => 2]]);
    }

    private function exercise(int $id, int $lessonId, string $instructions, array $content): Exercise
    {
        DB::table('exercises')->insert(['id' => $id, 'lesson_id' => $lessonId, 'title' => "Ex {$id}", 'instructions' => $instructions, 'content' => json_encode($content)]);
        return Exercise::query()->with('lesson.unit.integratedTheme.subject')->find($id);
    }

    public function test_an_exercise_is_cut_into_the_pieces_the_app_reads(): void
    {
        $voice = app(StatementVoice::class);
        $mcq = $this->exercise(1, 1, 'Choose the correct answer.', ['type' => 'mcq', 'questions' => [['text' => 'Round 374 to the nearest hundred.', 'options' => ['300', '400'], 'answer' => 1], ['question' => 'What is 2 + 2?', 'options' => ['4'], 'answer' => 0]]]);
        $fill = $this->exercise(2, 2, 'Complète.', ['type' => 'fill_in', 'items' => [['sentence' => 'Le chat ___ du lait.', 'answer' => 'boit']]]);
        $tf = $this->exercise(3, 1, 'True or false?', ['type' => 'true_false', 'statement' => 'The sun rises in the east.', 'answer' => true]);

        $this->assertSame(['instructions' => 'Choose the correct answer.', 'q:0' => 'Round 374 to the nearest hundred.', 'q:1' => 'What is 2 + 2?'], $voice->segments($mcq));
        $this->assertSame(['instructions' => 'Complète.', 'item:0' => 'Le chat … du lait.'], $voice->segments($fill));
        $this->assertSame('fr', $voice->language($fill));
        $this->assertSame('en', $voice->language($mcq));
        $this->assertSame('The sun rises in the east.', $voice->segments($tf)['statement']);
    }

    public function test_each_text_is_generated_once_and_shared(): void
    {
        $voice = app(StatementVoice::class);
        $a = $this->exercise(1, 1, 'True or false?', ['type' => 'true_false', 'statement' => 'Water boils at 100 °C.', 'answer' => true]);
        $b = $this->exercise(2, 1, 'True or false?', ['type' => 'true_false', 'statement' => 'Ice is hot.', 'answer' => false]);

        $this->assertSame([null, null], array_column($voice->manifest($a)['segments'], 'url'));
        [$created] = $voice->generateFor($a, 10_000);
        [$createdB] = $voice->generateFor($b, 10_000);

        $this->assertSame(2, $created);
        $this->assertSame(1, $createdB); // « True or false? » déjà prêt
        $this->assertSame(3, SpeechClip::query()->count());
        $url = $voice->manifest($a)['segments']['statement']['url'];
        $this->assertSame('Water boils at 100 °C.', $voice->manifest($a)['segments']['statement']['text']);
        $this->assertStringStartsWith('/storage/speech/', $url);
        Storage::disk('public')->assertExists(substr($url, strlen('/storage/')));
    }

    public function test_the_command_counts_without_spending_then_respects_its_budget(): void
    {
        $this->exercise(1, 1, 'Choose the correct answer.', ['type' => 'mcq', 'questions' => [['text' => 'Round 374 to the nearest hundred.', 'options' => ['300', '400'], 'answer' => 1]]]);
        $this->exercise(2, 2, 'Complète.', ['type' => 'fill_in', 'items' => [['sentence' => 'Le chat ___ du lait.', 'answer' => 'boit']]]);

        $this->artisan('app:voice-statements', ['--level' => ['Class 3'], '--dry-run' => true])
            ->expectsOutputToContain('1 exercices, 2 énoncés à générer, 59 caractères')
            ->assertSuccessful();
        Http::assertNothingSent();

        $this->artisan('app:voice-statements', ['--level' => ['Class 3'], '--max-chars' => 30])->assertSuccessful();
        $this->assertSame(1, SpeechClip::query()->count()); // la question (33 car.) dépasse le budget restant
        $this->artisan('app:voice-statements', ['--level' => ['Class 3'], '--max-chars' => 1000])->assertSuccessful();
        $this->assertSame(2, SpeechClip::query()->count());
        $this->assertSame(0, SpeechClip::query()->where('language', 'fr')->count()); // Class 5 non demandé
    }

    public function test_background_generation_only_with_a_monthly_cap_and_only_for_families(): void
    {
        Queue::fake();
        $this->exercise(1, 1, 'True or false?', ['type' => 'true_false', 'statement' => 'Ice is hot.', 'answer' => false]);
        $family = Crypt::encryptString(json_encode(['household_id' => 1, 'expires_at' => now()->addHour()->timestamp]));

        $this->getJson('/api/exercises/1/speech')->assertStatus(401);

        $this->withCredentials()->withUnencryptedCookie('edumaison_access', $family)->getJson('/api/exercises/1/speech')
            ->assertOk()->assertJsonPath('segments.statement.url', null)->assertJsonPath('segments.statement.text', 'Ice is hot.');
        Queue::assertNothingPushed();

        config(['services.elevenlabs.statement_monthly_chars' => 5000]);
        $this->withCredentials()->withUnencryptedCookie('edumaison_access', $family)->getJson('/api/exercises/1/speech')->assertOk();
        Queue::assertPushed(GenerateStatementVoice::class, fn ($job) => $job->exerciseId === 1);
    }

    public function test_a_network_failure_skips_one_text_without_stopping_the_batch(): void
    {
        Http::fake([
            'api.elevenlabs.io/*' => Http::sequence()
                ->pushFailedConnection()->pushFailedConnection()->pushFailedConnection() // 1er texte : 3 essais en échec
                ->push('ID3-fake-mp3', 200), // 2e texte : réussi
        ]);
        $exercise = $this->exercise(1, 1, 'True or false?', ['type' => 'true_false', 'statement' => 'Ice is hot.', 'answer' => false]);

        [$created, , $left] = app(StatementVoice::class)->generateFor($exercise, 10_000);

        $this->assertSame([1, 1], [$created, $left]);
        $this->assertSame(1, SpeechClip::query()->count());
    }
}
