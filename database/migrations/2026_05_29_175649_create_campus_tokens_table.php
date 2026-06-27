<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Table des Personal Access Tokens emis pour les ecoles Campus.
 *
 * Chaque ecole (cote Campus = `schools.id`) recoit son propre token pour
 * authentifier ses appels a notre API publique (verifyChild, fetchSummary,
 * parents/link). Le hash est stocke (SHA-256 du token cleartext).
 *
 * Workflow d'emission :
 *   1. Admin (cote Mama Judi UI plus tard) cree un token pour l'ecole X
 *   2. EduMaison genere un token aleatoire 40 chars + hash SHA-256 en BDD
 *   3. Le cleartext est affiche UNE SEULE FOIS et copie cote Campus dans
 *      `learning.api_token` (CommunicationSetting tenant X)
 *   4. Tous les appels futurs : Authorization: Bearer <token>
 *
 * Revocation : on remplit `revoked_at`. Une route administrative permet
 * de revoquer un token compromis sans toucher au reste.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('campus_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campus_school_id')->unique();   // 1 token par ecole, simple V1
            $table->string('label', 100)->nullable();                   // ex: "Saint-Joseph - prod"
            $table->string('token_hash', 64)->index();                  // SHA-256 hex (64 chars)
            $table->string('token_prefix', 12);                         // 8 premiers chars du cleartext, pour identification UI
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campus_tokens');
    }
};
