<?php

namespace App\Jobs;

use App\Models\Child;
use App\Services\ChildNameVoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

/** Génère (ou supprime, sans accord) les répliques au prénom d'un enfant, hors requête. */
class SyncChildNameVoice implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public int $childId) {}

    public function uniqueId(): string
    {
        return (string) $this->childId;
    }

    public function handle(ChildNameVoice $voice): void
    {
        $child = Child::query()->with('household')->find($this->childId);
        if ($child) $voice->sync($child);
    }
}
