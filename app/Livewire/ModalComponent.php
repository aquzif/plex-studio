<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class ModalComponent extends Component
{

    public $name = 'modal';
    public $open = false;


    #[On('openModal')]
    public function openModal($name)
    {
        if ($name == $this->name) {
            $this->open = true;
        }
    }

    public function render()
    {
        return <<<'blade'
            <div></div>
        blade;
    }
}
