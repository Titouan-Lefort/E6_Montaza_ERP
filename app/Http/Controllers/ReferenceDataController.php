<?php

namespace App\Http\Controllers;

use App\Models\Famille;
use App\Models\SousFamille;
use App\Models\FormeJuridique;
use App\Models\DossierStandard;
use App\Models\Pays;
use App\Models\CodeApe;
use App\Models\ConditionPaiement;
use App\Models\Material;
use App\Models\Unite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReferenceDataController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'familles');

        // Charger seulement les données de l'onglet actif
        $data = [];

        switch ($activeTab) {
            case 'familles':
                $data['familles'] = Famille::with(['sousFamilles' => function($query) {
                    $query->withCount('matieres')->orderBy('nom');
                }])->orderBy('nom')->get();
                break;

            case 'formes':
                $data['formesJuridiques'] = FormeJuridique::withCount('societes')->orderByDesc('societes_count')->orderBy('nom')->paginate(50);
                break;

            case 'dossiers':
                $data['dossiersStandards'] = DossierStandard::withCount('standards')->orderBy('nom')->paginate(50);
                break;

            case 'pays':
                $data['pays'] = Pays::withCount('etablissements')
                    ->orderByDesc('etablissements_count')
                    ->orderBy('nom')
                    ->paginate(50);
                break;

            case 'codes-ape':
                $data['codesApe'] = CodeApe::withCount('societes')
                    ->orderByDesc('societes_count')
                    ->orderBy('code')
                    ->paginate(50);
                break;

            case 'autres':
                $data['conditionsPaiement'] = ConditionPaiement::withCount(relations: ['societes','cdes'])->orderBy('nom')->paginate(50);
                $data['materials'] = Material::withCount('matieres')->orderBy('nom')->paginate(50);
                $data['unites'] = Unite::withCount('matieres')->orderBy('short')->paginate(50);
                break;
        }

        if ($request->ajax()) {
            // Inclure aussi les modales dans la réponse AJAX
            $modalsHtml = '';

            switch ($activeTab) {
                case 'familles':
                    if (isset($data['familles'])) {
                        $modalsHtml .= view('administration.reference-data.modals.famille', $data)->render();
                        $modalsHtml .= view('administration.reference-data.modals.sous-famille', $data)->render();
                    }
                    break;
                case 'formes':
                    if (isset($data['formesJuridiques'])) {
                        $modalsHtml .= view('administration.reference-data.modals.forme-juridique', $data)->render();
                    }
                    break;
                case 'dossiers':
                    if (isset($data['dossiersStandards'])) {
                        $modalsHtml .= view('administration.reference-data.modals.dossier-standard', $data)->render();
                    }
                    break;
                case 'pays':
                    if (isset($data['pays'])) {
                        $modalsHtml .= view('administration.reference-data.modals.pays', $data)->render();
                    }
                    break;
                case 'codes-ape':
                    if (isset($data['codesApe'])) {
                        $modalsHtml .= view('administration.reference-data.modals.code-ape', $data)->render();
                    }
                    break;
                case 'autres':
                    if (isset($data['conditionsPaiement'])) {
                        $modalsHtml .= view('administration.reference-data.modals.condition-paiement', $data)->render();
                    }
                    if (isset($data['materials'])) {
                        $modalsHtml .= view('administration.reference-data.modals.material', $data)->render();
                    }
                    if (isset($data['unites'])) {
                        $modalsHtml .= view('administration.reference-data.modals.unite', $data)->render();
                    }
                    break;
            }

            return response()->json([
                'html' => view("administration.reference-data.tabs.{$activeTab}", $data)->render(),
                'modals' => $modalsHtml
            ]);
        }

        return view('administration.reference-data.index', compact('activeTab') + $data);
    }

    // Gestion des familles
    public function storeFamille(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:familles,nom'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Famille::create(['nom' => $request->nom]);

        return back()->with('success', 'Famille créée avec succès');
    }

    public function updateFamille(Request $request, Famille $famille)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:familles,nom,' . $famille->id
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $famille->update(['nom' => $request->nom]);

        return back()->with('success', 'Famille modifiée avec succès');
    }

    public function destroyFamille(Famille $famille)
    {
        if ($famille->sousFamilles()->count() > 0) {
            return back()->withErrors(['famille' => 'Impossible de supprimer une famille qui contient des sous-familles']);
        }

        $famille->delete();

        return back()->with('success', 'Famille supprimée avec succès');
    }

    // Gestion des sous-familles
    public function storeSousFamille(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'famille_id' => 'required|exists:familles,id',
            'type_affichage_stock' => 'nullable|integer|in:1,2'
        ]);
        if ($request->type_affichage_stock == null) {
            $request->type_affichage_stock = 1;

        }
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        SousFamille::create($request->all());

        return back()->with('success', 'Sous-famille créée avec succès');
    }

    public function updateSousFamille(Request $request, SousFamille $sousFamille)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'famille_id' => 'required|exists:familles,id',
            'type_affichage_stock' => 'nullable|integer|in:1,2'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $sousFamille->update($request->all());

        return back()->with('success', 'Sous-famille modifiée avec succès');
    }

    public function destroySousFamille(SousFamille $sousFamille)
    {
        if ($sousFamille->matieres()->count() > 0) {
            return back()->withErrors(['sous_famille' => 'Impossible de supprimer une sous-famille qui contient des matières']);
        }

        $sousFamille->delete();

        return back()->with('success', 'Sous-famille supprimée avec succès');
    }

    // Gestion des formes juridiques
    public function storeFormeJuridique(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:forme_juridiques,code',
            'nom' => 'required|string|max:255|unique:forme_juridiques,nom'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        FormeJuridique::create($request->all());

        return back()->with('success', 'Forme juridique créée avec succès');
    }

    public function updateFormeJuridique(Request $request, FormeJuridique $formeJuridique)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:forme_juridiques,code,' . $formeJuridique->id,
            'nom' => 'required|string|max:255|unique:forme_juridiques,nom,' . $formeJuridique->id
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $formeJuridique->update($request->all());

        return back()->with('success', 'Forme juridique modifiée avec succès');
    }

    public function destroyFormeJuridique(FormeJuridique $formeJuridique)
    {
        if ($formeJuridique->societes()->count() > 0) {
            return back()->withErrors(['forme_juridique' => 'Impossible de supprimer une forme juridique utilisée par des sociétés']);
        }

        $formeJuridique->delete();

        return back()->with('success', 'Forme juridique supprimée avec succès');
    }

    // Gestion des dossiers standards
    public function storeDossierStandard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:dossier_standards,nom'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DossierStandard::create(['nom' => $request->nom]);

        return back()->with('success', 'Dossier standard créé avec succès');
    }

    public function updateDossierStandard(Request $request, DossierStandard $dossierStandard)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:dossier_standards,nom,' . $dossierStandard->id
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $dossierStandard->update(['nom' => $request->nom]);

        return back()->with('success', 'Dossier standard modifié avec succès');
    }

    public function destroyDossierStandard(DossierStandard $dossierStandard)
    {
        if ($dossierStandard->standards()->count() > 0) {
            return back()->withErrors(['dossier' => 'Impossible de supprimer un dossier qui contient des standards']);
        }

        $dossierStandard->delete();

        return back()->with('success', 'Dossier standard supprimé avec succès');
    }

    // Gestion des pays
    public function storePays(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:pays,nom',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Pays::create(['nom' => $request->nom]);

        return back()->with('success', 'Pays créé avec succès');
    }

    public function updatePays(Request $request, Pays $pays)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:pays,nom,' . $pays->id,
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $pays->update(['nom' => $request->nom]);

        return back()->with('success', 'Pays modifié avec succès');
    }

    public function destroyPays(Pays $pays)
    {
        if ($pays->etablissements()->count() > 0) {
            return back()->withErrors(['pays' => 'Impossible de supprimer un pays qui contient des établissements']);
        }

        $pays->delete();

        return back()->with('success', 'Pays supprimé avec succès');
    }

    // Gestion des codes APE
    public function storeCodeApe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:code_apes,code',
            'nom' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        CodeApe::create($request->all());

        return back()->with('success', 'Code APE créé avec succès');
    }

    public function updateCodeApe(Request $request, CodeApe $codeApe)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:code_apes,code,' . $codeApe->id,
            'nom' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $codeApe->update($request->all());

        return back()->with('success', 'Code APE modifié avec succès');
    }

    public function destroyCodeApe(CodeApe $codeApe)
    {
        if ($codeApe->societes()->count() > 0) {
            return back()->withErrors(['code_ape' => 'Impossible de supprimer un code APE utilisé par des sociétés']);
        }

        $codeApe->delete();

        return back()->with('success', 'Code APE supprimé avec succès');
    }

    // Gestion des conditions de paiement
    public function storeConditionPaiement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:condition_paiements,nom'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        ConditionPaiement::create(['nom' => $request->nom]);

        return back()->with('success', 'Condition de paiement créée avec succès');
    }

    public function updateConditionPaiement(Request $request, ConditionPaiement $conditionPaiement)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:condition_paiements,nom,' . $conditionPaiement->id
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $conditionPaiement->update(['nom' => $request->nom]);

        return back()->with('success', 'Condition de paiement modifiée avec succès');
    }

    public function destroyConditionPaiement(ConditionPaiement $conditionPaiement)
    {
        $conditionPaiement->delete();

        return back()->with('success', 'Condition de paiement supprimée avec succès');
    }

    // Gestion des matériaux
    public function storeMaterial(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:materials,nom'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Material::create(['nom' => $request->nom]);

        return back()->with('success', 'Matériau créé avec succès');
    }

    public function updateMaterial(Request $request, Material $material)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:materials,nom,' . $material->id
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $material->update(['nom' => $request->nom]);

        return back()->with('success', 'Matériau modifié avec succès');
    }

    public function destroyMaterial(Material $material)
    {
        $material->delete();

        return back()->with('success', 'Matériau supprimé avec succès');
    }

    // Gestion des unités
    public function storeUnite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'short' => 'required|string|max:10|unique:unites,short',
            'full' => 'required|string|max:50',
            'full_plural' => 'nullable|string|max:50',
            'type' => 'nullable|string|max:50'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Unite::create($request->all());

        return back()->with('success', 'Unité créée avec succès');
    }

    public function updateUnite(Request $request, Unite $unite)
    {
        $validator = Validator::make($request->all(), [
            'short' => 'required|string|max:10|unique:unites,short,' . $unite->id,
            'full' => 'required|string|max:50',
            'full_plural' => 'nullable|string|max:50',
            'type' => 'nullable|string|max:50'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $unite->update($request->all());

        return back()->with('success', 'Unité modifiée avec succès');
    }

    public function destroyUnite(Unite $unite)
    {
        if ($unite->matieres()->count() > 0) {
            return back()->withErrors(['unite' => 'Impossible de supprimer une unité utilisée par des matières']);
        }

        $unite->delete();

        return back()->with('success', 'Unité supprimée avec succès');
    }

    public function loadModal(Request $request)
    {
        $modalName = $request->get('modal');
        $tab = $request->get('tab');

        // Charger seulement les données nécessaires pour la modale
        $data = [];

        if (str_contains($modalName, 'famille') || str_contains($modalName, 'sous-famille')) {
            $data['familles'] = Famille::orderBy('nom')->get();
            if (str_contains($modalName, 'sous-famille')) {
                // Récupérer les sous-familles pour les modales d'édition/suppression
                $familles = Famille::with('sousFamilles')->orderBy('nom')->get();
                $data['familles'] = $familles;
            }
        }

        if (str_contains($modalName, 'forme-juridique')) {
            $data['formesJuridiques'] = FormeJuridique::orderBy('nom')->get();
        }

        if (str_contains($modalName, 'dossier-standard')) {
            $data['dossiersStandards'] = DossierStandard::withCount('standards')->orderBy('nom')->get();
        }

        if (str_contains($modalName, 'pays')) {
            $data['pays'] = Pays::withCount('etablissements')->orderBy('nom')->get();
        }

        if (str_contains($modalName, 'code-ape')) {
            $data['codesApe'] = CodeApe::withCount('societes')->orderBy('code')->get();
        }

        if (str_contains($modalName, 'condition-paiement')) {
            $data['conditionsPaiement'] = ConditionPaiement::orderBy('nom')->get();
        }

        if (str_contains($modalName, 'material')) {
            $data['materials'] = Material::orderBy('nom')->get();
        }

        if (str_contains($modalName, 'unite')) {
            $data['unites'] = Unite::withCount('matieres')->orderBy('short')->get();
        }

        // Détermine le bon fichier de modale à charger en fonction du nom de la modale
        $modalFile = 'famille';
        if (str_contains($modalName, 'sous-famille')) {
            $modalFile = 'sous-famille';
        } elseif (str_contains($modalName, 'forme-juridique')) {
            $modalFile = 'forme-juridique';
        } elseif (str_contains($modalName, 'dossier-standard')) {
            $modalFile = 'dossier-standard';
        } elseif (str_contains($modalName, 'pays')) {
            $modalFile = 'pays';
        } elseif (str_contains($modalName, 'code-ape')) {
            $modalFile = 'code-ape';
        } elseif (str_contains($modalName, 'condition-paiement')) {
            $modalFile = 'condition-paiement';
        } elseif (str_contains($modalName, 'material')) {
            $modalFile = 'material';
        } elseif (str_contains($modalName, 'unite')) {
            $modalFile = 'unite';
        }

        return view("administration.reference-data.modals.{$modalFile}", $data);
    }
}
