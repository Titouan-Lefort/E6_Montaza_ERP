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
        Schema::create('mouvement_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matiere_id')->constrained('matieres')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Qui a fait l'action
            $table->string('type'); // "entree" ou "sortie"
            $table->decimal('quantite', 16, 6);
            $table->decimal('valeur_unitaire', 16, 6)->nullable();
            $table->string('raison')->nullable(); // Explication du mouvement
            $table->timestamp('date')->useCurrent();
            $table->foreignId('cde_ligne_id')->nullable()->constrained('cde_lignes')->onDelete('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvement_stocks');
    }
};
