<?php

use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\CdeController;
use App\Http\Controllers\CdeNoteController;
use App\Http\Controllers\DdpController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\EtablissementController;
use App\Http\Controllers\MailtemplateController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\MatierePrixController;
use App\Http\Controllers\ModelChangeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\PersonnelEmploiDuTempsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReferenceDataController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SocieteContactController;
use App\Http\Controllers\SocieteController;
use App\Http\Controllers\StandardController;
use App\Http\Controllers\UserShortcutController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ReparationController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\DevisTuyauterieController;
use App\Http\Controllers\DossierDevisController;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AffaireController;
use App\Http\Controllers\MaterielController;

Route::get('/', function () {
    if (auth()->check()) {
        return view('accueil');
    }
    return view('welcome');
})->middleware(['GetGlobalVariable'])->name('accueil');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'GetGlobalVariable'])->name('dashboard');
Route::get('/dashboard/paillettes', function () {
    return view('dashboard', ['paillettes' => 'oui']);
})->middleware(['auth', 'verified', 'GetGlobalVariable'])->name('dashboard.paillettes');

Route::middleware(['GetGlobalVariable', 'XSSProtection', 'auth'])->group(function () {

    Route::get('/administration', [AdministrationController::class, 'index'])->name('administration.index');
    Route::get('/administration/info', [AdministrationController::class, 'info'])->name('administration.info');
    Route::get('/icons', function () {
        return view('administration.icons');
    })->name('administration.icons');
    Route::get('/administration/info/{entite}', [AdministrationController::class, 'info'])->name('administration.info_entite');
    Route::patch('/administration/info/{entite}/update', [AdministrationController::class, 'update'])->name('administration.update');


    Route::middleware('permission:gerer_les_utilisateurs')->group(function () {
        route::get('/administration/settings', [AppSettingController::class, 'settings'])->name('administration.appsettings.index');
        Route::patch('/administration/settings/update', [AppSettingController::class, 'update'])->name('administration.appsettings.update');
    });


    Route::get('/documentation', [DocumentationController::class, 'index'])->name('documentation.index');
    Route::get('/documentation/download/{format}', [DocumentationController::class, 'download'])->name('documentation.download');
    Route::get('/documentation/images/{filename}', [DocumentationController::class, 'serveImage'])->name('documentation.images');

    Route::get('/profile/{id}', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile-admin', [ProfileController::class, 'updateAdmin'])->name('profile.update_admin');
    Route::delete('/profile/{user}/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{user}/restore', [ProfileController::class, 'restore'])->name('profile.restore');

    Route::post('/notifications/{id}/lu', [NotificationController::class, 'lu'])->name('notifications.lu');
    Route::post('/notifications/lu-all', [NotificationController::class, 'luAll'])->name('notifications.luall');
    Route::post('/notifications/{id}/non-lu', [NotificationController::class, 'nonLu'])->name('notifications.nonlu');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/lus', [NotificationController::class, 'indexLus'])->name('notifications.lus');
    Route::get('/notification/{id}', [NotificationController::class, 'detail'])->name('notifications.detail');
    Route::post('/notifications/transfer', [NotificationController::class, 'transfer'])->name('notifications.transfer');
    Route::get('/notifications/fetch', [NotificationController::class, 'fetch'])->name('notifications.fetch');
    Route::get('/notifications/modal', [NotificationController::class, 'modal'])->name('notifications.modal');

    Route::get('/shortcuts', [UserShortcutController::class, 'index'])->name('shortcuts.index');
    Route::post('/shortcuts', [UserShortcutController::class, 'store'])->name('shortcuts.store');
    Route::delete('/shortcuts/{id}', [UserShortcutController::class, 'destroy'])->name('shortcuts.destroy');
    Route::patch('/shortcuts/update-order', [UserShortcutController::class, 'updateOrder'])->name('shortcuts.updateOrder');


    Route::middleware('permission:gerer_les_utilisateurs')->group(function () {
        Route::get('/profiles', [ProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile/create', [RoleController::class, 'store'])->name('role.store');
    });


    Route::middleware('permission:gerer_les_permissions')->group(function () {
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions');
        Route::get('/permissions/{role}', [PermissionController::class, 'index'])->name('permissions.index');
        Route::post('/permission/role/create', [RoleController::class, 'store'])->name('permissions.role.store');
        Route::put('/permissions/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    });


    Route::middleware('permission:gerer_les_postes')->group(function () {
        Route::get('/postes', [RoleController::class, 'index'])->name('roles');
        Route::get('/postes/{role}', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/postes/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::patch('/postes/{role}/update', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/postes/{role}/delete', [RoleController::class, 'destroy'])->name('roles.destroy');
        Route::patch('/postes/{role}/restore', [RoleController::class, 'restore'])->name('roles.restore');
    });


    Route::middleware('permission:gerer_les_utilisateurs')->group(function () {
        Route::resource('personnel', PersonnelController::class);
        Route::post('/personnel/{personnel}/restore', [PersonnelController::class, 'restore'])->name('personnel.restore');
        Route::get('/personnel-anciens-employes', [PersonnelController::class, 'anciensEmployes'])->name('personnel.anciens-employes');
        Route::get('/personnel/{personnel}/emploi-du-temps', [PersonnelEmploiDuTempsController::class, 'index'])->name('personnel.emploi_du_temps');

        // Routes pour les congés
        Route::post('/personnel/{personnel}/conges', [PersonnelController::class, 'storeConge'])->name('personnel.conges.store');
        Route::patch('/personnel/{personnel}/conges/{conge}', [PersonnelController::class, 'updateConge'])->name('personnel.conges.update');
        Route::delete('/personnel/{personnel}/conges/{conge}', [PersonnelController::class, 'deleteConge'])->name('personnel.conges.delete');

        // Route pour modifier le statut
        Route::patch('/personnel/{personnel}/update-statut', [PersonnelController::class, 'updateStatut'])->name('personnel.updateStatut');
    });


    Route::middleware('permission:voir_historique')->group(function () {
        Route::get('/logs', [ModelChangeController::class, 'index'])->name('model_changes.index');
    });


    // Routes pour les données de référence
    Route::middleware(['permission:gerer_les_donnees_de_reference'])->group(function () {
        Route::get('/administration/reference-data', [ReferenceDataController::class, 'index'])->name('reference-data.index');
        Route::get('/administration/reference-data/modal', [ReferenceDataController::class, 'loadModal'])->name('reference-data.modal');

        // Familles
        Route::post('/administration/reference-data/famille', [ReferenceDataController::class, 'storeFamille'])->name('reference-data.famille.store');
        Route::patch('/administration/reference-data/famille/{famille}', [ReferenceDataController::class, 'updateFamille'])->name('reference-data.famille.update');
        Route::delete('/administration/reference-data/famille/{famille}', [ReferenceDataController::class, 'destroyFamille'])->name('reference-data.famille.destroy');

        // Sous-familles
        Route::post('/administration/reference-data/sous-famille', [ReferenceDataController::class, 'storeSousFamille'])->name('reference-data.sous-famille.store');
        Route::patch('/administration/reference-data/sous-famille/{sousFamille}', [ReferenceDataController::class, 'updateSousFamille'])->name('reference-data.sous-famille.update');
        Route::delete('/administration/reference-data/sous-famille/{sousFamille}', [ReferenceDataController::class, 'destroySousFamille'])->name('reference-data.sous-famille.destroy');

        // Formes juridiques
        Route::post('/administration/reference-data/forme-juridique', [ReferenceDataController::class, 'storeFormeJuridique'])->name('reference-data.forme-juridique.store');
        Route::patch('/administration/reference-data/forme-juridique/{formeJuridique}', [ReferenceDataController::class, 'updateFormeJuridique'])->name('reference-data.forme-juridique.update');
        Route::delete('/administration/reference-data/forme-juridique/{formeJuridique}', [ReferenceDataController::class, 'destroyFormeJuridique'])->name('reference-data.forme-juridique.destroy');

        // Dossiers standards
        Route::post('/administration/reference-data/dossier-standard', [ReferenceDataController::class, 'storeDossierStandard'])->name('reference-data.dossier-standard.store');
        Route::patch('/administration/reference-data/dossier-standard/{dossierStandard}', [ReferenceDataController::class, 'updateDossierStandard'])->name('reference-data.dossier-standard.update');
        Route::delete('/administration/reference-data/dossier-standard/{dossierStandard}', [ReferenceDataController::class, 'destroyDossierStandard'])->name('reference-data.dossier-standard.destroy');

        // Pays
        Route::post('/administration/reference-data/pays', [ReferenceDataController::class, 'storePays'])->name('reference-data.pays.store');
        Route::patch('/administration/reference-data/pays/{pays}', [ReferenceDataController::class, 'updatePays'])->name('reference-data.pays.update');
        Route::delete('/administration/reference-data/pays/{pays}', [ReferenceDataController::class, 'destroyPays'])->name('reference-data.pays.destroy');

        // Codes APE
        Route::post('/administration/reference-data/code-ape', [ReferenceDataController::class, 'storeCodeApe'])->name('reference-data.code-ape.store');
        Route::patch('/administration/reference-data/code-ape/{codeApe}', [ReferenceDataController::class, 'updateCodeApe'])->name('reference-data.code-ape.update');
        Route::delete('/administration/reference-data/code-ape/{codeApe}', [ReferenceDataController::class, 'destroyCodeApe'])->name('reference-data.code-ape.destroy');

        // Conditions de paiement
        Route::post('/administration/reference-data/condition-paiement', [ReferenceDataController::class, 'storeConditionPaiement'])->name('reference-data.condition-paiement.store');
        Route::patch('/administration/reference-data/condition-paiement/{conditionPaiement}', [ReferenceDataController::class, 'updateConditionPaiement'])->name('reference-data.condition-paiement.update');
        Route::delete('/administration/reference-data/condition-paiement/{conditionPaiement}', [ReferenceDataController::class, 'destroyConditionPaiement'])->name('reference-data.condition-paiement.destroy');

        // Matériaux
        Route::post('/administration/reference-data/material', [ReferenceDataController::class, 'storeMaterial'])->name('reference-data.material.store');
        Route::patch('/administration/reference-data/material/{material}', [ReferenceDataController::class, 'updateMaterial'])->name('reference-data.material.update');
        Route::delete('/administration/reference-data/material/{material}', [ReferenceDataController::class, 'destroyMaterial'])->name('reference-data.material.destroy');

        // Unités
        Route::post('/administration/reference-data/unite', [ReferenceDataController::class, 'storeUnite'])->name('reference-data.unite.store');
        Route::patch('/administration/reference-data/unite/{unite}', [ReferenceDataController::class, 'updateUnite'])->name('reference-data.unite.update');
        Route::delete('/administration/reference-data/unite/{unite}', [ReferenceDataController::class, 'destroyUnite'])->name('reference-data.unite.destroy');
    });


    Route::middleware('permission:voir_les_societes')->group(function () {
        Route::get('/societes', [SocieteController::class, 'index'])->name('societes.index');
        Route::get('/societes/client', [SocieteController::class, 'indexClient'])->name('societes.index_client');
        Route::get('/societes/fournisseur', [SocieteController::class, 'indexFournisseur'])->name('societes.index_fournisseur');
        Route::get('/societes/fournisseurs/quickSearch', [SocieteController::class, 'quickSearchFournisseur'])->name('societes.quickSearchFournisseur');
        Route::get('/societe/{societe}', [SocieteController::class, 'show'])->name('societes.show');
        Route::get('/societe/{societe}/json', [SocieteController::class, 'showJson'])->name('societes.show_json');
        Route::get('/societe/{societe}/etablissement/{etablissement}', [SocieteController::class, 'show'])->name('societes.etablissement.show');
        Route::get('/societe/{societe}/etablissements/json', [SocieteController::class, 'showEtablissementsJson'])->name('societes.etablissement.show_json');
        Route::patch('/societe/{id}/commentaire/save', [SocieteController::class, 'updateCommentaire'])->name('societes.commentaire');
        Route::patch('/societe/etablissement/{id}/commentaire/save', [EtablissementController::class, 'updateCommentaire'])->name('societes.etablissement.commentaire');
        Route::get('/societes/{societeId}/etablissements/{etablissementId}/contacts/json', [SocieteContactController::class, 'showJson'])->name('societes.contacts.show_json');

        // Routes pour gérer les matières d'un établissement
        Route::get('/etablissement/{etablissement}/matieres/json', [EtablissementController::class, 'getMatieresJson'])->name('etablissements.matieres.json');
        Route::post('/etablissement/{etablissement}/matieres/attach', [EtablissementController::class, 'attachMatiere'])->name('etablissements.matieres.attach');
        Route::delete('/etablissement/{etablissement}/matieres/{matiere}/detach', [EtablissementController::class, 'detachMatiere'])->name('etablissements.matieres.detach');

        Route::middleware('permission:gerer_les_societes')->group(function () {
            Route::get('/societes/create', [SocieteController::class, 'create'])->name('societes.create');
            Route::post('/societes/store', [SocieteController::class, 'store'])->name('societes.store');
            Route::get('/societe/{societe}/edit', [SocieteController::class, 'edit'])->name('societes.edit');
            Route::patch('/societe/{societe}/update', [SocieteController::class, 'update'])->name('societes.update');
            Route::delete('/societe/{societe}/delete', [SocieteController::class, 'destroy'])->name('societes.destroy');
            Route::patch('/societe/{societe}/restore', [SocieteController::class, 'restore'])->name('societes.restore');
            Route::get('/societe/{societe}/etablissements/create', [EtablissementController::class, 'create'])->name('etablissements.create');
            Route::post('/societe/etablissement/store', [EtablissementController::class, 'store'])->name('etablissements.store');
            Route::get('/societe/{societe}/etablissement/{etablissement}/edit', [EtablissementController::class, 'edit'])->name('etablissements.edit');
            Route::patch('/societe/etablissement/{etablissement}/update', [EtablissementController::class, 'update'])->name('etablissements.update');
            Route::delete('/societe/etablissement/{etablissement}/delete', [EtablissementController::class, 'destroy'])->name('etablissements.destroy');
        });
        Route::middleware('permission:gerer_les_contacts')->group(function () {
            Route::post('/societe/contact/store', [SocieteContactController::class, 'store'])->name('societes.contacts.store');
            Route::get('/societes/contacts/quickCreate', [SocieteContactController::class, 'quickCreate'])->name('societes.contacts.quickCreate');
            Route::get('/societe/{societe}/contact/{contact}/edit', [SocieteContactController::class, 'edit'])->name('societes.contacts.edit');
            Route::patch('/societe/contact/{contact}/update', [SocieteContactController::class, 'update'])->name('societes.contacts.update');
            Route::delete('/societe/contact/{contact}/delete', [SocieteContactController::class, 'destroy'])->name('societes.contacts.destroy');
        });
    });


    Route::middleware('permission:voir_les_matieres')->group(function () {
        Route::get('/matieres', [MatiereController::class, 'index'])->name('matieres.index');
        Route::get('/matieres/verification-devis', [MatiereController::class, 'devisVerification'])->name('matieres.devis_verification');
        Route::post('/matieres/assigner-stock-devis', [MatiereController::class, 'assignerStockDevis'])->name('matieres.assigner_stock_devis');
        Route::get('/matieres/search', [MatiereController::class, 'searchResult'])->name('matieres.search');
        Route::get('/matieres/quickcreate/{modalId}', [MatiereController::class, 'quickCreate'])->name('matieres.quickCreate');
        Route::POST('/matieres/quickcreate/{modalId}', [MatiereController::class, 'quickStore'])->name('matieres.quickStore');
        Route::get('/matieres/quickSearch', [MatiereController::class, 'quickSearch'])->name('matieres.quickSearch');
        Route::get('/matieres/famille/{famille}/sous-familles/json', [MatiereController::class, 'sousFamillesJson'])->name('matieres.sous_familles.json');
        Route::post('/matieres/sous-famille/store', [MatiereController::class, 'storeSousFamille'])->name('matieres.sous_familles.store');
        Route::post('/matieres/familles', [MatiereController::class, 'storeFamille'])->name('matieres.familles.store');
        Route::post('/matieres/sous-familles', [MatiereController::class, 'storeSousFamille'])->name('matieres.sous_familles.store');
        Route::get('/matieres/import', [MatiereController::class, 'importForm'])->name('matieres.import.form');
        Route::post('/matieres/import/preview', [MatiereController::class, 'importExcel'])->name('matieres.import.preview');
        Route::post('/matieres/import/store', [MatiereController::class, 'importExcelStore'])->name('matieres.import.store');
        Route::get('/matieres/import/example', [MatiereController::class, 'importExample'])->name('matieres.import.example');
        Route::get('/matieres/import-database', [MatiereController::class, 'importDatabaseForm'])->name('matieres.import.database.form');
        Route::post('/matieres/import-database', [MatiereController::class, 'importDatabase'])->name('matieres.import.database.preview');
        Route::post('/matieres/import-database/store', [MatiereController::class, 'importDatabaseStore'])->name('matieres.import.database.store');
        Route::get('/matieres/{matiere}/fournisseurs/json', [MatiereController::class, 'fournisseursJson'])->name('matieres.fournisseurs.json');
        Route::get('/matieres/{id}/json', [MatiereController::class, 'getMatiereJson'])->name('matieres.get_json');
        Route::get('/matieres/standards', [StandardController::class, 'index'])->name('standards.index');
        Route::get('/matieres/{matiere}', [MatiereController::class, 'show'])->name('matieres.show');

        // Routes pour la gestion des prix (utilise le nouveau contrôleur)
        Route::get('/matieres/{matiere}/prix/{fournisseur}', [MatierePrixController::class, 'show'])->name('matieres.show_prix');
        Route::post('/matieres/{matiere}/prix/{fournisseur}/store', [MatierePrixController::class, 'store'])->name('matieres.show_prix.store');
        Route::put('/matieres/{matiere}/prix/{fournisseur}/{prix}', [MatierePrixController::class, 'update'])
            ->name('matieres.show_prix.update');
        Route::delete('/matieres/{matiere}/prix/{fournisseur}/{prix}', [MatierePrixController::class, 'delete'])
            ->name('matieres.show_prix.delete');

        Route::post('/matieres/{matiere}/retirer', [MatiereController::class, 'retirerMatiere'])->name('matieres.retirer');
        Route::post('/matieres/{matiere}/ajouter', [MatiereController::class, 'ajouterMatiere'])->name('matieres.ajouter');
        Route::post('/matieres/{matiere}/ajuster', [MatiereController::class, 'ajusterMatiere'])->name('matieres.ajuster');
        Route::get('/matieres/{matiere}/edit', [MatiereController::class, 'edit'])->name('matieres.edit');
        Route::patch('/matieres/{matiere}/update', [MatiereController::class, 'update'])->name('matieres.update');
        Route::delete('/matieres/{matiere}', [MatiereController::class, 'destroy'])->name('matieres.destroy');
        Route::get('/matieres/{id}/mouvements', [MatiereController::class, 'mouvements'])->name('matieres.mouvements');
        Route::delete('/matieres/{matiere}/mouvements/{mouvement}', [MatiereController::class, 'supprimerMouvement'])
            ->name('matieres.mouvement.supprimer');
        Route::put('/matieres/{matiere}/mouvements/{mouvement}', [MatiereController::class, 'modifierMouvement'])
            ->name('matieres.mouvement.modifier');
        Route::post('/matieres/{matiere}/fournisseur/store', [MatiereController::class, 'storeFournisseur'])->name('matieres.fournisseurs.store');
        Route::delete('/matieres/{matiere}/fournisseurs/{fournisseur}', [MatiereController::class, 'detacherFournisseur'])->name('matieres.fournisseurs.detacher');

        Route::delete('/matieres/standards/delete', [StandardController::class, 'destroy'])->name('standards.destroy');
        Route::delete('/matieres/standards/deleteDossier', [StandardController::class, 'destroyDossier'])->name('standards.destroy_dossier');
        Route::get('/matieres/standards/create', [StandardController::class, 'create'])->name('standards.create');
        Route::post('/matieres/standards/create', [StandardController::class, 'store'])->name('standards.store');
        Route::post('/matieres/standards/createDossier', [StandardController::class, 'storeDossier'])->name('standards.store_dossier');
        Route::get('/matieres/standards/{dossier}/standards/json', [StandardController::class, 'showStandardsJson'])->name('standards.show_json');
        Route::get('/matieres/standards/{dossier}/{standard}/versions/json', [StandardController::class, 'showVersionsJson'])->name('standards.show_versions_json');
        Route::get('/matieres/standards/{dossier}/{standard}', [StandardController::class, 'show'])->name('standards.show');
    });


    Route::middleware('permission:gerer_mail_templates')->group(function () {
        Route::get('/mailtemplates', [MailtemplateController::class, 'index'])->name('mailtemplates.index');
        Route::get('/mailtemplates/{mailtemplate}/edit', [MailTemplateController::class, 'edit'])->name('mailtemplates.edit');
        Route::patch('/mailtemplates/{mailtemplate}/update', [MailTemplateController::class, 'update'])->name(name: 'mailtemplates.update');
        Route::post('/mailtemplates/upload-signature', [MailtemplateController::class, 'uploadSignature'])->name('mailtemplates.uploadSignature');
    });


    Route::middleware('permission:voir_les_ddp_et_cde')->group(function () {
        Route::get('/administration/cde-notes/{entite}', [CdeNoteController::class, 'index'])->name('administration.cdeNote.index');
        Route::get('/administration/cde-note/{entite}/create', [CdeNoteController::class, 'create'])->name('administration.cdeNote.create');
        Route::get('/administration/cde-note/{note}', [CdeNoteController::class, 'show'])->name('administration.cdeNote.show');
        Route::post('/administration/cde-note/store', [CdeNoteController::class, 'store'])->name('administration.cdeNote.store');
        Route::patch('/administration/cde-note/{note}/update', [CdeNoteController::class, 'update'])->name('administration.cdeNote.update');
        Route::delete('/administration/cde-note/{note}/destroy', [CdeNoteController::class, 'destroy'])->name('administration.cdeNote.destroy');
        Route::patch('/administration/cde-note/update-order', [CdeNoteController::class, 'updateOrder'])->name('administration.cdeNote.updateOrder');
        Route::get('/ddp&cde', [DdpController::class, 'indexDdp_cde'])->name('ddp_cde.index');
        Route::get('/ddp', [DdpController::class, 'index'])->name('ddp.index');
        Route::get('/colddp', [DdpController::class, 'indexColDdp'])->name('ddp.index_col_ddp');
        Route::get('/colddp/small', [DdpController::class, 'indexColDdpSmall'])->name('ddp.index_col_ddp_small');
        Route::get('/ddp/create', [DdpController::class, 'create'])->name('ddp.create');
        Route::post('/ddp/save', [DdpController::class, 'save'])->name('ddp.save');
        Route::post('/ddp/get-last-code/{entite}', [DdpController::class, 'getLastCode'])->name('ddp.get_last_code');
        Route::patch('/ddp/{id}/commentaire/save', [DdpController::class, 'updateCommentaire'])->name('ddp.commentaire');
        Route::get('/ddp/{ddp}/annuler', [DdpController::class, 'annuler'])->name('ddp.annuler');
        Route::get('/ddp/{ddp}/reprendre', [DdpController::class, 'reprendre'])->name('ddp.reprendre');
        Route::delete('/ddp/{ddp}/destroy', [DdpController::class, 'destroy'])->name('ddp.destroy');
        Route::get('/ddp/{ddp}/validate', [DdpController::class, 'validation'])->name('ddp.validation');
        Route::post('/ddp/{ddp}/validate', [DdpController::class, 'validate'])->name('ddp.validate');
        Route::get('/ddp/{ddp}/annuler-validation', [DdpController::class, 'cancelValidate'])->name('ddp.cancel_validate');
        Route::post('/ddp/{ddp}/save-retours', [DdpController::class, 'saveRetours'])->name('ddp.save_retours');
        Route::get('/ddp/{ddp}/pdfs', [DdpController::class, 'pdfs'])->name('ddp.pdfs');
        Route::get('/ddp/{ddp}/pdfs/download', [DdpController::class, 'pdfsDownload'])->name('ddp.pdfs.download');
        Route::get('/ddp/{ddp}/pdf/{annee}/{nom}', [DdpController::class, 'pdfshow'])->name('ddp.pdfshow');
        Route::get('/ddp/{ddp}/pdf/{annee}/{nom}/download', [DdpController::class, 'pdfDownload'])->name('ddp.pdfdownload');
        Route::get('/ddp/{ddp}', [DdpController::class, 'show'])->name('ddp.show');
        Route::post('/ddp/{ddp}/sendmails', [DdpController::class, 'sendMails'])->name('ddp.sendmails');
        Route::get('/ddp/{ddp}/skipmails', [DdpController::class, 'skipMails'])->name('ddp.skipmails');
        Route::get('/ddp/{ddp}/terminer', [DdpController::class, 'terminer'])->name('ddp.terminer');
        Route::get('/ddp/{ddp}/annuler_terminer', [DdpController::class, 'annuler_terminer'])->name('ddp.annuler_terminer');
        Route::get('/ddp/{ddp}/{societe_contact}/commander', [DdpController::class, 'commander'])->name('ddp.commander');




        Route::get('/cde', [CdeController::class, 'index'])->name('cde.index');
        Route::get('/colcde', [CdeController::class, 'indexColCde'])->name('ddp.index_col_cde');
        Route::get('/colcde/small', [CdeController::class, 'indexColCdeSmall'])->name('ddp.index_col_cde_small');
        Route::get('/cde/create', [CdeController::class, 'create'])->name('cde.create');
        Route::post('/cde/save', [CdeController::class, 'save'])->name('cde.save');
        Route::post('/cde/get-last-code/{entite}', [CdeController::class, 'getLastCode'])->name('cde.get_last_code');
        Route::get('/cde/{cde}', [CdeController::class, 'show'])->name('cde.show');
        Route::get('/cde/{cde}/annuler', [CdeController::class, 'annuler'])->name('cde.annuler');
        Route::get('/cde/{cde}/reprendre', [CdeController::class, 'reprendre'])->name('cde.reprendre');
        Route::patch('/cde/{id}/commentaire/save', [CdeController::class, 'updateCommentaire'])->name('cde.commentaire');
        Route::delete('/cde/{cde}/destroy', [CdeController::class, 'destroy'])->name('cde.destroy');
        Route::get('/cde/{cde}/validate', [CdeController::class, 'validation'])->name('cde.validation');
        Route::post('/cde/{cde}/validate', [CdeController::class, 'validate'])->name('cde.validate');
        Route::get('/cde/{cde}/annuler-validation', [CdeController::class, 'cancelValidate'])->name('cde.cancel_validate');
        Route::post('/cde/{cde}/save-retours', [CdeController::class, 'saveRetours'])->name('cde.save_retours');
        Route::get('/cde/{cde}/reset', [CdeController::class, 'reset'])->name('cde.reset');
        Route::get('/cde/{ddp}/pdf/download/sans-prix', [CdeController::class, 'pdfDownloadSansPrix'])->name('cde.pdfs.pdfdownload_sans_prix');
        Route::get('/cde/{cde}/pdfs/download', [CdeController::class, 'downloadPdfs'])->name('cde.pdfs.download');
        Route::get('/cde/{cde}/pdfshow/{annee}/{nom}', [CdeController::class, 'showPdf'])->name('cde.pdfshow');
        Route::get('/cde/{cde}/skipmails', [CdeController::class, 'skipMails'])->name('cde.skipmails');
        Route::post('/cde/{cde}/sendmails', [CdeController::class, 'sendMails'])->name('cde.sendmails');
        Route::get('/cde/{cde}/terminer', [CdeController::class, 'terminer'])->name('cde.terminer');
        Route::get('/cde/{cde}/annuler_terminer', [CdeController::class, 'annulerTerminer'])->name('cde.annuler_terminer');
        Route::get('/cde/{cde}/terminer_controler', [CdeController::class, 'terminerControler'])->name('cde.terminer_controler');
        Route::get('/cde/{cde}/annuler_terminer_controler', [CdeController::class, 'annulerTerminerControler'])->name('cde.annuler_terminer_controler');
        Route::post('/cde/{cde}/stock/store', [CdeController::class, 'storeStock'])->name('cde.stock.store');
        Route::post('/cde/{cde}/stock/{ligne}/store', [CdeController::class, 'storeStockLigne'])->name('cde.stock.ligne.store');
        Route::post('/cde/{cde}/stock/{ligne}/store-mouvement', [CdeController::class, 'storeMouvement'])->name('cde.stock.mouvement.store');
        Route::patch('/cde/stock/mouvement/{mouvement}/update', [CdeController::class, 'updateMouvement'])->name('cde.stock.mouvement.update');
        Route::delete('/cde/stock/mouvement/{mouvement}/destroy', [CdeController::class, 'destroyMouvement'])->name('cde.stock.mouvement.destroy_single');
        Route::delete('/cde/{cde}/stock/ligne/{ligne}/destroy', [CdeController::class, 'destroyMouvements'])->name('cde.stock.mouvement.destroy');
        Route::get('/cde/{cde}/stock/no', [CdeController::class, 'noStock'])->name('cde.stock.no');
    });
    Route::middleware('permission:voir_les_affaires')->group(function () {
        Route::get('/affaires', [AffaireController::class, 'index'])->name('affaires.index');
        Route::get('/affaires/planning', [AffaireController::class, 'planning'])->name('affaires.planning');
        Route::get('/affaires/suivi', [AffaireController::class, 'suivi'])->name('affaires.suivi');
        Route::get('/colaffaire/small', [AffaireController::class, 'indexColAffaireSmall'])->name('affaires.index_col_small');
        Route::get('/affaires/actualiser', [AffaireController::class, 'actualiserAllTotals'])->name('affaires.actualiser_totals');
        Route::get('/affaires/create', [AffaireController::class, 'create'])->name('affaires.create');
        Route::post('/affaires/store', [AffaireController::class, 'store'])->name('affaires.store');
        Route::get('/affaires/{affaire}/edit', [AffaireController::class, 'edit'])->name('affaires.edit');
        Route::patch('/affaires/{affaire}/update', [AffaireController::class, 'update'])->name('affaires.update');
        Route::delete('/affaires/{affaire}/delete', [AffaireController::class, 'destroy'])->name('affaires.destroy');
        Route::get('/affaires/{affaire}/suivi', [AffaireController::class, 'suiviDetail'])->name('affaires.suivi_detail');
        Route::post('/affaires/{affaire}/suivi-lignes', [AffaireController::class, 'storeSuiviLigne'])->name('affaires.suivi_lignes.store');
        Route::patch('/affaires/{affaire}/suivi-lignes/{ligne}', [AffaireController::class, 'updateSuiviLigne'])->name('affaires.suivi_lignes.update');
        Route::delete('/affaires/{affaire}/suivi-lignes/{ligne}', [AffaireController::class, 'deleteSuiviLigne'])->name('affaires.suivi_lignes.delete');
        Route::post('/affaires/{affaire}/suivi-lignes/import', [AffaireController::class, 'importSuiviLignes'])->name('affaires.suivi_lignes.import');
        Route::get('/affaires/{affaire}', [AffaireController::class, 'show'])->name('affaires.show');
        Route::post('/affaires/{affaire}/assign-devis', [AffaireController::class, 'assignDevis'])->name('affaires.assign_devis');
        Route::delete('/affaires/{affaire}/unassign-devis/{devis}', [AffaireController::class, 'unassignDevis'])->name('affaires.unassign_devis');
        Route::post('/affaires/{affaire}/assign-personnel', [AffaireController::class, 'assignPersonnel'])->name('affaires.assign_personnel');
        Route::delete('/affaires/{affaire}/unassign-personnel/{personnel}', [AffaireController::class, 'unassignPersonnel'])->name('affaires.unassign_personnel');
        Route::patch('/affaires/{affaire}/update-personnel/{personnel}', [AffaireController::class, 'updatePersonnelAssignment'])->name('affaires.update_personnel');

        // Routes pour les tâches du personnel dans les affaires
        Route::get('/affaires/{affaire}/personnel/{personnel}/taches', [AffaireController::class, 'showPersonnelTaches'])->name('affaires.personnel.taches');
        Route::post('/affaires/{affaire}/personnel/{personnel}/taches', [AffaireController::class, 'storePersonnelTache'])->name('affaires.personnel.taches.store');
        Route::patch('/affaires/{affaire}/personnel/{personnel}/taches/{tache}', [AffaireController::class, 'updatePersonnelTache'])->name('affaires.personnel.taches.update');
        Route::delete('/affaires/{affaire}/personnel/{personnel}/taches/{tache}', [AffaireController::class, 'deletePersonnelTache'])->name('affaires.personnel.taches.delete');
        Route::post('/affaires/{affaire}/personnel/{personnel}/taches/{tache}/complete', [AffaireController::class, 'completePersonnelTache'])->name('affaires.personnel.taches.complete');
        Route::post('/affaires/{affaire}/personnel/{personnel}/taches/{tache}/reopen', [AffaireController::class, 'reopenPersonnelTache'])->name('affaires.personnel.taches.reopen');

        // Routes fusionnées depuis Production
        Route::patch('/affaires/{affaire}/update-status', [AffaireController::class, 'updateStatus'])->name('affaires.update_status');
        Route::post('/affaires/{affaire}/assign-materiel', [AffaireController::class, 'assignMateriel'])->name('affaires.assign_materiel');
        Route::post('/affaires/{affaire}/detach-materiel/{materiel}', [AffaireController::class, 'detachMateriel'])->name('affaires.detach_materiel');
    });

    // Routes pour le système de médias
    Route::get('/media/download/{mediaId}', [MediaController::class, 'download'])->name('media.download');
    Route::post('/media/{model}/{id}', [MediaController::class, 'store'])->name('media.store');


    Route::middleware('permission:gerer_les_medias')->group(function () {
        Route::get('/media', [MediaController::class, 'index'])->name('media.index');
        Route::put('/media/{media}', [MediaController::class, 'update'])->name('media.update');
        Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    });


    // Route pour générer un lien signé vers la page d'upload par QR code
    Route::get('/media/generate-qr/{model}/{id}', [MediaController::class, 'generateQrLink'])->name('media.generate-qr');
    Route::get('/media/{id}', [MediaController::class, 'show'])->name('media.show');
    Route::patch('/media/{id}/commentaire/save', [MediaController::class, 'updateCommentaire'])->name('media.commentaire.save');
    Route::patch('/media/{id}/type/save', [MediaController::class, 'updateType'])->name('media.type.save');

    // Dossiers de Devis - Protection par permission
    Route::middleware('permission:voir_les_devis')->group(function () {
        Route::get('/dossiers-devis', [DossierDevisController::class, 'index'])->name('dossiers_devis.index');
        Route::get('/dossiers-devis/create', [DossierDevisController::class, 'create'])->name('dossiers_devis.create');
        Route::post('/dossiers-devis', [DossierDevisController::class, 'store'])->name('dossiers_devis.store');
        Route::get('/dossiers-devis/{dossierDevis}', [DossierDevisController::class, 'show'])->name('dossiers_devis.show');
        Route::get('/dossiers-devis/{dossierDevis}/edit', [DossierDevisController::class, 'edit'])->name('dossiers_devis.edit');
        Route::patch('/dossiers-devis/{dossierDevis}', [DossierDevisController::class, 'update'])->name('dossiers_devis.update');
        Route::delete('/dossiers-devis/{dossierDevis}', [DossierDevisController::class, 'destroy'])->name('dossiers_devis.destroy');
        Route::post('/dossiers-devis/{dossierDevis}/quantitatif', [DossierDevisController::class, 'ajouterQuantitatif'])->name('dossiers_devis.ajouter_quantitatif');
        Route::patch('/dossiers-devis-quantitatif/{quantitatif}', [DossierDevisController::class, 'updateQuantitatif'])->name('dossiers_devis.update_quantitatif');
        Route::delete('/dossiers-devis-quantitatif/{quantitatif}', [DossierDevisController::class, 'deleteQuantitatif'])->name('dossiers_devis.delete_quantitatif');
        Route::get('/dossiers-devis/{dossierDevis}/preparer-devis', [DossierDevisController::class, 'preparerDevis'])->name('dossiers_devis.preparer_devis');
        Route::post('/dossiers-devis/{dossierDevis}/generer-devis', [DossierDevisController::class, 'genererDevis'])->name('dossiers_devis.generer_devis');
        Route::post('/dossiers-devis/{dossierDevis}/archiver', [DossierDevisController::class, 'archiver'])->name('dossiers_devis.archiver');
    });

    // Devis Tuyauterie - Protection par permission
    Route::middleware('permission:voir_les_devis')->group(function () {
        Route::get('/devis-tuyauterie', [DevisTuyauterieController::class, 'index'])->name('devis_tuyauterie.index');
        Route::get('/devis-tuyauterie/archives', [DevisTuyauterieController::class, 'archives'])->name('devis_tuyauterie.archives');
        Route::post('/devis-tuyauterie/{id}/archive', [DevisTuyauterieController::class, 'archive'])->name('devis_tuyauterie.archive');
        Route::post('/devis-tuyauterie/{id}/unarchive', [DevisTuyauterieController::class, 'unarchive'])->name('devis_tuyauterie.unarchive');
        Route::get('/coldevistuyauterie/small', [DevisTuyauterieController::class, 'indexColDevisTuyauterieSmall'])->name('devis_tuyauterie.index_col_small');
        Route::get('/devis-tuyauterie/create', [DevisTuyauterieController::class, 'create'])->name('devis_tuyauterie.create');
        Route::get('/devis-tuyauterie/{id}/edit', [DevisTuyauterieController::class, 'edit'])->name('devis_tuyauterie.edit');
        Route::get('/devis-tuyauterie/{id}', [DevisTuyauterieController::class, 'show'])->name('devis_tuyauterie.show');
        Route::get('/devis-tuyauterie/{id}/pdf', [DevisTuyauterieController::class, 'pdf'])->name('devis_tuyauterie.pdf');
        Route::get('/devis-tuyauterie/{id}/preview', [DevisTuyauterieController::class, 'preview'])->name('devis_tuyauterie.preview');
        Route::get('/devis-tuyauterie/{id}/pdf/download', [DevisTuyauterieController::class, 'downloadPdf'])->name('devis_tuyauterie.download_pdf');
        Route::post('/devis-tuyauterie/{id}/send-email', [DevisTuyauterieController::class, 'sendEmail'])->name('devis_tuyauterie.send_email');
    });
});

// Route d'upload via QR code (protégée par signature)
Route::get('/media/upload/{model}/{id}/{token}', [MediaController::class, 'showUploadForm'])
    ->name('media.upload-form')
    ->middleware('signed');
// Route POST pour traiter l'upload via QR code
Route::post('/media/upload/{model}/{id}/{token}', [MediaController::class, 'uploadFromQr'])
    ->name('media.upload')
    ->middleware(['signed', 'PreventDebugMode'])
    ->withoutMiddleware([VerifyCsrfToken::class, ValidatePostSize::class]);



// La page production utilise des variables globales préparées par le middleware GetGlobalVariable
// et requiert l'authentification et la protection XSS. On inclut ces middlewares ici pour
// que la vue `production.index` ait accès aux variables attendues (ex: $societeTypes).
// Route::middleware(['GetGlobalVariable', 'XSSProtection', 'auth', 'permission:voir_la_production'])->group(function () {
//     Route::get('/production', [ProductionController::class, 'index'])->name('production.index');
//     Route::get('/colproduction/small', [ProductionController::class, 'indexColProductionSmall'])->name('production.index_col_small');
//     Route::get('/production/{affaire}', [ProductionController::class, 'show'])->name('production.show');
//     Route::patch('/production/{affaire}/update-status', [ProductionController::class, 'updateStatus'])->name('production.update_status');
//     Route::post('/production/{affaire}/assign-materiel', [ProductionController::class, 'assignMateriel'])->name('production.assign_materiel');
//     Route::post('/production/{affaire}/detach-materiel/{materiel}', [ProductionController::class, 'detachMateriel'])->name('production.detach_materiel');
// });

Route::middleware(['GetGlobalVariable', 'XSSProtection', 'auth', 'permission:voir_les_reparations'])->group(function () {
    // Routes statiques de réparation (avant les routes paramétrées)
    Route::get('/reparation', [ReparationController::class, 'index'])->name('reparation.index');
    Route::get('/reparation/create', [ReparationController::class, 'create'])->name('reparation.create');
    Route::post('/reparation/store', [ReparationController::class, 'store'])->name('reparation.store');

    // Routes statiques de matériel (avant les routes paramétrées)
    Route::get('/reparation/materiel', [MaterielController::class, 'index'])->name('reparation.materiel.index');
    Route::get('/reparation/materiel/create', [MaterielController::class, 'create'])->name('reparation.materiel.create');
    Route::post('/reparation/materiel/store', [MaterielController::class, 'store'])->name('reparation.materiel.store');
    Route::get('/reparation/mareriel/historique', [MaterielController::class, 'historique'])->name('reparation.materiel.historique');

    //Routes statiques des factures de réparation
    Route::get('/reparation/factures', [FactureController::class, 'index'])->name('reparation.facture.index');
    Route::get('/reparation/facture/create', [FactureController::class, 'create'])->name('reparation.facture.create');
    Route::post('/reparation/facture/store', [FactureController::class, 'store'])->name('reparation.facture.store');

    // Routes paramétrées des factures
    Route::get('/reparation/facture/{facture}', [FactureController::class, 'show'])->name('reparation.facture.show');
    Route::get('/reparation/facture/{facture}/edit', [FactureController::class, 'edit'])->name('reparation.facture.edit');
    Route::patch('/reparation/facture/{facture}', [FactureController::class, 'update'])->name('reparation.facture.update');
    Route::delete('/reparation/facture/{facture}', [FactureController::class, 'destroy'])->name('reparation.facture.destroy');

    // Routes paramétrées de réparation
    Route::get('/reparation/{reparation}', [ReparationController::class, 'show'])->name('reparation.show');
    Route::get('/reparation/{reparation}/edit', [ReparationController::class, 'edit'])->name('reparation.edit');
    Route::patch('/reparation/{reparation}', [ReparationController::class, 'update'])->name('reparation.update');
    Route::post('/reparation/{reparation}/archive', [ReparationController::class, 'archive'])->name('reparation.archive');
    Route::patch('/reparation/{reparation}/status', [ReparationController::class, 'updateStatus'])->name('reparation.updateStatus');

    // Routes paramétrées de matériel
    Route::get('/reparation/materiel/edit/{materiel}', [MaterielController::class, 'edit'])->name('reparation.materiel.edit');
    Route::patch('/reparation/materiel/update/{materiel}', [MaterielController::class, 'update'])->name('reparation.materiel.update');
    Route::delete('/reparation/materiel/destroy/{materiel}', [MaterielController::class, 'destroy'])->name('reparation.materiel.destroy');


});



require __DIR__ . '/auth.php';

// Import matières Excel
require __DIR__ . '/dev_tools.php';


