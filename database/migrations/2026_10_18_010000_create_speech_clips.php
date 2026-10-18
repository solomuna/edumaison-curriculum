<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Énoncés lus par Mama Judi (ElevenLabs) : un fichier par texte, partagé par
// toutes les familles (contenu pédagogique, aucune donnée d'enfant).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('speech_clips', function (Blueprint $table) {
            $table->id();
            // Empreinte voix + modèle + langue + texte : un même énoncé n'est
            // généré (et facturé) qu'une fois.
            $table->string('hash', 64)->unique();
            $table->string('language', 2);
            $table->text('text');
            $table->unsignedInteger('characters');
            $table->string('model', 64);
            $table->string('file_path');
            $table->timestamps();
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('speech_clips');
    }
};
