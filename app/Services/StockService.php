<?php

namespace App\Services;

use App\Models\Matiere;
use App\Models\MouvementStock;
use App\Models\Notification as ModelsNotification;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use RuntimeException;
use Log;
use Notification;

class StockService
{
    /**
     * Check if stock is below minimum threshold and send notification
     */
    private function checkStockMinimum(Matiere $matiere): void
    {
        if (!$matiere->stock_min || $matiere->stock_min <= 0) {
            return; // Pas de stock minimum défini
        }

        $stockTotal = $matiere->quantite();

        // On utilise un champ booléen sur la matière pour savoir si la notif a déjà été envoyée
        // Ajoutez un champ 'stock_min_notif_envoyee' (boolean, default false) sur la table matieres
        if ($stockTotal < $matiere->stock_min) {
            if (!$matiere->stock_min_notif_envoyee) {
                \Log::warning('Stock en dessous du minimum', [
                    'matiere_id' => $matiere->id,
                    'matiere_designation' => $matiere->designation,
                    'stock_actuel' => $stockTotal,
                    'stock_minimum' => $matiere->stock_min,
                    'unite' => $matiere->unite->designation ?? '',
                    'user_id' => Auth::id()
                ]);

                ModelsNotification::createNotification(
                    Auth::user()->role,
                    'stock',
                    'Stock minimum atteint',
                    "Le stock de {$matiere->designation} est en dessous du minimum requis.",
                    "stock inférieur à {$matiere->stock_min} {$matiere->unite->short}",
                    'matieres.show',
                    ['matiere' => $matiere->id],
                    'Voir le stock'
                );

                $matiere->stock_min_notif_envoyee = true;
                $matiere->save();
            }
        } else {
            // Si le stock repasse au-dessus du seuil, on réinitialise le flag
            if ($matiere->stock_min_notif_envoyee) {
                $matiere->stock_min_notif_envoyee = false;
                $matiere->save();
            }
        }
    }

