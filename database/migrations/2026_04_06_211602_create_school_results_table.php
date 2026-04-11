<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_competency_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('total_score', 5, 2)->nullable();
            $table->decimal('max_score', 5, 2)->nullable();
            $table->decimal('average_score', 5, 2)->nullable();
            $table->string('appreciation')->nullable(); // ex: "Bien", "Passable"
            $table->text('teacher_comment')->nullable();
            $table->enum('source_type', ['bulletin', 'manual', 'import'])->default('manual');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_results');
    }
};