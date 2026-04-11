<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('duel_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('duel_id')->constrained('duels')->onDelete('cascade');
            $table->foreignId('child_id')->constrained('children');
            $table->integer('score')->default(0);
            $table->integer('total')->default(0);
            $table->integer('duration_seconds')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duel_results');
    }
};
