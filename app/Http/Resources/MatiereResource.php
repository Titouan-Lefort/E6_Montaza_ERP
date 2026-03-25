<?php

namespace App\Http\Resources;

use App\Models\Unite;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MatiereResource extends JsonResource
{
    public function toArray($request)
    {
        $societe_id = $request->input('societe') ?? null;
        $quantite = $this->quantite();
        if ($quantite < 2) {
            $unite_full = $this->unite->full ?? null;
        } else {
            $unite_full = $this->unite->full_plural ?? null;
        }
        $data = [
            'id' => $this->id,
            'refInterne' => $this->ref_interne,
            'sousFamille' => $this->sousFamille->nom ?? null,
            'quantite' => $quantite,
            'refValeurUnitaire' => $this->ref_valeur_unitaire,
            'typeAffichageStock' => $this->typeAffichageStock(),
            'stockMin' => $this->stock_min,
            'designation' => $this->designation,
            'material' => $this->material->nom ?? null,
            'standard' => $this->standardVersion->standard->nom ?? null,
            'standardVersion' => $this->standardVersion->version ?? null,
            'standardPath' => $this->standardVersion->chemin_pdf ?? 'none',
            'dn' => $this->dn ?? null,
            'epaisseur' => $this->epaisseur ?? null,
            'Unite' => $this->unite->short ?? null,
            'Unite_id' => $this->unite->id ?? null,
            'Unite_full' => $unite_full,
            'tooltip' => view('components.stock-tooltip', ['matiere' => $this])->render(),
            'refTooltip' => view('components.ref-tooltip', ['matiere' => $this])->render(),
        ];

        if ($societe_id) {
            $data = array_merge(
                $data,
                [
                    'lastPriceDate' => $this->getLastPrice($societe_id) ? Carbon::parse($this->getLastPrice($societe_id)->date)->format('d/m/Y') : null,
                    'lastPrice_formated' => $this->getLastPrice($societe_id) ? formatNumberArgent($this->getLastPrice($societe_id)->prix_unitaire) : null,
                    'lastPrice' => $this->getLastPrice($societe_id) ? formatNumberArgent($this->getLastPrice($societe_id)->prix_unitaire,true,true) : null,

                    'refexterne' => $this->societeMatiere($societe_id)->ref_externe ?? null,
                ]
            );
        }
        return $data;
    }
}
