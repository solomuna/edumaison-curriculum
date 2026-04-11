<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');                // ex: "TV Salon", "Tablette Kamgang"
            $table->string('type')->nullable();    // pc, mobile, tablet, tv
            $table->string('user_agent')->nullable();
            $table->string('ip_address')->nullable();
            $table->boolean('is_trusted')->default(false);
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};