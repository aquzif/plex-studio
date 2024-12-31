<?php

namespace App\Livewire\Modals;

use App\Livewire\ModalComponent;
use App\Utils\TvDBUtils;

class NewShowModal extends ModalComponent {
    public $name = 'new-show-modal';

    public $fetchedData = [];

    public $showName;

    public function mount()
    {

    }

    public function updatedShowName()
    {
        $this->fetchedData = TvDBUtils::search($this->showName);
    }

    public function render()
    {
        return view('livewire.modals.new-show-modal');
    }
}
