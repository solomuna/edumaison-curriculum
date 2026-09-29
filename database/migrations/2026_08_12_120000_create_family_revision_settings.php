<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('family_revision_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id')->unique()->constrained()->cascadeOnDelete();
            $table->boolean('enabled')->default(false);
            $table->time('revision_time')->default('19:00');
            $table->json('child_ids')->nullable();
            $table->date('last_triggered_on')->nullable();
            $table->timestampTz('last_triggered_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('family_revision_settings'); }
};
