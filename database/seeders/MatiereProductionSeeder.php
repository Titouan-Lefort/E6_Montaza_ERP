<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\Matiere;
use App\Models\SousFamille;
use App\Models\Standard;
use App\Models\StandardVersion;
use App\Models\Stock;
use App\Models\Unite;
use DB;
use Illuminate\Database\Seeder;

class MatiereProductionSeeder extends Seeder
{
    public function run() {
        $path = storage_path('app/public/ressources/test2.csv');
        $csv = array_map(function($line) {
            return str_getcsv($line, ';');
        }, file($path));
        $tour = 0;
        $erreur_standard = 0;
        DB::beginTransaction();
        foreach ($csv as $row) {
            $unite = Unite::where('short', 'ILIKE', $row[8])->first()->id ?? null;
            $row[1] = preg_replace('/^\x{FEFF}/u', '', $row[1]);
            if ($row[1] === '') {
                $row[1] = 'Autre';
            }
            $matierial_id = Material::where('nom', 'ILIKE', $row[2])->first()->id ?? null;
            $sous_famille_model = SousFamille::where('nom','ILIKE',trim($row[1]))->first();
            if ($sous_famille_model->type_affichage_stock == 2) {
                $ref_valeur_unitaire = '6';
            } else {
                $ref_valeur_unitaire = null;
            }
            $sous_famille = $sous_famille_model ? $sous_famille_model->id : null;
            $standardModel = Standard::where('nom', 'ILIKE', $row[5])->first();
            $standard = $standardModel ? $standardModel->getLatestVersion()->id : null;
            if ($unite === null) {
                echo 'TOUR :'.$tour . "\n";
                echo "ERREUR Unite :  \n - " . $row[4] . "\n - " . $row[8] . "\n";
            }
            if ($sous_famille === null) {
                echo 'TOUR :'.$tour . "\n";
                echo "ERREUR SousFamille :  \n - " . $row[4] . "\n - " . $row[1] . "\n";
            }
            if ($standard === null) {
                // echo 'TOUR :'.$tour . "\n ERREUR Standard :  \n - " . $row[4] . "\n - " . $row[5] . "\n";
                $erreur_standard++;
            }
            if (Matiere::where('ref_interne', $row[0])->exists()) {
                continue;
            }
            Matiere::create([
                'ref_interne' => $row[0] ?? null,
                'designation' => "{$row[4]}",
                'unite_id' => $unite ?? throw new \Exception("Unite ID is null for row: " . json_encode($row)),
                'sous_famille_id' => $sous_famille ?? throw new \Exception("SousFamille ID is null for row: " . json_encode($row)),
                'standard_version_id' => $standard,
                'material_id' => $matierial_id,
                'ref_valeur_unitaire' => $ref_valeur_unitaire,
                'dn' => $row[6],
                'epaisseur' => $row[7],
                'stock_min' => 0,
            ]);
            $tour++;
        }
        DB::commit();
        echo $erreur_standard . " erreurs sur " . $tour . " lignes.\n";

    }
}
