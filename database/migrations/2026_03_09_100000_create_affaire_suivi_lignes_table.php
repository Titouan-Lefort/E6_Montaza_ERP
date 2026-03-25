<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affaire_suivi_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affaire_id')->constrained('affaires')->onDelete('cascade');

            // Identification
            $table->string('projet', 100)->nullable();
            $table->string('tm', 50)->nullable();
            $table->string('indice', 20)->nullable();
            $table->string('activite', 100)->nullable();
            $table->string('stade_montage', 100)->nullable();
            $table->string('lot', 50)->nullable();
            $table->string('bloc', 50)->nullable();
            $table->string('panneau', 50)->nullable();
            $table->string('repere', 100)->nullable();

            // Infos techniques
            $table->date('date_reception_iso')->nullable();
            $table->string('fournisseur_prefa', 150)->nullable();
            $table->string('classe', 50)->nullable();
            $table->string('code_ts', 50)->nullable();
            $table->string('traitement_surface', 100)->nullable();
            $table->string('schema_ligne', 150)->nullable();
            $table->string('trigramme', 10)->nullable();

            // Dimensions / Quantités
            $table->decimal('longueur_poids', 10, 2)->nullable();
            $table->decimal('pouces_total', 10, 2)->nullable();
            $table->integer('qtt_cintrages')->nullable();
            $table->string('dn', 50)->nullable();
            $table->string('ep', 50)->nullable();
            $table->string('matiere', 100)->nullable();
            $table->string('categorie', 100)->nullable();
            $table->string('dp_armement', 100)->nullable();

            // Temps estimés
            $table->decimal('temps_fabrication', 8, 2)->nullable();
            $table->decimal('temps_montage_total_estime', 8, 2)->nullable();
            $table->decimal('temps_soudure_estime', 8, 2)->nullable();
            $table->decimal('temps_montage_estime', 8, 2)->nullable();

            // Approvisionnement
            $table->date('matiere_commande_le')->nullable();
            $table->date('appro_matiere')->nullable();

            // Fabrication
            $table->date('piking')->nullable();
            $table->date('fin_debit')->nullable();
            $table->date('debut_fabrication')->nullable();
            $table->string('tuyauteur', 100)->nullable();
            $table->date('fin_assemblage')->nullable();
            $table->integer('nbr_soudure')->nullable();
            $table->string('soudeur', 100)->nullable();
            $table->date('fin_soudage')->nullable();
            $table->date('fin_fabrication')->nullable();

            // Traitement
            $table->date('depart_traitement')->nullable();
            $table->date('retour_traitement')->nullable();

            // Livraison
            $table->date('livraison_bord')->nullable();

            // Montage chantier
            $table->date('debut_montage')->nullable();
            $table->date('monte')->nullable();
            $table->date('soude')->nullable();
            $table->date('supporte')->nullable();
            $table->decimal('nb_heures_montages', 8, 2)->nullable();
            $table->string('equipe_montage', 150)->nullable();
            $table->decimal('nb_heures_soudages', 8, 2)->nullable();
            $table->string('soudeurs', 150)->nullable();

            // Temps réels et différences (calculés ou saisis)
            $table->decimal('temps_montage_total_reel', 8, 2)->nullable();

            // Qualité
            $table->date('eprouve_le')->nullable();
            $table->text('non_conformite')->nullable();

            $table->integer('ordre')->default(0);
            $table->timestamps();

            $table->index('affaire_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affaire_suivi_lignes');
    }
};
