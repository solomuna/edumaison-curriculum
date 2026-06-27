<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Personal Access Token utilise par une ecole Campus pour appeler notre API.
 *
 * Emission :
 *   [$token, $clearText] = CampusToken::issue($schoolId, 'Saint-Joseph - prod');
 *   echo $clearText; // <-- a copier dans Campus, affiche UNE SEULE FOIS
 *
 * Verification (lors d'un appel inbound) :
 *   $row = CampusToken::findByCleartext($clearText); // null si invalide ou revoque
 *
 * Revocation :
 *   $token->revoke();
 */
class CampusToken extends Model
{
    protected $fillable = [
        'campus_school_id', 'label', 'token_hash', 'token_prefix',
        'last_used_at', 'revoked_at',
    ];

    protected $casts = [
        'campus_school_id' => 'integer',
        'last_used_at'     => 'datetime',
        'revoked_at'       => 'datetime',
    ];

    /**
     * Genere un nouveau token pour une ecole donnee. Retourne [model, cleartext].
     * Le cleartext doit etre stocke par Campus (CommunicationSetting `learning.api_token`).
     *
     * @return array{0: self, 1: string}
     */
    public static function issue(int $schoolId, ?string $label = null): array
    {
        $clear = 'em_' . Str::random(48);          // ex: em_3xK9p...
        $hash  = hash('sha256', $clear);

        $token = self::create([
            'campus_school_id' => $schoolId,
            'label'            => $label,
            'token_hash'       => $hash,
            'token_prefix'     => substr($clear, 0, 11),  // "em_" + 8 chars
        ]);

        return [$token, $clear];
    }

    /**
     * Trouve un token valide (non revoque) a partir de son cleartext.
     * Met a jour last_used_at au passage.
     */
    public static function findByCleartext(string $clear): ?self
    {
        $hash = hash('sha256', $clear);
        $row  = self::query()
            ->where('token_hash', $hash)
            ->whereNull('revoked_at')
            ->first();

        if ($row) {
            $row->forceFill(['last_used_at' => Carbon::now()])->saveQuietly();
        }
        return $row;
    }

    public function revoke(): void
    {
        $this->update(['revoked_at' => Carbon::now()]);
    }
}
