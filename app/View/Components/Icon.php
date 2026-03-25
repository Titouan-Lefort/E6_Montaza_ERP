<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Icon extends Component
{
    public int $size;

    public string $type;

    public string|null $class;

    /**
     * Summary of __construct
     *
     * @param  int  $size
     * @param  string  $type
     * @param  string|null  $class
     */
    public function __construct($size = 6, $type = 'error_icon', $class = null)
    {
        $this->size = $size; // Taille par défaut à 6
        $this->type = $type;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.icon');
    }
}
