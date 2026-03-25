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
        Schema::create('type_expeditions', function (Blueprint $table) {
            $table->id();
            $table->string('short')->unique();
            $table->string('nom');
            $table->timestamps();
        });
        Schema::create('cde_notes', function (Blueprint $table) {
            $table->id();
            $table->text('contenu')->nullable();
            $table->integer('ordre')->nullable();
            $table->boolean('is_checked')->default(false);
            $table->foreignId('entite_id')->constrained('entites');
            $table->timestamps();
        });

        Schema::create('cdes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('nom');
            $table->foreignId('ddp_cde_statut_id')->constrained(table: 'ddp_cde_statuts');
            $table->integer('old_statut')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('entite_id')->constrained('entites'); // entité pour qui on fait la commande
            $table->foreignId('ddp_id')->nullable()->constrained('ddps');
            $table->string('affaire_numero')->nullable();
            $table->string('affaire_nom')->nullable();
            $table->string('devis_numero')->nullable();
            $table->foreignId('affaire_suivi_par_id')->nullable()->constrained('users');
            $table->foreignId('acheteur_id')->nullable()->constrained('users');
            $table->decimal('frais_de_port', 13, places: 3)->nullable();
            $table->decimal('frais_divers', 13, places: 3)->nullable();
            $table->string('frais_divers_texte')->nullable();
            $table->decimal('total_ht', 13, places: 3)->nullable();
            $table->integer('tva');
            $table->decimal('total_ttc', 13, places: 3)->nullable();
            $table->foreignId('type_expedition_id')->nullable()->constrained('type_expeditions');
            $table->json('adresse_livraison')->nullable();
            $table->json('adresse_facturation')->nullable();
            $table->foreignId('condition_paiement_id')->nullable()->constrained('condition_paiements');
            $table->boolean('show_ref_fournisseur')->default(false);
            $table->boolean('afficher_destinataire')->default(false);
            $table->foreignId('commentaire_id')->constrained('commentaires')->nullable();
            $table->string('custom_note')->nullable();
            $table->json('changement_livraison')->nullable();
            $table->timestamps();
        });
        Schema::create('cde_cde_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cde_id')->constrained()->onDelete('cascade');
            $table->foreignId('cde_note_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('cde_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cde_id')->constrained('cdes')->onDelete('cascade');
            $table->integer('poste');
            $table->string('ref_interne')->nullable();
            $table->string('ref_fournisseur')->nullable();
            $table->foreignId('matiere_id')->nullable()->constrained('matieres');
            $table->string('designation')->nullable();
            $table->decimal('quantite', 16, places: 6);
            $table->foreignId('ddp_cde_statut_id')->default(1)->constrained('ddp_cde_statuts');
            $table->foreignId('type_expedition_id')->nullable()->constrained('type_expeditions');
            // $table->foreignId('unite_id')->nullable()->constrained('unites');
            $table->decimal('prix_unitaire', 13, places: 3)->nullable();
            $table->decimal('prix', 13, places: 3)->nullable();
            $table->date('date_livraison')->nullable();
            $table->date('date_livraison_reelle')->nullable();
            $table->string('ligne_autre_id')->nullable();
            $table->boolean('is_stocke')->nullable()->default(null);
            $table->timestamps();
        });
        Schema::table('societe_matiere_prixs', function (Blueprint $table) {
            $table->foreignId('cde_ligne_id')->nullable()->constrained('cde_lignes')->onDelete('cascade');
        });
        Schema::create('cde_societe_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('societe_contact_id')->constrained('societe_contacts')->onDelete('cascade');
            $table->foreignId('cde_id')->constrained('cdes')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cde_societe_contacts');
        Schema::table('societe_matiere_prixs', function (Blueprint $table) {
            $table->dropForeign(['cde_ligne_id']);
            $table->dropColumn('cde_ligne_id');
        });
        Schema::dropIfExists('cde_lignes');
        Schema::dropIfExists('cde_cde_notes');
        Schema::dropIfExists('cdes');
        Schema::dropIfExists('cde_notes');
        Schema::dropIfExists('type_expeditions');
    }
};
