<?php

namespace App\Livewire;

use Livewire\Component;

class ShowTile extends Component
{

    public $show;
    public $season;
    public $red = 'red-600';
    public $green = 'green-600';
    public $yellow = 'yellow-500';
    public $color = 'white';
    public $src = '';
    public $title = '';
    public $subValue = '';


    public function mount() {



    }

    public function render()
    {
        return view('livewire.show-tile');
    }
}
