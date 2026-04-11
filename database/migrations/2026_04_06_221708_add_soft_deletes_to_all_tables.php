<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'levels', 'subjects', 'units', 'lessons', 'children',
            'school_years', 'households', 'integrated_themes',
            'exercises', 'remediation_plans', 'school_competencies',
            'home_skills', 'audio_models',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'levels', 'subjects', 'units', 'lessons', 'children',
            'school_years', 'households', 'integrated_themes',
            'exercises', 'remediation_plans', 'school_competencies',
            'home_skills', 'audio_models',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};