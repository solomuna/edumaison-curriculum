<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('evening_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children');
            $table->foreignId('subject_id')->nullable()->constrained('subjects');
            $table->foreignId('unit_id')->nullable()->constrained('units');
            $table->foreignId('exam_id')->nullable()->constrained('exams');
            $table->enum('theme_source', ['auto', 'manual', 'mama_judi'])->default('auto');
            $table->string('mama_judi_message')->nullable(); // message vocal personnalise
            $table->enum('status', ['pending', 'active', 'done'])->default('pending');
            $table->timestamp('triggered_at')->nullable();
            $table->json('units_covered')->nullable(); // unites travaillees aujourd'hui
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evening_sessions');
    }
};
