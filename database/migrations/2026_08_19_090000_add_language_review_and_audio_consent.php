<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('households', function (Blueprint $table) {
            $table->timestamp('speaking_audio_consent_at')->nullable()->after('timezone');
            $table->unsignedSmallInteger('speaking_audio_retention_days')->default(30)->after('speaking_audio_consent_at');
        });

        Schema::table('pronunciation_attempts', function (Blueprint $table) {
            $table->foreignId('exercise_attempt_id')
                ->nullable()
                ->after('exercise_id')
                ->constrained('exercise_attempts')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pronunciation_attempts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('exercise_attempt_id');
        });

        Schema::table('households', function (Blueprint $table) {
            $table->dropColumn(['speaking_audio_consent_at', 'speaking_audio_retention_days']);
        });
    }
};
