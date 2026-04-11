<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('book_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects');
            $table->foreignId('unit_id')->nullable()->constrained('units');
            $table->string('book_name'); // ex: "English Pupil's Book C4"
            $table->integer('page_from')->nullable();
            $table->integer('page_to')->nullable();
            $table->string('chapter')->nullable();
            $table->string('level')->nullable(); // C1, C2...
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_references');
    }
};
