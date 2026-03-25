<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('predefined_shortcuts', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Titre du raccourci
            $table->string('icon');  // Chemin ou nom de l'icône
            $table->string('url')->nullable();   // URL cible
            $table->json('modal')->nullable(); // Données pour la modal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predefined_shortcuts');
    }
};