    /**
     * Process stock movement (entry or exit)
     *
     * @param int $matiereId Material ID
     * @param string $type Movement type ('entree' or 'sortie')
     * @param float $quantite Quantity to add or remove
     * @param float|null $valeurUnitaire Unit value (required for type 2 materials)
     * @param string|null $raison Reason for movement
     * @param mixed $cde_ligne_id Order line ID reference
     * @return array Success data
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function stock(int $matiereId, string $type, float $quantite, ?float $valeurUnitaire = null, ?string $raison = null, $cde_ligne_id = null): array
    {
        $matiere = Matiere::findOrFail($matiereId);
        $mouvement = null;
        $mouvement1 = null;

        // Check if this is a material with unit value tracking (type 2)
        if ($matiere->typeAffichageStock() == 2) {
            // Make sure we have a unit value
            $valeurUnitaire = $valeurUnitaire ?? $matiere->ref_valeur_unitaire;
            if (!$valeurUnitaire) {
                throw new InvalidArgumentException('Aucune valeur unitaire définie.');
            }

            // Get or create stock entry for this material with this unit value
            $stock = Stock::where('matiere_id', $matiere->id)
                ->where('valeur_unitaire', $valeurUnitaire)
                ->first() ?? Stock::create([
                    'matiere_id' => $matiere->id,
                    'valeur_unitaire' => $valeurUnitaire,
                    'quantite' => 0
                ]);

            // Process entry
            if ($type == 'entree') {
                // Simply add the quantity to the stock with the specified unit value
                $stock->quantite += $quantite;
                $stock->save();

                // Record the movement
                $mouvement = MouvementStock::create([
                    'matiere_id' => $matiere->id,
                    'user_id' => Auth::id(),
                    'type' => $type,
                    'quantite' => $quantite,
                    'valeur_unitaire' => $valeurUnitaire,
                    'cde_ligne_id' => $cde_ligne_id,
                    'raison' => $raison,
                    'date' => now(),
                ]);
            }
            // Process exit
            elseif ($type == 'sortie') {
                if ($stock->quantite < $quantite) {
                    throw new RuntimeException('Stock insuffisant.');
                }

                $stock->quantite -= $quantite;
                $stock->save();

                // Record the movement
                $mouvement = MouvementStock::create([
                    'matiere_id' => $matiere->id,
                    'user_id' => Auth::id(),
                    'type' => $type,
                    'quantite' => $quantite,
                    'valeur_unitaire' => $valeurUnitaire,
                    'cde_ligne_id' => $cde_ligne_id,
                    'raison' => $raison,
                    'date' => now(),
                ]);
            }
        }
        // Simple stock without unit value (type 1)
        else {
            // Get or create stock for this material
            $stock = Stock::firstOrCreate(['matiere_id' => $matiere->id]);

            // Process entry
            if ($type == 'entree') {
                $stock->quantite += $quantite;
                $stock->save();

                // Record the movement
                $mouvement = MouvementStock::create([
                    'matiere_id' => $matiere->id,
                    'user_id' => Auth::id(),
                    'type' => $type,
                    'quantite' => $quantite,
                    'valeur_unitaire' => $valeurUnitaire,
                    'cde_ligne_id' => $cde_ligne_id,
                    'raison' => $raison,
                    'date' => now(),
                ]);
            }
            // Process exit
            elseif ($type == 'sortie') {
                if ($stock->quantite < $quantite) {
                    throw new RuntimeException('Stock insuffisant.');
                }

                $stock->quantite -= $quantite;
                $stock->save();

                // Record the movement
                $mouvement = MouvementStock::create([
                    'matiere_id' => $matiere->id,
                    'user_id' => Auth::id(),
                    'type' => $type,
                    'quantite' => $quantite,
                    'valeur_unitaire' => $valeurUnitaire,
                    'cde_ligne_id' => $cde_ligne_id,
                    'raison' => $raison,
                    'date' => now(),
                ]);
            }
        }

        $result = [
            'mouvement' => $mouvement,
            'mouvement1' => $mouvement1,
        ];

        // Vérifier le stock minimum après toute modification
        $this->checkStockMinimum($matiere);

        return $result;
    }

    /**
     * Delete stock movement and adjust inventory
     *
     * @param MouvementStock $mouvement The stock movement to delete
     * @return array Success data
     * @throws RuntimeException
     */
    public function deleteStockFromMouvement(MouvementStock $mouvement): array
    {
        $matiere = $mouvement->matiere;

        // Determine if this is a tracked value material
        if ($matiere->typeAffichageStock() == 2) {
            // Find the corresponding stock entry
            $stock = Stock::where('matiere_id', $matiere->id)
                ->where('valeur_unitaire', $mouvement->valeur_unitaire)
                ->first();

            if (!$stock) {
                throw new RuntimeException('Entrée de stock non trouvée.');
            }

            // Reverse the movement effect
            if ($mouvement->type == 'entree') {
                // If it was an entry, we need to remove that quantity
                if ($stock->quantite < $mouvement->quantite) {
                    Log::error('Stock insuffisant pour annuler le mouvement', [
                        'stock_id' => $stock->id,
                        'mouvement_id' => $mouvement->id,
                        'quantite' => $mouvement->quantite
                    ]);
                    throw new RuntimeException('Impossible d\'annuler le mouvement: stock insuffisant.');
                }
                $stock->quantite -= $mouvement->quantite;
            } else if ($mouvement->type == 'sortie') {
                // If it was an exit, we need to add that quantity back
                $stock->quantite += $mouvement->quantite;
            }

            $stock->save();
        } else {
            // Simple stock handling
            $stock = Stock::where('matiere_id', $matiere->id)->first();

            if (!$stock) {
                throw new RuntimeException('Entrée de stock non trouvée.');
            }

            // Reverse the movement effect
            if ($mouvement->type == 'entree') {
                // If it was an entry, we need to remove that quantity
                if ($stock->quantite < $mouvement->quantite) {
                    Log::error('Stock insuffisant pour annuler le mouvement', [
                        'stock_id' => $stock->id,
                        'mouvement_id' => $mouvement->id,
                        'quantite' => $mouvement->quantite
                    ]);
                    throw new RuntimeException('Impossible d\'annuler le mouvement: stock insuffisant.');
                }
                $stock->quantite -= $mouvement->quantite;
            } else if ($mouvement->type == 'sortie') {
                // If it was an exit, we need to add that quantity back
                $stock->quantite += $mouvement->quantite;
            }

            $stock->save();
        }

        // Delete the movement record
        $mouvement->delete();

        $result = [
            'message' => 'Mouvement de stock annulé avec succès',
            'stock' => $stock
        ];

        // Vérifier le stock minimum après l'annulation
        $this->checkStockMinimum($matiere);

        return $result;
    }

