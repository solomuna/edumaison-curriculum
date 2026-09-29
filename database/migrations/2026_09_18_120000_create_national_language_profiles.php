<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('national_languages', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 32)->unique();
            $table->string('name', 120);
            $table->string('autonym', 120)->nullable();
            $table->json('aliases')->nullable();
            $table->text('source_notes')->nullable();
            $table->boolean('is_selectable')->default(true)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('households', function (Blueprint $table): void {
            $table->foreignId('national_language_id')->nullable()->after('school')
                ->constrained('national_languages')->nullOnDelete();
            $table->string('national_language_other_name', 120)->nullable()->after('national_language_id');
        });

        Schema::table('children', function (Blueprint $table): void {
            $table->foreignId('national_language_id')->nullable()->after('level_id')
                ->constrained('national_languages')->nullOnDelete();
            $table->string('national_language_other_name', 120)->nullable()->after('national_language_id');
        });

        Schema::table('learning_packs', function (Blueprint $table): void {
            $table->foreignId('national_language_id')->nullable()->after('target_subject_id')
                ->constrained('national_languages')->nullOnDelete();
            $table->string('content_version', 32)->nullable()->after('national_language_id');
            $table->string('linguistic_review_status', 24)->default('not_required')->after('content_version')->index();
            $table->timestamp('linguistic_reviewed_at')->nullable()->after('linguistic_review_status');
            $table->text('linguistic_review_notes')->nullable()->after('linguistic_reviewed_at');
            $table->index(
                ['type', 'national_language_id', 'linguistic_review_status'],
                'learning_packs_language_review_index'
            );
        });
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE learning_packs ADD CONSTRAINT learning_packs_linguistic_review_status_check CHECK (linguistic_review_status IN ('not_required', 'draft', 'in_review', 'verified', 'rejected'))");
        }

        Schema::create('household_national_language', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('household_id')->constrained()->cascadeOnDelete();
            $table->foreignId('national_language_id')->nullable()
                ->constrained('national_languages')->nullOnDelete();
            $table->string('custom_name', 120)->nullable();
            $table->string('family_label', 120)->nullable();
            $table->string('variant_name', 120)->nullable();
            $table->unsignedSmallInteger('priority')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->unique(
                ['household_id', 'national_language_id'],
                'household_national_language_catalogue_unique'
            );
            $table->index(['household_id', 'priority'], 'household_national_language_priority_index');
        });

        Schema::create('child_national_language', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('child_id')->constrained()->cascadeOnDelete();
            $table->foreignId('household_national_language_id')
                ->constrained('household_national_language')->cascadeOnDelete();
            $table->unsignedSmallInteger('position')->default(0);
            $table->boolean('is_enabled')->default(true)->index();
            $table->boolean('is_current')->default(false)->index();
            $table->timestamp('selected_at')->nullable();
            $table->timestamps();
            $table->unique(
                ['child_id', 'household_national_language_id'],
                'child_national_language_choice_unique'
            );
            $table->index(['child_id', 'is_current'], 'child_national_language_current_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_national_language');
        Schema::dropIfExists('household_national_language');

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE learning_packs DROP CONSTRAINT IF EXISTS learning_packs_linguistic_review_status_check');
        }
        Schema::table('learning_packs', function (Blueprint $table): void {
            $table->dropIndex('learning_packs_language_review_index');
            $table->dropConstrainedForeignId('national_language_id');
            $table->dropColumn([
                'content_version',
                'linguistic_review_status',
                'linguistic_reviewed_at',
                'linguistic_review_notes',
            ]);
        });

        Schema::table('children', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('national_language_id');
            $table->dropColumn('national_language_other_name');
        });

        Schema::table('households', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('national_language_id');
            $table->dropColumn('national_language_other_name');
        });

        Schema::dropIfExists('national_languages');
    }
};
