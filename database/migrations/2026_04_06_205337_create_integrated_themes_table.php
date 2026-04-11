<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('integrated_themes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('name');           // ex: "My Family"
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('integrated_themes');
    }
};