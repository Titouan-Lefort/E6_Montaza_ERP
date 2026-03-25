<?php

namespace Database\Factories;

use App\Models\Matiere;
use App\Models\Societe;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Unite;
use App\Models\SousFamille;
use App\Models\Standard;
use App\Models\StandardVersion;

class MatiereFactory extends Factory
{
    protected $model = Matiere::class;

    public function definition()
    {
        $societe = Societe::get('id')->random();
        $unite = Unite::get('id')->random();
        $standard = StandardVersion::get('id')->random();
        $sous_famille = SousFamille::get('id')->random();
        return [
            'ref_interne' => strtoupper($this->faker->lexify('??')) . '-' . $this->faker->numerify('####'),
            'designation' => $this->faker->word(),
            'unite_id' => $unite,
            'sous_famille_id' => $sous_famille,
            'standard_version_id' => $standard,
            'dn' => rand(1, 100),
            'epaisseur' => $this->faker->randomFloat(2, 0.1, 10),
            'stock_min' => rand(1, 100),
        ];
    }
}
