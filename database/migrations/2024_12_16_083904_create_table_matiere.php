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
        Schema::create('unites', function (Blueprint $table) {
            $table->id();
            $table->string('short')->unique();
            $table->string('full');
            $table->string('full_plural')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });
        Schema::create('familles', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->timestamps();
        });
        Schema::create('sous_familles', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->foreignId('famille_id')->constrained('familles');
            $table->integer('type_affichage_stock')->default(1);
            $table->timestamps();
        });
        Schema::create('dossier_standards', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->timestamps();
        });
        Schema::create('standards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_standard_id')->constrained('dossier_standards')->cascadeOnDelete();
            $table->string('nom');

            $table->timestamps();
        });

        Schema::create('standard_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('standard_id')->constrained('standards')->cascadeOnDelete();
            $table->string('version');
            $table->string('chemin_pdf'); // Chemin du fichier PDF
            $table->timestamps();
        });
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->timestamps();
        });
        Schema::create('matieres', function (Blueprint $table) {
            $table->id();
            $table->string('ref_interne')->unique();
            $table->foreignId('standard_version_id')->nullable()->constrained('standard_versions');
            $table->string('designation');
            $table->foreignId(column: 'sous_famille_id')->constrained('sous_familles');
            $table->foreignId(column: 'material_id')->nullable()->constrained('materials');
            $table->foreignId(column: 'unite_id')->constrained('unites');
            $table->string('dn')->nullable();
            $table->string('epaisseur')->nullable();
            $table->integer('stock_min');
            $table->integer('ref_valeur_unitaire')->nullable();
            $table->timestamps();
        });
        Schema::create('col_supp_noms', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->timestamps();
        });
        Schema::create('col_supp_vals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('col_supp_nom_id')->constrained('col_supp_noms');
            $table->string('valeur');
            $table->foreignId('matiere_id')->constrained('matieres');
            $table->timestamps();
        });
        Schema::create('societe_matieres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matiere_id')->constrained('matieres')->cascadeOnDelete();
            $table->foreignId('societe_id')->constrained('societes')->cascadeOnDelete();
            $table->string('ref_externe')->nullable(); // Référence fournisseur
            $table->foreignId('standard_version_id')->nullable()->constrained('standard_versions');
            $table->timestamps();
        });
        Schema::create('societe_matiere_prixs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('societe_matiere_id')->constrained('societe_matieres')->cascadeOnDelete();
            $table->decimal('prix_unitaire', 8, 3)->nullable();
            $table->string('description')->nullable();
            $table->dateTime('date')->nullable();
            $table->timestamps();
        });
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matiere_id')->constrained('matieres')->cascadeOnDelete();
            $table->decimal('quantite', 16, 6)->default(0);
            $table->decimal('valeur_unitaire', 16, 6)->nullable();
            $table->string('certificat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standard_versions');
        Schema::dropIfExists('standards');
        Schema::dropIfExists('dossier_standards');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('societe_matiere_prixs');
        Schema::dropIfExists('societe_matieres');
        Schema::dropIfExists('col_supp_vals');
        Schema::dropIfExists('col_supp_noms');
        Schema::dropIfExists('matieres');
        Schema::dropIfExists('sous_familles');
        Schema::dropIfExists('familles');
        Schema::dropIfExists('unites');
    }
};