    /**
     * Adjust stock unit value for a portion of the stock
     *
     * @param int $stockId Stock entry ID to adjust
     * @param float $quantiteAjuster Quantity to adjust
     * @param float $newValue New unit value (must be lower than current)
     * @param string|null $raison Reason for adjustment
     * @return array Success data
     * @throws InvalidArgumentException
     */
    public function ajusterStock(int $stockId, float $quantiteAjuster, float $newValue, ?string $raison = null): array
    {
        $stock = Stock::findOrFail($stockId);
        $matiere = $stock->matiere;
        $currentValue = $stock->valeur_unitaire;

        // Verify the new value is lower than current value
        if ($newValue >= $currentValue) {
            throw new InvalidArgumentException('La nouvelle valeur doit être inférieure à la valeur actuelle.');
        }

        // Verify the quantity to adjust is valid
        if ($quantiteAjuster <= 0 || $quantiteAjuster > $stock->quantite) {
            throw new InvalidArgumentException('Quantité à ajuster invalide.');
        }

        // Calculate the difference - this is effectively a stock reduction
        $reduction = $currentValue - $newValue;
        $reductionPercentage = ($reduction / $currentValue) * 100;

        // Step 1: Reduce the quantity of the original stock
        $stock->quantite -= $quantiteAjuster;
        $stock->save();

        // Step 2: Create a new stock entry with the adjusted value
        $newStock = Stock::create([
            'matiere_id' => $matiere->id,
            'quantite' => $quantiteAjuster,
            'valeur_unitaire' => $newValue,
        ]);

        // Create a movement record to track this adjustment
        $mouvement = MouvementStock::create([
            'matiere_id' => $matiere->id,
            'user_id' => Auth::id(),
            'type' => 'sortie', // This is effectively a reduction in value
            'quantite' => $quantiteAjuster,
            'valeur_unitaire' => $reduction, // The reduction in unit value
            'raison' => $raison ?? "Ajustement de valeur unitaire ($reductionPercentage%)",
            'date' => now(),
        ]);

        $result = [
            'message' => 'Valeur unitaire ajustée avec succès',
            'stock_original' => $stock,
            'stock_nouveau' => $newStock,
            'mouvement' => $mouvement
        ];

        // Vérifier le stock minimum après l'ajustement
        $this->checkStockMinimum($matiere);

        return $result;
    }

    /**
     * Modify an existing stock movement
     *
     * @param MouvementStock $mouvement The movement to modify
     * @param float $newQuantite New quantity
     * @param float|null $newValeurUnitaire New unit value
     * @param string|null $newRaison New reason
     * @return array Success data
     * @throws RuntimeException
     */
    public function modifierMouvement(MouvementStock $mouvement, float $newQuantite, ?float $newValeurUnitaire = null, ?string $newRaison = null): array
    {
        $matiere = $mouvement->matiere;
        $oldQuantite = $mouvement->quantite;
        $oldValeurUnitaire = $mouvement->valeur_unitaire;

        // First, reverse the original movement
        $this->deleteStockFromMouvement($mouvement);

        // Then, create the new movement with updated values
        try {
            $result = $this->stock(
                $matiere->id,
                $mouvement->type, // Keep the same type
                $newQuantite,
                $newValeurUnitaire ?? $oldValeurUnitaire,
                $newRaison ?? $mouvement->raison,
                $mouvement->cde_ligne_id
            );

            $this->checkStockMinimum($matiere);

            return [
                'message' => 'Mouvement de stock modifié avec succès',
                'mouvement' => $result['mouvement']
            ];

        } catch (\Exception $e) {
            // If the new movement fails, restore the original one
            $this->stock(
                $matiere->id,
                $mouvement->type,
                $oldQuantite,
                $oldValeurUnitaire,
                $mouvement->raison,
                $mouvement->cde_ligne_id
            );

            throw new RuntimeException('Erreur lors de la modification: ' . $e->getMessage());
        }
    }
}
