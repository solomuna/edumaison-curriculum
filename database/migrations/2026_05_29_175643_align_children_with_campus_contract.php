<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Aligne le mapping enfant <-> eleve Campus sur le contrat existant cote Campus.
 *
 * Cote Campus : `students.learning_child_id` pointe vers nous (children.id).
 * Cote nous : on garde l'autre sens du lien (`campus_school_id`, `campus_student_id`)
 * pour pouvoir initier des appels Campus -> nous (et plus tard nous -> Campus).
 *
 * Drop des champs introduits par 2026_05_29_140851 (tenant_slug + external_student_id) :
 *   - Le mot "tenant" est interne a Campus (multi-tenant Laravel), pas pertinent
 *     dans notre vocabulaire.
 *   - Mieux : `campus_school_id` (bigint, equivalent Campus `schools.id`) +
 *     `campus_student_id` (matricule ou id direct cote Campus).
 *
 * On garde `campus_last_sync_at` qui reste utile.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('children', function (Blueprint $table) {
            // Supprimer index + unique de l'ancienne migration
            $table->dropUnique('children_campus_unique');
            $table->dropIndex('children_tenant_slug_idx');
            $table->dropColumn(['tenant_slug', 'external_student_id']);
        });

        Schema::table('children', function (Blueprint $table) {
            // Nouveaux champs alignes sur le vocabulaire Campus
            $table->unsignedBigInteger('campus_school_id')->nullable()->after('household_id');
            $table->string('campus_student_id', 64)->nullable()->after('campus_school_id');

            $table->unique(['campus_school_id', 'campus_student_id'], 'children_campus_unique');
            $table->index('campus_school_id', 'children_campus_school_idx');
        });
    }

    public function down(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->dropUnique('children_campus_unique');
            $table->dropIndex('children_campus_school_idx');
            $table->dropColumn(['campus_school_id', 'campus_student_id']);
        });

        // Restaurer les anciens champs pour cohérence avec la migration précédente
        Schema::table('children', function (Blueprint $table) {
            $table->string('tenant_slug', 64)->nullable()->after('household_id');
            $table->string('external_student_id', 64)->nullable()->after('tenant_slug');

            $table->unique(['tenant_slug', 'external_student_id'], 'children_campus_unique');
            $table->index('tenant_slug', 'children_tenant_slug_idx');
        });
    }
};
