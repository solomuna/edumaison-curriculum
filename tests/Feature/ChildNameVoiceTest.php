<?php

namespace Tests\Feature;

use App\Jobs\SyncChildNameVoice;
use App\Models\Child;
use App\Models\ChildVoiceClip;
use App\Services\ChildNameVoice;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ChildNameVoiceTest extends TestCase
{
    private int $clipsPerChild;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        config(['services.elevenlabs.key' => 'test-key', 'services.elevenlabs.voice_id' => 'voice123', 'services.elevenlabs.model' => 'eleven_multilingual_v2']);
        Http::fake(['api.elevenlabs.io/*' => Http::response('ID3-fake-mp3', 200, ['Content-Type' => 'audio/mpeg'])]);

        Schema::create('households', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamp('child_name_voice_consent_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id');
            $table->string('first_name');
            $table->string('last_name')->default('');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('child_voice_clips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id');
            $table->string('language', 2);
            $table->string('event', 32);
            $table->unsignedTinyInteger('variant');
            $table->string('file_path');
            $table->string('source_hash', 64);
            $table->timestamps();
        });
        DB::table('households')->insert([
            ['id' => 1, 'name' => 'A', 'child_name_voice_consent_at' => now()],
            ['id' => 2, 'name' => 'B', 'child_name_voice_consent_at' => null],
        ]);
        $this->clipsPerChild = collect(ChildNameVoice::LINES)->flatten()->count();
    }

    private function child(int $householdId, string $firstName = 'Ama'): Child
    {
        return Child::withoutEvents(fn () => Child::create(['household_id' => $householdId, 'first_name' => $firstName]));
    }

    private function familyCookie(int $householdId): string
    {
        return Crypt::encryptString(json_encode(['household_id' => $householdId, 'expires_at' => now()->addHour()->timestamp]));
    }

    public function test_generates_private_clips_with_the_first_name_when_the_family_agreed(): void
    {
        $child = $this->child(1, 'Ama Grace');

        $created = app(ChildNameVoice::class)->sync($child->load('household'));

        $this->assertSame($this->clipsPerChild, $created);
        $this->assertSame($this->clipsPerChild, ChildVoiceClip::query()->where('child_id', $child->id)->count());
        Http::assertSent(fn ($request) => str_contains($request->url(), '/v1/text-to-speech/voice123') && $request['text'] === 'Bravo, Ama !');
        $clip = ChildVoiceClip::query()->first();
        $this->assertStringStartsWith("child-voice/1/{$child->id}/", $clip->file_path);
        Storage::disk('local')->assertExists($clip->file_path);
    }

    public function test_nothing_is_generated_without_the_family_agreement(): void
    {
        $child = $this->child(2);

        $this->assertSame(0, app(ChildNameVoice::class)->sync($child->load('household')));
        Http::assertNothingSent();
    }

    public function test_second_sync_reuses_clips_and_a_new_first_name_regenerates_them(): void
    {
        $voice = app(ChildNameVoice::class);
        $child = $this->child(1, 'Ama');
        $voice->sync($child->load('household'));

        $this->assertSame(0, $voice->sync($child));

        $child->first_name = 'Kofi';
        $this->assertSame($this->clipsPerChild, $voice->sync($child));
        Http::assertSent(fn ($request) => ($request['text'] ?? '') === 'Bravo, Kofi !');
    }

    public function test_withdrawing_agreement_deletes_the_clips_and_files(): void
    {
        $voice = app(ChildNameVoice::class);
        $child = $this->child(1);
        $voice->sync($child->load('household'));
        $paths = ChildVoiceClip::query()->pluck('file_path');

        DB::table('households')->where('id', 1)->update(['child_name_voice_consent_at' => null]);
        $voice->sync($child->fresh('household'));

        $this->assertSame(0, ChildVoiceClip::query()->count());
        foreach ($paths as $path) Storage::disk('local')->assertMissing($path);
    }

    public function test_renaming_a_child_queues_a_new_generation_only_with_agreement(): void
    {
        Queue::fake();
        $agreed = $this->child(1);
        $refused = $this->child(2);

        $agreed->update(['first_name' => 'Kofi']);
        $refused->update(['first_name' => 'Kofi']);
        $agreed->update(['last_name' => 'Mensah']);

        Queue::assertPushed(SyncChildNameVoice::class, 1);
        Queue::assertPushed(SyncChildNameVoice::class, fn ($job) => $job->childId === $agreed->id);
    }

    public function test_a_family_only_sees_and_hears_its_own_children(): void
    {
        $mine = $this->child(1);
        $other = $this->child(1, 'Kofi');
        app(ChildNameVoice::class)->sync($mine->load('household'));
        app(ChildNameVoice::class)->sync($other->load('household'));
        $foreignClip = ChildVoiceClip::query()->where('child_id', $other->id)->first();
        DB::table('children')->where('id', $other->id)->update(['household_id' => 2]);

        $cookie = $this->familyCookie(1);
        $manifest = $this->withCredentials()->withUnencryptedCookie('edumaison_access', $cookie)->getJson("/api/children/{$mine->id}/name-voice");
        $manifest->assertOk();
        $this->assertCount(2, $manifest->json('clips.fr.correct'));

        $this->withCredentials()->withUnencryptedCookie('edumaison_access', $cookie)->getJson("/api/children/{$other->id}/name-voice")->assertNotFound();
        $this->withCredentials()->withUnencryptedCookie('edumaison_access', $cookie)->get("/api/children/{$mine->id}/name-voice/{$foreignClip->id}")->assertNotFound();
        // Sans connexion familiale : refusé.
        $this->unencryptedCookies = [];
        $this->getJson("/api/children/{$mine->id}/name-voice")->assertStatus(401);
    }
}
