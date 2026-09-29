<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_packs', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 160);
            $table->string('slug', 180)->unique();
            $table->text('description')->nullable();
            $table->string('type', 32)->index();
            $table->string('visibility', 24)->default('global')->index();
            $table->foreignId('target_level_id')->nullable()->constrained('levels')->nullOnDelete();
            $table->foreignId('target_subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->json('metadata')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_published')->default(false)->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('learning_pack_exercise', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('learning_pack_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_required')->default(true);
            $table->timestamps();
            $table->unique(['learning_pack_id', 'exercise_id']);
            $table->index(['learning_pack_id', 'position']);
        });

        Schema::create('household_learning_pack', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('household_id')->constrained()->cascadeOnDelete();
            $table->foreignId('learning_pack_id')->constrained()->cascadeOnDelete();
            $table->foreignId('activated_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true)->index();
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->unique(['household_id', 'learning_pack_id']);
        });

        Schema::create('child_learning_pack', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('household_id')->constrained()->cascadeOnDelete();
            $table->foreignId('child_id')->constrained()->cascadeOnDelete();
            $table->foreignId('learning_pack_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 24)->default('assigned')->index();
            $table->string('source', 24)->default('manual')->index();
            $table->json('settings')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['child_id', 'learning_pack_id']);
            $table->index(['household_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_learning_pack');
        Schema::dropIfExists('household_learning_pack');
        Schema::dropIfExists('learning_pack_exercise');
        Schema::dropIfExists('learning_packs');
    }
};
