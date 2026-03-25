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
        Schema::create('affaire_personnel_taches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('affaire_personnel_id');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut', ['a_faire', 'en_cours', 'termine'])->default('a_faire');
            $table->enum('priorite', ['basse', 'normale', 'haute'])->default('normale');
            $table->integer('ordre')->default(0);
            $table->timestamps();

            // Clé étrangère vers la table pivot affaire_personnel
            $table->foreign('affaire_personnel_id')
                  ->references('id')
                  ->on('affaire_personnel')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affaire_personnel_taches');
    }
};
