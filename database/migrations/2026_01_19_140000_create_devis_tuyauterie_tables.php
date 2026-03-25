<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devis_tuyauteries', function (Blueprint $table) {
            $table->id();
            $table->string('reference_projet');
            $table->string('lieu_intervention')->nullable();
            $table->string('client_nom')->nullable();
            $table->string('client_contact')->nullable();
            $table->string('client_adresse')->nullable();
            $table->date('date_emission');
            $table->integer('duree_validite')->default(30);

            // Options (JSON pour simplicité ou colonnes séparées)
            $table->json('options')->nullable();
            $table->string('conditions_paiement')->nullable();
            $table->string('delais_execution')->nullable();

            $table->decimal('total_ht', 15, 2)->default(0);
            $table->decimal('total_tva', 15, 2)->default(0);
            $table->decimal('total_ttc', 15, 2)->default(0);
            $table->decimal('marge_globale', 15, 2)->default(0);

            $table->timestamps();
        });

        Schema::create('devis_tuyauterie_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('devis_tuyauterie_id')->constrained()->cascadeOnDelete();
            $table->string('titre');
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });

        Schema::create('devis_tuyauterie_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('devis_tuyauterie_section_id')->constrained()->cascadeOnDelete();

            $table->string('type')->default('fourniture'); // fourniture, main_d_oeuvre, sous_traitance, consommable
            $table->string('designation')->nullable();
            $table->string('matiere')->nullable();

            $table->decimal('quantite', 10, 2)->default(1);
            $table->string('unite')->default('u');

            $table->decimal('prix_achat', 15, 2)->default(0);
            $table->decimal('prix_unitaire', 15, 2)->default(0);
            $table->decimal('total_ht', 15, 2)->default(0);

            $table->integer('ordre')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devis_tuyauterie_lignes');
        Schema::dropIfExists('devis_tuyauterie_sections');
        Schema::dropIfExists('devis_tuyauteries');
    }
};
