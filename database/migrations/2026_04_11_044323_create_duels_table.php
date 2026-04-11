<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('duels', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->foreignId('child1_id')->constrained('children');
            $table->foreignId('child2_id')->constrained('children');
            $table->foreignId('subject_id')->nullable()->constrained('subjects');
            $table->integer('nb_exercises')->default(10);
            $table->integer('duration_seconds')->default(300);
            $table->json('exercise_ids')->nullable(); // liste des exercise_id communs
            $table->enum('status', ['pending', 'active', 'finished'])->default('pending');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duels');
    }
};
