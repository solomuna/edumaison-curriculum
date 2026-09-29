<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('language_contributions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('household_id')->constrained()->cascadeOnDelete();
            $table->foreignId('household_national_language_id')
                ->constrained('household_national_language')->cascadeOnDelete();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('variant_name', 120)->nullable();
            $table->string('kind', 24);
            $table->string('source_text', 160);
            $table->string('french_translation', 200);
            $table->string('english_translation', 200);
            $table->text('usage_context');
            $table->string('source_origin', 32);
            $table->string('source_reference', 500)->nullable();
            $table->boolean('rights_confirmed')->default(false);
            $table->string('status', 24)->default('draft')->index();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['household_id', 'status'], 'language_contribution_household_status_index');
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE language_contributions ADD CONSTRAINT language_contributions_kind_check CHECK (kind IN ('word', 'expression', 'short_sentence'))");
            DB::statement("ALTER TABLE language_contributions ADD CONSTRAINT language_contributions_origin_check CHECK (source_origin IN ('adult_speaker', 'family_creation', 'authorized_reference'))");
            DB::statement("ALTER TABLE language_contributions ADD CONSTRAINT language_contributions_status_check CHECK (status IN ('draft', 'submitted', 'in_review', 'approved', 'rejected'))");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('language_contributions');
    }
};
