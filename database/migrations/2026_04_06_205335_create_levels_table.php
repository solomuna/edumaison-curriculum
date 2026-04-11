<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // ex: "Class 1"
            $table->string('slug')->unique(); // ex: "class-1"
            $table->integer('order');         // ex: 1, 2, 3...
            $table->enum('cycle', [
                'pre_nursery',
                'nursery',
                'primary'
            ]);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};