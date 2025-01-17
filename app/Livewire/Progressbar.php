<?php

namespace App\Livewire;

use Livewire\Component;

class Progressbar extends Component
{

    public $value = 0;
    public $max = 0;
    public $error = false;

    public function render()
    {
        return view('livewire.components.progressbar');
    }
}
