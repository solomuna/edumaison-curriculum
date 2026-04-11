<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->foreignId('household_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('level_id')->constrained()->onDelete('restrict');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date');
            $table->string('avatar')->nullable();
            $table->string('pin', 4)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->dropColumn(['household_id', 'level_id', 'first_name', 'last_name', 'birth_date', 'avatar', 'pin']);
        });
    }
};
