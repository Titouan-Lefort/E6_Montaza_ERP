<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $nom
 * @property int $ddp_cde_statut_id
 * @property int|null $old_statut
 * @property int $user_id
 * @property int $entite_id
 * @property int|null $ddp_id
 * @property string|null $devis_numero
 * @property int|null $affaire_suivi_par_id
 * @property int|null $acheteur_id
 * @property numeric|null $frais_de_port
 * @property numeric|null $frais_divers
 * @property string|null $frais_divers_texte
 * @property numeric|null $total_ht
 * @property int $tva
 * @property numeric|null $total_ttc
 * @property int|null $type_expedition_id
 * @property string|null $adresse_livraison
 * @property string|null $adresse_facturation
 * @property int|null $condition_paiement_id
 * @property bool $show_ref_fournisseur
 * @property bool $afficher_destinataire
 * @property int $commentaire_id
 * @property string|null $custom_note
 * @property string|null $changement_livraison
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $affaire_id
 * @property-read \App\Models\User|null $acheteur
 * @property-read \App\Models\Affaire|null $affaire
 * @property-read \App\Models\User|null $affaireSuiviPar
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CdeLigne> $cdeLignes
 * @property-read int|null $cde_lignes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CdeNote> $cdeNotes
 * @property-read int|null $cde_notes_count
 * @property-read \App\Models\Commentaire $commentaire
 * @property-read \App\Models\ConditionPaiement|null $conditionPaiement
 * @property-read \App\Models\Ddp|null $ddp
 * @property-read \App\Models\DdpCdeStatut $ddpCdeStatut
 * @property-read \App\Models\Entite $entite
 * @property-read mixed $etablissement
 * @property-read mixed $societe
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MouvementStock> $mouvementsStock
 * @property-read int|null $mouvements_stock_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SocieteContact> $societeContacts
 * @property-read int|null $societe_contacts_count
 * @property-read \App\Models\DdpCdeStatut $statut
 * @property-read \App\Models\TypeExpedition|null $typeExpedition
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\CdeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereAcheteurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereAdresseFacturation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereAdresseLivraison($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereAffaireId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereAffaireSuiviParId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereAfficherDestinataire($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereChangementLivraison($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereCommentaireId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereConditionPaiementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereCustomNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereDdpCdeStatutId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereDdpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereDevisNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereEntiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereFraisDePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereFraisDivers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereFraisDiversTexte($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereOldStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereShowRefFournisseur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereTotalHt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereTotalTtc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereTva($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereTypeExpeditionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cde whereUserId($value)
 */
	class Cde extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $cde_id
 * @property int $cde_note_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cde $cde
 * @property-read \App\Models\CdeNote $cdeNote
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeCdeNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeCdeNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeCdeNote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeCdeNote whereCdeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeCdeNote whereCdeNoteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeCdeNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeCdeNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeCdeNote whereUpdatedAt($value)
 */
	class CdeCdeNote extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $cde_id
 * @property int $poste
 * @property string|null $ref_interne
 * @property string|null $ref_fournisseur
 * @property int|null $matiere_id
 * @property string|null $designation
 * @property numeric $quantite
 * @property int $ddp_cde_statut_id
 * @property int|null $type_expedition_id
 * @property numeric|null $prix_unitaire
 * @property numeric|null $prix
 * @property string|null $date_livraison
 * @property string|null $date_livraison_reelle
 * @property string|null $ligne_autre_id
 * @property bool|null $is_stocke
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $sous_ligne
 * @property bool $non_livre
 * @property-read \App\Models\Cde $cde
 * @property-read \App\Models\DdpCdeStatut $ddpCdeStatut
 * @property-read \App\Models\Matiere|null $matiere
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MouvementStock> $mouvementsStock
 * @property-read int|null $mouvements_stock_count
 * @property-read \App\Models\TypeExpedition|null $typeExpedition
 * @property-read \App\Models\Unite|null $unite
 * @method static \Database\Factories\CdeLigneFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne whereCdeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne whereDateLivraison($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne whereDateLivraisonReelle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne whereDdpCdeStatutId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne whereDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne whereIsStocke($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne whereLigneAutreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne whereMatiereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne whereNonLivre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne wherePoste($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne wherePrix($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne wherePrixUnitaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne whereRefFournisseur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne whereRefInterne($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne whereSousLigne($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne whereTypeExpeditionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeLigne whereUpdatedAt($value)
 */
	class CdeLigne extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $contenu
 * @property int|null $ordre
 * @property bool $is_checked
 * @property int $entite_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CdeCdeNote> $cdeCdeNote
 * @property-read int|null $cde_cde_note_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CdeNote> $cdenote
 * @property-read int|null $cdenote_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cde> $cdes
 * @property-read int|null $cdes_count
 * @property-read \App\Models\Entite $entite
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeNote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeNote whereContenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeNote whereEntiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeNote whereIsChecked($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeNote whereOrdre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeNote whereUpdatedAt($value)
 */
	class CdeNote extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $societe_contact_id
 * @property int $cde_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cde $cde
 * @property-read \App\Models\SocieteContact $societeContact
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeSocieteContact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeSocieteContact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeSocieteContact query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeSocieteContact whereCdeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeSocieteContact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeSocieteContact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeSocieteContact whereSocieteContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CdeSocieteContact whereUpdatedAt($value)
 */
	class CdeSocieteContact extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $code
 * @property string $nom
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Societe> $societes
 * @property-read int|null $societes_count
 * @method static \Database\Factories\CodeApeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CodeApe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CodeApe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CodeApe query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CodeApe whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CodeApe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CodeApe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CodeApe whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CodeApe whereUpdatedAt($value)
 */
	class CodeApe extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $contenu
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $medias
 * @property-read int|null $medias_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Societe> $societes
 * @property-read int|null $societes_count
 * @method static \Database\Factories\CommentaireFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commentaire newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commentaire newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commentaire query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commentaire whereContenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commentaire whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commentaire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Commentaire whereUpdatedAt($value)
 */
	class Commentaire extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cde> $cdes
 * @property-read int|null $cdes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Societe> $societes
 * @property-read int|null $societes_count
 * @method static \Database\Factories\ConditionPaiementFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConditionPaiement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConditionPaiement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConditionPaiement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConditionPaiement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConditionPaiement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConditionPaiement whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConditionPaiement whereUpdatedAt($value)
 */
	class ConditionPaiement extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $nom
 * @property int $ddp_cde_statut_id
 * @property int|null $old_statut
 * @property int $user_id
 * @property int|null $dossier_suivi_par_id
 * @property int $entite_id
 * @property string|null $date_rendu
 * @property bool $afficher_destinataire
 * @property int $commentaire_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $affaire_id
 * @property-read \App\Models\Affaire|null $affaire
 * @property-read \App\Models\Commentaire $commentaire
 * @property-read \App\Models\DdpCdeStatut $ddpCdeStatut
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DdpLigne> $ddpLigne
 * @property-read int|null $ddp_ligne_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DdpLigneFournisseur> $ddpLigneFournisseur
 * @property-read int|null $ddp_ligne_fournisseur_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DdpLigneFournisseur> $ddpLigneFournisseurs
 * @property-read int|null $ddp_ligne_fournisseurs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DdpLigne> $ddpLignes
 * @property-read int|null $ddp_lignes_count
 * @property-read \App\Models\User|null $dossierSuiviPar
 * @property-read \App\Models\Entite $entite
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\DdpCdeStatut $statut
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\DdpFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ddp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ddp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ddp query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ddp whereAffaireId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ddp whereAfficherDestinataire($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ddp whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ddp whereCommentaireId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ddp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ddp whereDateRendu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ddp whereDdpCdeStatutId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ddp whereDossierSuiviParId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ddp whereEntiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ddp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ddp whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ddp whereOldStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ddp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ddp whereUserId($value)
 */
	class Ddp extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom
 * @property string $couleur
 * @property string $couleur_texte
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\DdpCdeStatutFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpCdeStatut newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpCdeStatut newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpCdeStatut query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpCdeStatut whereCouleur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpCdeStatut whereCouleurTexte($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpCdeStatut whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpCdeStatut whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpCdeStatut whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpCdeStatut whereUpdatedAt($value)
 */
	class DdpCdeStatut extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $ddp_id
 * @property int|null $matiere_id
 * @property numeric|null $quantite
 * @property string|null $ligne_autre_id
 * @property string|null $case_ref
 * @property string|null $case_designation
 * @property string|null $case_quantite
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Ddp $ddp
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DdpLigneFournisseur> $ddpLigneFournisseur
 * @property-read int|null $ddp_ligne_fournisseur_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DdpLigneFournisseur> $ddpLigneFournisseurs
 * @property-read int|null $ddp_ligne_fournisseurs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Societe> $fournisseurs
 * @property-read int|null $fournisseurs_count
 * @property-read \App\Models\Matiere|null $matiere
 * @property-read \App\Models\Unite|null $unite
 * @method static \Database\Factories\DdpLigneFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigne newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigne newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigne query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigne whereCaseDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigne whereCaseQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigne whereCaseRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigne whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigne whereDdpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigne whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigne whereLigneAutreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigne whereMatiereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigne whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigne whereUpdatedAt($value)
 */
	class DdpLigne extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $ddp_ligne_id
 * @property int $societe_id
 * @property int $ddp_cde_statut_id
 * @property int|null $societe_contact_id
 * @property string|null $date_livraison
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Ddp|null $ddp
 * @property-read \App\Models\DdpLigne $ddpLigne
 * @property-read \App\Models\SocieteContact|null $destinataire
 * @property-read \App\Models\Societe|null $fournisseur
 * @property-read \App\Models\Societe $societe
 * @property-read \App\Models\SocieteContact|null $societeContact
 * @property-read \App\Models\DdpCdeStatut|null $statut
 * @method static \Database\Factories\DdpLigneFournisseurFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigneFournisseur newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigneFournisseur newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigneFournisseur query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigneFournisseur whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigneFournisseur whereDateLivraison($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigneFournisseur whereDdpCdeStatutId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigneFournisseur whereDdpLigneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigneFournisseur whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigneFournisseur whereSocieteContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigneFournisseur whereSocieteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DdpLigneFournisseur whereUpdatedAt($value)
 */
	class DdpLigneFournisseur extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $devis_tuyauterie_id
 * @property int $matiere_id
 * @property numeric $quantite_reservee
 * @property int $user_id
 * @property string $statut
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DevisTuyauterie $devisTuyauterie
 * @property-read \App\Models\Matiere $matiere
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisStockReservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisStockReservation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisStockReservation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisStockReservation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisStockReservation whereDevisTuyauterieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisStockReservation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisStockReservation whereMatiereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisStockReservation whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisStockReservation whereQuantiteReservee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisStockReservation whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisStockReservation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisStockReservation whereUserId($value)
 */
	class DevisStockReservation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $reference_projet
 * @property string|null $lieu_intervention
 * @property string|null $client_nom
 * @property string|null $client_contact
 * @property string|null $client_adresse
 * @property \Illuminate\Support\Carbon $date_emission
 * @property int $duree_validite
 * @property array<array-key, mixed>|null $options
 * @property string|null $conditions_paiement
 * @property string|null $delais_execution
 * @property numeric $total_ht
 * @property numeric $total_tva
 * @property numeric $total_ttc
 * @property numeric $marge_globale
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $societe_id
 * @property int|null $societe_contact_id
 * @property bool $is_archived
 * @property int|null $affaire_id
 * @property int|null $dossier_devis_id
 * @property-read \App\Models\Affaire|null $affaire
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $chargesAffaire
 * @property-read int|null $charges_affaire_count
 * @property-read \App\Models\DossierDevis|null $dossierDevis
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DevisTuyauterieSection> $sections
 * @property-read int|null $sections_count
 * @property-read \App\Models\Societe|null $societe
 * @property-read \App\Models\SocieteContact|null $societeContact
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DevisStockReservation> $stockReservations
 * @property-read int|null $stock_reservations_count
 * @method static \Database\Factories\DevisTuyauterieFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereAffaireId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereClientAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereClientContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereClientNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereConditionsPaiement($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereDateEmission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereDelaisExecution($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereDossierDevisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereDureeValidite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereIsArchived($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereLieuIntervention($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereMargeGlobale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereReferenceProjet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereSocieteContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereSocieteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereTotalHt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereTotalTtc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereTotalTva($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterie whereUpdatedAt($value)
 */
	class DevisTuyauterie extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $devis_tuyauterie_section_id
 * @property string $type
 * @property string|null $designation
 * @property string|null $matiere
 * @property numeric $quantite
 * @property string $unite
 * @property numeric $prix_achat
 * @property numeric $prix_unitaire
 * @property numeric $total_ht
 * @property int $ordre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $matiere_id
 * @property numeric|null $quantite_matiere_unitaire Quantité de matière nécessaire pour fabriquer 1 élément
 * @property string|null $unite_matiere Unité de la matière (ml, kg, etc.)
 * @method static \Database\Factories\DevisTuyauterieLigneFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieLigne newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieLigne newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieLigne query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieLigne whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieLigne whereDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieLigne whereDevisTuyauterieSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieLigne whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieLigne whereMatiere($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieLigne whereMatiereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieLigne whereOrdre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieLigne wherePrixAchat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieLigne wherePrixUnitaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieLigne whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieLigne whereQuantiteMatiereUnitaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieLigne whereTotalHt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieLigne whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieLigne whereUnite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieLigne whereUniteMatiere($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieLigne whereUpdatedAt($value)
 */
	class DevisTuyauterieLigne extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $devis_tuyauterie_id
 * @property string $titre
 * @property int $ordre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DevisTuyauterieLigne> $lignes
 * @property-read int|null $lignes_count
 * @method static \Database\Factories\DevisTuyauterieSectionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieSection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieSection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieSection query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieSection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieSection whereDevisTuyauterieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieSection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieSection whereOrdre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieSection whereTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevisTuyauterieSection whereUpdatedAt($value)
 */
	class DevisTuyauterieSection extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $nom
 * @property int|null $affaire_id
 * @property int|null $societe_id
 * @property int|null $societe_contact_id
 * @property string|null $reference_projet
 * @property string|null $lieu_intervention
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $date_creation
 * @property string $statut
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Affaire|null $affaire
 * @property-read \App\Models\User|null $createur
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DevisTuyauterie> $devisTuyauteries
 * @property-read int|null $devis_tuyauteries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DossierDevisQuantitatif> $quantitatifs
 * @property-read int|null $quantitatifs_count
 * @property-read \App\Models\Societe|null $societe
 * @property-read \App\Models\SocieteContact|null $societeContact
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevis query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevis whereAffaireId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevis whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevis whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevis whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevis whereDateCreation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevis whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevis whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevis whereLieuIntervention($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevis whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevis whereReferenceProjet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevis whereSocieteContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevis whereSocieteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevis whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevis whereUpdatedAt($value)
 */
	class DossierDevis extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $dossier_devis_id
 * @property string|null $categorie
 * @property string $designation
 * @property string|null $reference
 * @property numeric $quantite
 * @property string $unite
 * @property string|null $remarques
 * @property int $ordre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $matiere_id
 * @property string $type
 * @property numeric|null $prix_achat
 * @property numeric|null $prix_unitaire
 * @property numeric|null $quantite_matiere_unitaire Quantité de matière nécessaire pour fabriquer 1 élément
 * @property string|null $unite_matiere Unité de la matière (ml, kg, etc.)
 * @property string|null $description_technique
 * @property-read \App\Models\DossierDevis $dossierDevis
 * @property-read \App\Models\Matiere|null $matiere
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif whereCategorie($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif whereDescriptionTechnique($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif whereDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif whereDossierDevisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif whereMatiereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif whereOrdre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif wherePrixAchat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif wherePrixUnitaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif whereQuantiteMatiereUnitaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif whereRemarques($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif whereUnite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif whereUniteMatiere($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierDevisQuantitatif whereUpdatedAt($value)
 */
	class DossierDevisQuantitatif extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Standard> $standards
 * @property-read int|null $standards_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierStandard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierStandard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierStandard query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierStandard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierStandard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierStandard whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DossierStandard whereUpdatedAt($value)
 */
	class DossierStandard extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $adresse
 * @property string $ville
 * @property string $code_postal
 * @property string $tel
 * @property string $siret
 * @property string $rcs
 * @property string $numero_tva
 * @property string $code_ape
 * @property string|null $logo
 * @property string $horaires
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\EntiteFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entite query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entite whereAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entite whereCodeApe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entite whereCodePostal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entite whereHoraires($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entite whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entite whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entite whereNumeroTva($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entite whereRcs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entite whereSiret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entite whereTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Entite whereVille($value)
 */
	class Entite extends \Eloquent {}
}

namespace App\Models{
/**
 * Summary of Etablissement
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $nom
 * @property string|null $adresse
 * @property string|null $code_postal
 * @property string|null $ville
 * @property string|null $region
 * @property int $pay_id
 * @property string|null $siret
 * @property int $societe_id
 * @property int $commentaire_id
 * @property string|null $deleted_at
 * @property string|null $complement_adresse
 * @property-read \App\Models\Commentaire $commentaire
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SocieteContact> $contacts
 * @property-read int|null $contacts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Matiere> $matieres
 * @property-read int|null $matieres_count
 * @property-read \App\Models\Pays $pays
 * @property-read \App\Models\Societe $societe
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SocieteContact> $societeContacts
 * @property-read int|null $societe_contacts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SocieteMatiere> $societeMatieres
 * @property-read int|null $societe_matieres_count
 * @method static \Database\Factories\EtablissementFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Etablissement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Etablissement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Etablissement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Etablissement whereAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Etablissement whereCodePostal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Etablissement whereCommentaireId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Etablissement whereComplementAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Etablissement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Etablissement whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Etablissement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Etablissement whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Etablissement wherePayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Etablissement whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Etablissement whereSiret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Etablissement whereSocieteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Etablissement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Etablissement whereVille($value)
 */
	class Etablissement extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $numero_facture
 * @property \Illuminate\Support\Carbon $date_emission
 * @property numeric $montant_total
 * @property int $reparation_id
 * @property-read \App\Models\Reparation $reparation
 * @method static \Database\Factories\FactureFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Facture newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Facture newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Facture query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Facture whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Facture whereDateEmission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Facture whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Facture whereMontantTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Facture whereNumeroFacture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Facture whereReparationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Facture whereUpdatedAt($value)
 */
	class Facture extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SousFamille> $sousFamilles
 * @property-read int|null $sous_familles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Famille newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Famille newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Famille query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Famille whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Famille whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Famille whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Famille whereUpdatedAt($value)
 */
	class Famille extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $code
 * @property string $nom
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Societe> $societes
 * @property-read int|null $societes_count
 * @method static \Database\Factories\FormeJuridiqueFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormeJuridique newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormeJuridique newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormeJuridique query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormeJuridique whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormeJuridique whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormeJuridique whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormeJuridique whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FormeJuridique whereUpdatedAt($value)
 */
	class FormeJuridique extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom
 * @property string $sujet
 * @property string $contenu
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mailtemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mailtemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mailtemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mailtemplate whereContenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mailtemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mailtemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mailtemplate whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mailtemplate whereSujet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mailtemplate whereUpdatedAt($value)
 */
	class Mailtemplate extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Matiere> $matieres
 * @property-read int|null $matieres_count
 * @method static \Database\Factories\MaterialFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Material whereUpdatedAt($value)
 */
	class Material extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $reference
 * @property string $designation
 * @property string|null $description
 * @property string $numero_serie
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $acquisition_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $desactive
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Affaire> $affaires
 * @property-read int|null $affaires_count
 * @property mixed $code
 * @method static \Database\Factories\MaterielFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materiel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materiel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materiel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materiel whereAcquisitionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materiel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materiel whereDesactive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materiel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materiel whereDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materiel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materiel whereNumeroSerie($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materiel whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materiel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Materiel whereUpdatedAt($value)
 */
	class Materiel extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $ref_interne
 * @property int|null $standard_version_id
 * @property string $designation
 * @property int $sous_famille_id
 * @property int|null $material_id
 * @property int $unite_id
 * @property string|null $dn
 * @property string|null $epaisseur
 * @property int $stock_min
 * @property int|null $ref_valeur_unitaire
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $stock_min_notif_envoyee
 * @property-read \App\Models\Famille|null $famille
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Societe> $fournisseurs
 * @property-read int|null $fournisseurs_count
 * @property-read \App\Models\Material|null $material
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MouvementStock> $mouvementStocks
 * @property-read int|null $mouvement_stocks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SocieteMatierePrix> $prix
 * @property-read int|null $prix_count
 * @property-read \App\Models\Societe|null $societe
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SocieteMatiere> $societeMatieres
 * @property-read int|null $societe_matieres_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Societe> $societes
 * @property-read int|null $societes_count
 * @property-read \App\Models\SousFamille $sousFamille
 * @property-read \App\Models\Standard|null $standard
 * @property-read \App\Models\StandardVersion|null $standardVersion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Stock> $stock
 * @property-read int|null $stock_count
 * @property-read \App\Models\Unite $unite
 * @method static \Database\Factories\MatiereFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matiere newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matiere newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matiere query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matiere whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matiere whereDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matiere whereDn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matiere whereEpaisseur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matiere whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matiere whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matiere whereRefInterne($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matiere whereRefValeurUnitaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matiere whereSousFamilleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matiere whereStandardVersionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matiere whereStockMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matiere whereStockMinNotifEnvoyee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matiere whereUniteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matiere whereUpdatedAt($value)
 */
	class Matiere extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $mediaable_type
 * @property int $mediaable_id
 * @property string $filename
 * @property string $original_filename
 * @property string $path
 * @property string $mime_type
 * @property int $size
 * @property int $uploaded_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $commentaire_id
 * @property int|null $media_type_id
 * @property-read \App\Models\Commentaire|null $commentaire
 * @property-read mixed $full_path
 * @property-read mixed $url
 * @property-read \App\Models\MediaType|null $mediaType
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $mediaable
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereCommentaireId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereMediaTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereMediaableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereMediaableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereOriginalFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Media whereUploadedBy($value)
 */
	class Media extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom
 * @property string|null $background_color_light
 * @property string|null $background_color_dark
 * @property string|null $text_color_light
 * @property string|null $text_color_dark
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MediaType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MediaType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MediaType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MediaType whereBackgroundColorDark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MediaType whereBackgroundColorLight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MediaType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MediaType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MediaType whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MediaType whereTextColorDark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MediaType whereTextColorLight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MediaType whereUpdatedAt($value)
 */
	class MediaType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string $model_type
 * @property array<array-key, mixed>|null $before
 * @property array<array-key, mixed>|null $after
 * @property string $event
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelChange newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelChange newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelChange query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelChange whereAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelChange whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelChange whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelChange whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelChange whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelChange whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelChange whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModelChange whereUserId($value)
 */
	class ModelChange extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $matiere_id
 * @property int $user_id
 * @property string $type
 * @property numeric $quantite
 * @property numeric|null $valeur_unitaire
 * @property string|null $raison
 * @property string $date
 * @property int|null $cde_ligne_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cde|null $cde
 * @property-read \App\Models\CdeLigne|null $cdeLigne
 * @property-read \App\Models\Matiere $matiere
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\MouvementStockFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MouvementStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MouvementStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MouvementStock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MouvementStock whereCdeLigneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MouvementStock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MouvementStock whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MouvementStock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MouvementStock whereMatiereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MouvementStock whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MouvementStock whereRaison($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MouvementStock whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MouvementStock whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MouvementStock whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MouvementStock whereValeurUnitaire($value)
 */
	class MouvementStock extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $role_id
 * @property string $type
 * @property string $data
 * @property bool $read
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Role $role
 * @method static \Database\Factories\NotificationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification withoutTrashed()
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $nom
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Etablissement> $etablissements
 * @property-read int|null $etablissements_count
 * @method static \Database\Factories\PaysFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pays newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pays newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pays query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pays whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pays whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pays whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pays whereUpdatedAt($value)
 */
	class Pays extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\PermissionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereUpdatedAt($value)
 */
	class Permission extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $matricule
 * @property string $nom
 * @property string $prenom
 * @property string $email
 * @property string|null $telephone
 * @property string|null $telephone_mobile
 * @property string|null $poste
 * @property string|null $departement
 * @property \Illuminate\Support\Carbon|null $date_embauche
 * @property \Illuminate\Support\Carbon|null $date_depart
 * @property numeric|null $salaire
 * @property string|null $adresse
 * @property string|null $ville
 * @property string|null $code_postal
 * @property string|null $numero_securite_sociale
 * @property string $statut
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $raison_depart
 * @property string|null $motif_depart
 * @property-read \App\Models\AffairePersonnel|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Affaire> $affaires
 * @property-read int|null $affaires_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonnelConge> $conges
 * @property-read int|null $conges_count
 * @method static \Database\Factories\PersonnelFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereCodePostal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereDateDepart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereDateEmbauche($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereDepartement($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereMatricule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereMotifDepart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereNumeroSecuriteSociale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel wherePoste($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel wherePrenom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereRaisonDepart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereSalaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereTelephoneMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel whereVille($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Personnel withoutTrashed()
 */
	class Personnel extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $personnel_id
 * @property \Illuminate\Support\Carbon $date_debut
 * @property \Illuminate\Support\Carbon $date_fin
 * @property string $type
 * @property string|null $motif
 * @property string $statut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Personnel $personnel
 * @method static \Database\Factories\PersonnelCongeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelConge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelConge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelConge query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelConge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelConge whereDateDebut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelConge whereDateFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelConge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelConge whereMotif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelConge wherePersonnelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelConge whereStatut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelConge whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonnelConge whereUpdatedAt($value)
 */
	class PersonnelConge extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $icon
 * @property string|null $url
 * @property string|null $modal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PredefinedShortcut newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PredefinedShortcut newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PredefinedShortcut query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PredefinedShortcut whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PredefinedShortcut whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PredefinedShortcut whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PredefinedShortcut whereModal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PredefinedShortcut whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PredefinedShortcut whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PredefinedShortcut whereUrl($value)
 */
	class PredefinedShortcut extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $materiel_id
 * @property string $description
 * @property string|null $date_cloture
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $archive
 * @property string $status
 * @property int $user_id
 * @property int|null $affaire_id
 * @property-read \App\Models\Affaire|null $affaire
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Facture> $factures
 * @property-read int|null $factures_count
 * @property-read \App\Models\Materiel|null $materiel
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ReparationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reparation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reparation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reparation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reparation whereAffaireId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reparation whereArchive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reparation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reparation whereDateCloture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reparation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reparation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reparation whereMaterielId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reparation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reparation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reparation whereUserId($value)
 */
	class Reparation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $entite_id
 * @property bool $undeletable
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Entite $entite
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\RoleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereEntiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUndeletable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withoutTrashed()
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $raison_sociale
 * @property int|null $siren
 * @property int $forme_juridique_id
 * @property int|null $code_ape_id
 * @property int $societe_type_id
 * @property string|null $telephone
 * @property string|null $email
 * @property string|null $site_web
 * @property string|null $numero_tva
 * @property int $condition_paiement_id
 * @property int $commentaire_id
 * @property string|null $deleted_at
 * @property-read \App\Models\CodeApe|null $codeApe
 * @property-read \App\Models\Commentaire $commentaire
 * @property-read \App\Models\ConditionPaiement $conditionPaiement
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Etablissement> $etablissements
 * @property-read int|null $etablissements_count
 * @property-read \App\Models\FormeJuridique $formeJuridique
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Matiere> $matieres
 * @property-read int|null $matieres_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SocieteContact> $societeContacts
 * @property-read int|null $societe_contacts_count
 * @property-read \App\Models\SocieteType $societeType
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Societe clients()
 * @method static \Database\Factories\SocieteFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Societe fournisseurs()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Societe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Societe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Societe query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Societe whereCodeApeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Societe whereCommentaireId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Societe whereConditionPaiementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Societe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Societe whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Societe whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Societe whereFormeJuridiqueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Societe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Societe whereNumeroTva($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Societe whereRaisonSociale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Societe whereSiren($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Societe whereSiteWeb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Societe whereSocieteTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Societe whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Societe whereUpdatedAt($value)
 */
	class Societe extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $nom
 * @property string|null $fonction
 * @property string $email
 * @property string|null $telephone_fixe
 * @property string|null $telephone_portable
 * @property int $etablissement_id
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DdpLigneFournisseur> $ddpLigneFournisseurs
 * @property-read int|null $ddp_ligne_fournisseurs_count
 * @property-read \App\Models\Etablissement $etablissement
 * @property-read \App\Models\Societe|null $societe
 * @method static \Database\Factories\SocieteContactFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteContact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteContact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteContact query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteContact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteContact whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteContact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteContact whereEtablissementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteContact whereFonction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteContact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteContact whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteContact whereTelephoneFixe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteContact whereTelephonePortable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteContact whereUpdatedAt($value)
 */
	class SocieteContact extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $matiere_id
 * @property int $societe_id
 * @property string|null $ref_externe
 * @property int|null $standard_version_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $etablissement_id
 * @property-read \App\Models\Etablissement|null $etablissement
 * @property-read \App\Models\Matiere $matiere
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SocieteMatierePrix> $prix
 * @property-read int|null $prix_count
 * @property-read \App\Models\Societe $societe
 * @property-read \App\Models\StandardVersion|null $standardVersion
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatiere newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatiere newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatiere query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatiere whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatiere whereEtablissementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatiere whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatiere whereMatiereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatiere whereRefExterne($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatiere whereSocieteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatiere whereStandardVersionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatiere whereUpdatedAt($value)
 */
	class SocieteMatiere extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $societe_matiere_id
 * @property numeric|null $prix_unitaire
 * @property string|null $description
 * @property string|null $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $ddp_ligne_fournisseur_id
 * @property int|null $cde_ligne_id
 * @property-read \App\Models\Cde|null $cde
 * @property-read \App\Models\CdeLigne|null $cdeLigne
 * @property-read \App\Models\DdpLigne|null $ddpLigne
 * @property-read \App\Models\DdpLigneFournisseur|null $ddpLigneFournisseur
 * @property-read \App\Models\Ddp|null $ddpViaLigne
 * @property-read \App\Models\Matiere|null $matiere
 * @property-read \App\Models\Societe|null $societe
 * @property-read \App\Models\SocieteMatiere $societeMatiere
 * @property-read \App\Models\Unite|null $unite
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatierePrix newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatierePrix newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatierePrix query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatierePrix whereCdeLigneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatierePrix whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatierePrix whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatierePrix whereDdpLigneFournisseurId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatierePrix whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatierePrix whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatierePrix wherePrixUnitaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatierePrix whereSocieteMatiereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteMatierePrix whereUpdatedAt($value)
 */
	class SocieteMatierePrix extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $nom
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Societe> $societes
 * @property-read int|null $societes_count
 * @method static \Database\Factories\SocieteTypeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteType whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocieteType whereUpdatedAt($value)
 */
	class SocieteType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom
 * @property int $famille_id
 * @property int $type_affichage_stock
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \App\Models\Famille $famille
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Matiere> $matieres
 * @property-read int|null $matieres_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SousFamille newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SousFamille newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SousFamille query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SousFamille whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SousFamille whereFamilleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SousFamille whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SousFamille whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SousFamille whereTypeAffichageStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SousFamille whereUpdatedAt($value)
 */
	class SousFamille extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $dossier_standard_id
 * @property string $nom
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DossierStandard $dossierStandard
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Matiere> $matieres
 * @property-read int|null $matieres_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StandardVersion> $versions
 * @property-read int|null $versions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereDossierStandardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Standard whereUpdatedAt($value)
 */
	class Standard extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $standard_id
 * @property string $version
 * @property string $chemin_pdf
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Matiere> $matieres
 * @property-read int|null $matieres_count
 * @property-read \App\Models\Standard $standard
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StandardVersion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StandardVersion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StandardVersion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StandardVersion whereCheminPdf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StandardVersion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StandardVersion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StandardVersion whereStandardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StandardVersion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StandardVersion whereVersion($value)
 */
	class StandardVersion extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $matiere_id
 * @property numeric $quantite
 * @property numeric|null $valeur_unitaire
 * @property string|null $certificat
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Matiere $matiere
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereCertificat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereMatiereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereValeurUnitaire($value)
 */
	class Stock extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $short
 * @property string $nom
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\TypeExpeditionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeExpedition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeExpedition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeExpedition query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeExpedition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeExpedition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeExpedition whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeExpedition whereShort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TypeExpedition whereUpdatedAt($value)
 */
	class TypeExpedition extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $short
 * @property string $full
 * @property string|null $full_plural
 * @property string|null $type
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Matiere> $matieres
 * @property-read int|null $matieres_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unite query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unite whereFull($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unite whereFullPlural($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unite whereShort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unite whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Unite whereUpdatedAt($value)
 */
	class Unite extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $last_name
 * @property string $first_name
 * @property string $phone
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property int $role_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Role $role
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserShortcut> $shortcuts
 * @property-read int|null $shortcuts_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $shortcut_id
 * @property int $ordre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PredefinedShortcut $shortcut
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserShortcut newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserShortcut newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserShortcut query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserShortcut whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserShortcut whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserShortcut whereOrdre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserShortcut whereShortcutId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserShortcut whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserShortcut whereUserId($value)
 */
	class UserShortcut extends \Eloquent {}
}

