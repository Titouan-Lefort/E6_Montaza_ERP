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
        Schema::create('ddp_cde_statuts', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('couleur');
            $table->string('couleur_texte');
            $table->timestamps();
        });

        Schema::create('ddps', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('nom');
            $table->foreignId('ddp_cde_statut_id')->constrained(table: 'ddp_cde_statuts');
            $table->integer('old_statut')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('dossier_suivi_par_id')->nullable()->constrained('users');
            $table->foreignId('entite_id')->constrained('entites'); // entité pour qui on fait la commande
            $table->date('date_rendu')->nullable();
            $table->boolean('afficher_destinataire')->default(true);
            $table->foreignId('commentaire_id')->constrained('commentaires')->nullable();
            $table->timestamps();
        });
        Schema::create('ddp_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ddp_id')->constrained('ddps')->onDelete('cascade');
            $table->foreignId('matiere_id')->nullable()->constrained('matieres');
            // $table->foreignId('unite_id')->constrained('unites');
            $table->decimal('quantite', 16, places: 6)->nullable();
            $table->string('ligne_autre_id')->nullable();
            $table->string('case_ref')->nullable();
            $table->string('case_designation')->nullable();
            $table->string('case_quantite')->nullable();
            $table->timestamps();
        });
        Schema::create('ddp_ligne_fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ddp_ligne_id')->constrained('ddp_lignes')->onDelete('cascade');
            $table->foreignId('societe_id')->constrained('societes');
            $table->foreignId('ddp_cde_statut_id')->constrained(table: 'ddp_cde_statuts');
            $table->foreignId('societe_contact_id')->nullable()->constrained('societe_contacts');
            $table->string('date_livraison')->nullable();
            $table->timestamps();
        });
        Schema::table('societe_matiere_prixs', function (Blueprint $table) {
            $table->foreignId('ddp_ligne_fournisseur_id')->nullable()->constrained('ddp_ligne_fournisseurs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('societe_matiere', function (Blueprint $table) {
            $table->dropForeign(['ddp_ligne_fournisseur_id']);
        });
        Schema::dropIfExists('ddp_ligne_fournisseur');
        Schema::dropIfExists('ddp_lignes');
        Schema::dropIfExists('ddps');
        Schema::dropIfExists('ddp_cde_statuts');
    }
};
