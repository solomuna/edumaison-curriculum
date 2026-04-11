<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->foreignId('home_skill_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->text('instructions')->nullable();
            $table->enum('category', [
                'reading', 'handwriting', 'listening',
                'speaking', 'vocabulary', 'mathematics',
                'science', 'ict', 'revision', 'quiz', 'oral_drill'
            ])->default('reading');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');
            $table->integer('estimated_minutes')->nullable();
            $table->json('content')->nullable();   // contenu structuré de l'exercice
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};