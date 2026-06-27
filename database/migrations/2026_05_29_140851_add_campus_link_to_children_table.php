<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lien EduMaison <-> EduMaison-Campus.
 *
 * Un enfant EduMaison (utilisateur a la maison) peut etre lie a un eleve dans une
 * ecole Campus. Le couple (tenant_slug, external_student_id) identifie de maniere
 * unique l'eleve cote Campus :
 *   - tenant_slug         : le slug de l'ecole cote Campus (ex: "demo", "anglodemo")
 *   - external_student_id : l'id du student dans la table tenant_<slug>.students
 *
 * Quand les deux champs sont nuls, l'enfant n'est pas relie a une ecole Campus
 * (cas par defaut, EduMaison fonctionne en autonomie).
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->string('tenant_slug', 64)->nullable()->after('household_id');
            $table->string('external_student_id', 64)->nullable()->after('tenant_slug');
            $table->timestamp('campus_last_sync_at')->nullable()->after('external_student_id');

            // Couple unique : un seul enfant EduMaison par eleve Campus.
            $table->unique(['tenant_slug', 'external_student_id'], 'children_campus_unique');
            $table->index('tenant_slug', 'children_tenant_slug_idx');
        });
    }

    public function down(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->dropUnique('children_campus_unique');
            $table->dropIndex('children_tenant_slug_idx');
            $table->dropColumn(['tenant_slug', 'external_student_id', 'campus_last_sync_at']);
        });
    }
};
