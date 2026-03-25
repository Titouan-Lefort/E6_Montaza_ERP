<?php

namespace App\View\Components;

use App\Models\Matiere;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StockTooltip extends Component
{
    public $matiere;

    public function __construct($matiereId)
    {
        $this->matiere = Matiere::find($matiereId);
    }

    public function render()
    {
        return view('components.stock-tooltip');
    }
}
