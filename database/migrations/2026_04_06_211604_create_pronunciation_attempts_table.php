<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pronunciation_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained()->onDelete('cascade');
            $table->foreignId('exercise_id')->nullable()->constrained()->onDelete('set null');
            $table->string('target_text');
            $table->string('recorded_audio_path')->nullable();
            $table->decimal('overall_score', 5, 2)->nullable();
            $table->decimal('fluency_score', 5, 2)->nullable();
            $table->decimal('prosody_score', 5, 2)->nullable();
            $table->decimal('rhythm_score', 5, 2)->nullable();
            $table->decimal('pronunciation_score', 5, 2)->nullable();
            $table->json('feedback_json')->nullable();
            $table->timestamp('attempted_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pronunciation_attempts');
    }
};