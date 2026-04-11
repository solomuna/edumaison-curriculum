<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('name');           // ex: "English"
            $table->string('slug')->unique(); // ex: "english-class-1"
            $table->string('color')->nullable(); // ex: "#4CAF50"
            $table->string('icon')->nullable();  // ex: "book-open"
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};