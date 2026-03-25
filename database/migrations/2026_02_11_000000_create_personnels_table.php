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
        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->string('telephone_mobile')->nullable();
            $table->string('poste')->nullable();
            $table->string('departement')->nullable();
            $table->date('date_embauche')->nullable();
            $table->date('date_depart')->nullable();
            $table->decimal('salaire', 10, 2)->nullable();
            $table->string('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('numero_securite_sociale')->nullable();
            $table->enum('statut', ['actif', 'en_conge', 'suspendu', 'parti'])->default('actif');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnels');
    }
};
