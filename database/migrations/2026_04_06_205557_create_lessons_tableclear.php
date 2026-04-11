<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('name');           // ex: "Lesson 1 - Hello!"
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->integer('order')->default(0);
            $table->integer('estimated_minutes')->nullable();
            $table->enum('type', [
                'reading',
                'listening',
                'speaking',
                'writing',
                'mathematics',
                'science',
                'ict',
                'mixed'
            ])->default('mixed');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};