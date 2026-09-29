<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mama_profile', function (Blueprint $table) {
            $table->foreignId('household_id')->nullable()->unique()->constrained('households')->nullOnDelete();
            $table->string('display_name', 80)->default('Mon accompagnateur');
            $table->string('relationship', 32)->default('parent');
            $table->string('child_address', 80)->nullable();
            $table->string('tone', 24)->default('encouraging');
            $table->string('language', 8)->default('fr');
        });

        DB::table('mama_profile')->whereNull('household_id')->update([
            'display_name' => 'Mama Judi',
            'relationship' => 'mama',
            'child_address' => 'Mama Judi',
            'tone' => 'encouraging',
            'language' => 'fr',
        ]);
    }

    public function down(): void
    {
        Schema::table('mama_profile', function (Blueprint $table) {
            $table->dropConstrainedForeignId('household_id');
            $table->dropColumn(['display_name', 'relationship', 'child_address', 'tone', 'language']);
        });
    }
};
