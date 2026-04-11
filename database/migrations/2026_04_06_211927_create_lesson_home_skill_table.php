<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_home_skill', function (Blueprint $table) {
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->foreignId('home_skill_id')->constrained()->onDelete('cascade');
            $table->primary(['lesson_id', 'home_skill_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_home_skill');
    }
};