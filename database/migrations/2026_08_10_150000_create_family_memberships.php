<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('household_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 24)->default('parent');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['household_id', 'user_id']);
            $table->index(['user_id', 'is_active']);
        });

        Schema::table('households', function (Blueprint $table) {
            $table->string('locale', 10)->default('fr')->after('school');
            $table->string('timezone', 64)->default('Africa/Douala')->after('locale');
        });

        Schema::table('children', function (Blueprint $table) {
            $table->string('pin_hash')->nullable()->after('pin');
        });
    }

    public function down(): void
    {
        Schema::table('children', fn (Blueprint $table) => $table->dropColumn('pin_hash'));
        Schema::table('households', fn (Blueprint $table) => $table->dropColumn(['locale', 'timezone']));
        Schema::dropIfExists('household_user');
    }
};
