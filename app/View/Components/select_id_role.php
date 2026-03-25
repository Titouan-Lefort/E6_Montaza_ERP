<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class select_id_role extends Component
{
    /**
     * @var array<int, mixed>
     */
    public array $entites;

    public string|null $class;

    public string|null $selected_role;

    public bool|null $onchange;

    /**
     * Summary of __construct
     * @param array<int, mixed> $entites
     * @param string $class
     * @param string $selected_role
     * @param bool $onchange
     */
    public function __construct(array $entites, $class = null, $selected_role = null, $onchange = null)
    {
        $this->entites = $entites;
        $this->class = $class;
        $this->selected_role = $selected_role;
        $this->onchange = $onchange;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-id_role');
    }
}
