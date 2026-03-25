<?php

namespace App\Models;

use App\MatiereMouvement;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Matiere extends Model
{
    use HasFactory;

    // Ces champs resteront modifiables même si la matière est verrouillée
    public const EDITABLE = [
        'sous_famille_id',
        'ref_valeur_unitaire',
        'standard_version_id',
        'stock_min',
    ];

    protected $fillable = [
        'ref_interne',
        'designation',
        'societe_id',
        'unite_id',
        'sous_famille_id',
        'dn',
        'epaisseur',
        'standard_version_id',
        'stock_min',
        'ref_valeur_unitaire',
        'material_id',
    ];


    protected static function booted()
    {
        static::created(function ($matiere) {
            Stock::create([
                'matiere_id' => $matiere->id,
                'quantite' => 0,
                'valeur_unitaire' => 0,
            ]);
            self::logChange($matiere, 'creating');

        });

        // Enregistrer avant la mise à jour d'un modèle
        static::updating(function ($model): void {
            if ($model->isDirty('remember_token')) {
                return;
            }
            self::logChange($model, 'updating');
        });

        // Enregistrer avant la suppression d'un modèle
        static::deleting(function ($model): void {
            self::logChange($model, 'deleting');
        });
    }
    protected static function logChange(Model $model, string $event): void
    {
        ModelChange::create([
            'user_id' => Auth::id(),
            'model_type' => 'matiere',
            'before' => $model->getOriginal(),
            'after' => $model->getAttributes(),
            'event' => $event,
        ]);
    }
    public function societes()
    {
        return $this->belongsToMany(Societe::class, 'societe_matieres')
            ->withTimestamps();
    }
    public function fournisseurs()
    {
        return $this->belongsToMany(Societe::class, 'societe_matieres')
            ->whereIn('societe_type_id', ['3', '2'])
            ->withTimestamps();
    }


    public function unite()
    {
        return $this->belongsTo(Unite::class);
    }
    public function sousFamille()
    {
        return $this->belongsTo(SousFamille::class);
    }
    public function famille() {
        return $this->hasOneThrough(Famille::class, SousFamille::class, 'id', 'id', 'sous_famille_id', 'famille_id');
    }
    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }
    public function standardVersion()
    {
        return $this->belongsTo(StandardVersion::class);
    }
    public function standard()
    {
        return $this->hasOneThrough(Standard::class, StandardVersion::class, 'id', 'id', 'standard_version_id', 'standard_id');
    }
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
    /**
     * Retourne le type d'affichage du stock de la matière.
     * 1 = Affichage par quantité et valeur unitaire
     * 2 = Affichage par valeur unitaire
     *
     * @return int
     */
    public function typeAffichageStock(): int
    {
        if (is_numeric($this->ref_valeur_unitaire) && $this->ref_valeur_unitaire > 0) {
            return 2; // Affichage par valeur unitaire
        } else {
            return 1; // Affichage par quantité et valeur unitaire
        }
    }
    public function quantite()
    {
        if ($this->typeAffichageStock() === 1) {
            return $this->stock->sum('quantite');
        } elseif ($this->typeAffichageStock() === 2) {
            $quantite = 0;
            foreach($this->stock as $stock) {
                $quantite += $stock->quantite * $stock->valeur_unitaire;
            };
            return $quantite;
        } else {
            return $this->stock->sum('quantite');
        }
    }
    public function societeMatieres()
    {
        return $this->hasMany(SocieteMatiere::class);
    }
    public function societeMatiere($societeId)
    {
        return $this->hasOne(SocieteMatiere::class, 'matiere_id', 'id')->where('societe_id', $societeId)->first();
    }
    public function prix()
    {
        return $this->hasManyThrough(
            SocieteMatierePrix::class, // Table cible (les prix)
            SocieteMatiere::class, // Table pivot (associe matières et sociétés)
            'matiere_id', // Clé étrangère sur `societe_matieres`
            'societe_matiere_id', // Clé étrangère sur `societe_matiere_prixs`
            'id', // Clé primaire de `matieres`
            'id' // Clé primaire de `societe_matieres`
        );
    }
    /**
     * Summary of prixPourSociete
     * @param mixed $societeId
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough<SocieteMatierePrix, SocieteMatiere, Matiere>
     */
    public function prixPourSociete($societeId)
    {
        return $this->prix()->whereHas('societeMatiere', function ($query) use ($societeId) {
            $query->where('societe_id', $societeId);
        });
    }
    public function getLastPrice($societe_id = null)
    {
        if ($societe_id) {
            return $this->prixPourSociete($societe_id)->latest()->first();
        } else {
            return $this->prix()->latest()->first();
        }
    }

    public function stock() {
        return $this->hasMany(Stock::class);
    }
    public function mouvementStocks()
    {
        return $this->hasMany(MouvementStock::class);
    }
    public function getLastMouvementStock()
    {
        return $this->mouvementStocks()->latest()->first();
    }

    // Vérifie si la matière est utilisée (et donc partiellement verrouillée)
    public function isLocked(): bool
    {
        // Une matière est considérée comme verrouillée si elle a des mouvements de stock
        // ou si elle est associée à des fournisseurs
        return $this->mouvementStocks()->exists()
            || $this->fournisseurs()->exists()
            || $this->stock()->exists()
            || CdeLigne::where('matiere_id', $this->id)->exists()
            || DdpLigne::where('matiere_id', $this->id)->exists();
    }

    /**
     * Override de la méthode delete pour vérifier si la suppression est autorisée
     */
    public function delete()
    {
        if ($this->isLocked()) {
            throw new \Exception('Impossible de supprimer cette matière car elle est utilisée dans des mouvements de stock, des commandes ou est associée à des fournisseurs.');
        }

        // Supprimer les stocks (qui devraient être vides)
        $this->stock()->delete();

        // Supprimer les relations société-matière (qui devraient être vides)
        $this->societeMatieres()->delete();

        // Appeler la méthode delete du parent
        return parent::delete();
    }
}
