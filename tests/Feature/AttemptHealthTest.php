<?php

namespace Tests\Feature;

use App\Support\AttemptHealth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class AttemptHealthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        Schema::create('exercise_attempts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    public function test_failing_rule_needs_enough_rejections_and_more_than_saved(): void
    {
        $this->assertFalse(AttemptHealth::isFailing(0, 4));
        $this->assertTrue(AttemptHealth::isFailing(0, 5));
        $this->assertTrue(AttemptHealth::isFailing(3, 6));
        $this->assertFalse(AttemptHealth::isFailing(10, 6));
    }

    public function test_rejections_are_counted_within_the_window_only(): void
    {
        $this->travelTo(now()->subHours(5));
        AttemptHealth::recordRejection(3, 'answers.items');
        $this->travelBack();
        AttemptHealth::recordRejection(5, 'answers.items');
        AttemptHealth::recordRejection(null, 'server_error:TypeError');

        $this->assertCount(2, AttemptHealth::rejectionsSince(2));
        $this->assertCount(3, AttemptHealth::rejectionsSince(6));
    }

    public function test_command_alerts_once_when_attempts_fail_in_series(): void
    {
        config(['services.alerts.webhook' => 'https://ntfy.sh/edumaison-test', 'services.alerts.email' => '']);
        Http::fake();
        foreach (range(1, 6) as $i) AttemptHealth::recordRejection(3, 'answers.items');

        $this->artisan('app:attempts-health')->assertExitCode(1);
        $this->artisan('app:attempts-health')->assertExitCode(1);

        Http::assertSentCount(1);
        Http::assertSent(fn ($request) => $request->url() === 'https://ntfy.sh/edumaison-test'
            && str_contains($request->body(), '6 tentative(s) refusée(s)'));
    }

    public function test_command_stays_quiet_when_attempts_are_saved(): void
    {
        config(['services.alerts.webhook' => 'https://ntfy.sh/edumaison-test']);
        Http::fake();
        \DB::table('exercise_attempts')->insert(array_fill(0, 8, ['created_at' => now(), 'updated_at' => now()]));
        foreach (range(1, 6) as $i) AttemptHealth::recordRejection(3, 'answers.items');

        $this->artisan('app:attempts-health')->assertExitCode(0);
        Http::assertNothingSent();
    }
}
