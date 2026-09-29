<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_years', function (Blueprint $table) {
            $table->string('official_status', 24)->default('legacy');
            $table->string('source_authority')->nullable();
            $table->text('source_url')->nullable();
            $table->string('source_reference')->nullable();
            $table->date('published_at')->nullable();
        });

        DB::statement('ALTER TABLE school_years ALTER COLUMN start_date DROP NOT NULL');
        DB::statement('ALTER TABLE school_years ALTER COLUMN end_date DROP NOT NULL');

        if (! DB::table('school_years')->where('label', '2026-2027')->exists()) {
            DB::table('school_years')->insert([
                'label' => '2026-2027',
                'start_date' => null,
                'end_date' => null,
                'is_current' => false,
                'official_status' => 'awaiting_official',
                'source_authority' => 'MINEDUB / MINESEC',
                'source_url' => 'https://www.minedub.cm/',
                'source_reference' => null,
                'published_at' => null,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('school_years')->where('label', '2026-2027')
            ->where('official_status', 'awaiting_official')->delete();

        Schema::table('school_years', function (Blueprint $table) {
            $table->dropColumn(['official_status', 'source_authority', 'source_url', 'source_reference', 'published_at']);
        });
    }
};
