<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Suivi des tentatives refusées par le serveur (422 ou erreur), pour détecter
 * une panne d'enregistrement en série — comme celle du 20/08 au 30/09/2026,
 * restée invisible six semaines. Aucune donnée d'enfant : seulement l'heure,
 * l'exercice et le champ en cause.
 */
class AttemptHealth
{
    private const KEY = 'attempt_health:rejections';
    private const KEEP_HOURS = 48;

    public static function recordRejection(?int $exerciseId, string $reason): void
    {
        $now = now()->getTimestamp();
        $entries = array_values(array_filter(
            (array) Cache::get(self::KEY, []),
            fn ($entry) => ($entry['at'] ?? 0) >= $now - self::KEEP_HOURS * 3600,
        ));
        $entries[] = ['at' => $now, 'exercise_id' => $exerciseId, 'reason' => mb_substr($reason, 0, 120)];
        Cache::put(self::KEY, array_slice($entries, -500), now()->addHours(self::KEEP_HOURS));
    }

    /** Refus enregistrés depuis $hours heures. */
    public static function rejectionsSince(int $hours): array
    {
        $since = now()->getTimestamp() - $hours * 3600;

        return array_values(array_filter((array) Cache::get(self::KEY, []), fn ($entry) => ($entry['at'] ?? 0) >= $since));
    }

    /**
     * Panne probable : au moins $minRejections refus, et plus de refus que de
     * tentatives réussies sur la même période.
     */
    public static function isFailing(int $saved, int $rejected, int $minRejections = 5): bool
    {
        return $rejected >= $minRejections && $rejected > $saved;
    }
}
