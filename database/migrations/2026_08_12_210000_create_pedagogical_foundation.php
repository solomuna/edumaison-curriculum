<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->string('education_subsystem', 24)->default('anglophone');
        });

        Schema::create('curriculum_documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('authority')->nullable();
            $table->string('education_subsystem', 24);
            $table->string('cycle', 32);
            $table->json('levels')->nullable();
            $table->string('language', 12)->default('en');
            $table->string('version_label')->nullable();
            $table->date('publication_date')->nullable();
            $table->text('source_url')->nullable();
            $table->string('storage_path')->nullable();
            $table->string('sha256', 64)->nullable()->unique();
            $table->string('verification_status', 24)->default('draft');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('school_competencies', function (Blueprint $table) {
            $table->foreignId('curriculum_document_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sequence_id')->nullable()->constrained()->nullOnDelete();
            $table->string('official_code')->nullable();
            $table->string('source_pages')->nullable();
            $table->string('verification_status', 24)->default('unverified');
        });

        Schema::create('exercise_media_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->string('kind', 24);
            $table->string('file_path');
            $table->text('alt_text')->nullable();
            $table->text('transcript')->nullable();
            $table->string('language', 12)->nullable();
            $table->json('metadata')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('family_voice_clips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('purpose', 32);
            $table->string('language', 12)->default('fr');
            $table->string('file_path');
            $table->text('transcript')->nullable();
            $table->timestamp('consent_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['household_id', 'purpose', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_voice_clips');
        Schema::dropIfExists('exercise_media_assets');

        Schema::table('school_competencies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('sequence_id');
            $table->dropConstrainedForeignId('curriculum_document_id');
            $table->dropColumn(['official_code', 'source_pages', 'verification_status']);
        });

        Schema::dropIfExists('curriculum_documents');
        Schema::table('levels', fn (Blueprint $table) => $table->dropColumn('education_subsystem'));
    }
};
