<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Répliques de Mama Judi avec le prénom de l'enfant (« Bravo Paul ! »),
// générées côté serveur avec l'accord du parent et rangées en privé.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('households', function (Blueprint $table) {
            $table->timestamp('child_name_voice_consent_at')->nullable();
        });

        Schema::create('child_voice_clips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained()->cascadeOnDelete();
            $table->string('language', 2);
            $table->string('event', 32);
            $table->unsignedTinyInteger('variant');
            $table->string('file_path');
            // Empreinte (prénom + voix + texte) : régénère si le prénom change ;
            // le prénom lui-même n'est pas recopié ici.
            $table->string('source_hash', 64);
            $table->timestamps();
            $table->unique(['child_id', 'language', 'event', 'variant']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_voice_clips');
        Schema::table('households', function (Blueprint $table) {
            $table->dropColumn('child_name_voice_consent_at');
        });
    }
};
