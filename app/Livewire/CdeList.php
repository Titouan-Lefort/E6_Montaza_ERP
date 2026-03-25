<?php

namespace App\Livewire;

use App\Models\Cde;
use App\Models\DdpCdeStatut;
use App\Models\Societe;
use Livewire\Component;
use Livewire\WithPagination;

class CdeList extends Component
{
    use WithPagination;

    public $search = '';
    public $statut = '';
    public $societe = '';
    public $sort = 'code';
    public $direction = 'desc';
    public $perPage = 50;
    public $hasMore = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'statut' => ['except' => ''],
        'societe' => ['except' => ''],
        'sort' => ['except' => 'code'],
        'direction' => ['except' => 'desc'],
    ];

    public function mount()
    {
        $this->search = request('search', '');
        $this->statut = request('statut', '');
        $this->societe = request('societe', '');
        $this->sort = request('sort', 'code');
        $this->direction = request('direction', 'desc');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatut()
    {
        $this->resetPage();
    }

    public function updatedSociete()
    {
        $this->resetPage();
    }

    public function sortBy($column)
    {
        if ($this->sort === $column) {
            $this->direction = $this->direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort = $column;
            $this->direction = 'asc';
        }
        $this->resetPage();
    }

    public function loadMore()
    {
        $this->perPage += 50;
    }

    public function render()
    {
        $query = Cde::query()
            ->where('cdes.nom', '!=', 'undefined')
            ->with(['entite', 'user', 'ddpCdeStatut', 'societeContacts', 'cdeLignes.matiere.unite', 'statut'])
            ->when($this->search, function ($query, $search) {
                $terms = explode(' ', $search);

                if (count($terms) == 1) {
                    $query->where(function ($subQuery) use ($search) {
                        $subQuery->where('cdes.nom', 'ILIKE', "%{$search}%")
                            ->orWhere('cdes.code', 'ILIKE', "%{$search}%")
                            ->orWhereHas('user', function ($subQuery) use ($search) {
                                $subQuery->where('first_name', 'ILIKE', "%{$search}%")
                                    ->orWhere('last_name', 'ILIKE', "%{$search}%");
                            })
                            ->orWhereHas('cdeLignes', function ($subQuery) use ($search) {
                                $subQuery->where('ref_interne', 'ILIKE', "%{$search}%")
                                    ->orWhere('ref_fournisseur', 'ILIKE', "%{$search}%")
                                    ->orWhere('designation', 'ILIKE', "%{$search}%");
                            });
                    });
                } else {
                    $query->where(function ($mainQuery) use ($terms) {
                        foreach ($terms as $term) {
                            $mainQuery->where(function ($subQuery) use ($term) {
                                $subQuery->where('cdes.nom', 'ILIKE', "%{$term}%")
                                    ->orWhere('cdes.code', 'ILIKE', "%{$term}%")
                                    ->orWhereHas('user', function ($subQuery) use ($term) {
                                        $subQuery->where('first_name', 'ILIKE', "%{$term}%")
                                            ->orWhere('last_name', 'ILIKE', "%{$term}%");
                                    })
                                    ->orWhereHas('cdeLignes', function ($subQuery) use ($term) {
                                        $subQuery->where('ref_interne', 'ILIKE', "%{$term}%")
                                            ->orWhere('ref_fournisseur', 'ILIKE', "%{$term}%")
                                            ->orWhere('designation', 'ILIKE', "%{$term}%");
                                    });
                            });
                        }
                    });
                }
            })
            ->when($this->statut, function ($query, $statut) {
                $query->where('ddp_cde_statut_id', $statut);
            });

        if ($this->societe) {
            $query->whereHas('societeContacts.etablissement.societe', function ($q) {
                $q->where('id', $this->societe);
            });
        }

        // Appliquer le tri avec groupement par entité
        switch ($this->sort) {
            case 'code':
                $query->orderBy('cdes.entite_id', 'asc')
                      ->orderBy('cdes.code', $this->direction);
                break;
            case 'nom':
                $query->orderBy('cdes.entite_id', 'asc')
                      ->orderBy('cdes.nom', $this->direction);
                break;
            case 'user':
                $query->join('users', 'cdes.user_id', '=', 'users.id')
                    ->orderBy('cdes.entite_id', 'asc')
                    ->orderBy('users.first_name', $this->direction)
                    ->orderBy('users.last_name', $this->direction)
                    ->select('cdes.*');
                break;
            case 'statut':
                $query->join('ddp_cde_statuts', 'cdes.ddp_cde_statut_id', '=', 'ddp_cde_statuts.id')
                    ->orderBy('cdes.entite_id', 'asc')
                    ->orderBy('ddp_cde_statuts.nom', $this->direction)
                    ->select('cdes.*');
                break;
            case 'created_at':
                $query->orderBy('cdes.entite_id', 'asc')
                      ->orderBy('cdes.created_at', $this->direction);
                break;
            default:
                $query->orderBy('cdes.entite_id', 'asc')
                      ->orderBy('cdes.ddp_cde_statut_id', 'asc')
                      ->orderBy('cdes.code', 'asc');
                break;
        }

        $cdes = $query->limit($this->perPage)->get();
        $this->hasMore = $query->count() > $this->perPage;

        // Grouper par entité
        $cdesGrouped = $cdes->groupBy('entite.nom');

        // Données pour les filtres
        $cde_statuts = DdpCdeStatut::all();
        $societes = collect();
        foreach (Cde::where('nom', '!=', 'undefined')->get() as $cde) {
            if ($cde->societe) {
                $societes = $societes->push($cde->societe);
            }
        }
        $societes = $societes->unique('id')->values();

        return view('livewire.cde-list', compact('cdesGrouped', 'cde_statuts', 'societes'));
    }
}
