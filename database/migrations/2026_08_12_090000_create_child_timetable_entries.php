<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('child_timetable_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id')->constrained()->cascadeOnDelete();
            $table->foreignId('child_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('weekday'); // 1=lundi ... 7=dimanche
            $table->time('start_time');
            $table->time('end_time');
            $table->string('subject_name', 120);
            $table->string('notes', 255)->nullable();
            $table->timestamps();
            $table->unique(['child_id', 'weekday', 'start_time']);
            $table->index(['household_id', 'child_id', 'weekday']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_timetable_entries');
    }
};
