<?php

use App\Models\Ledger;
use App\Traits\Modalable;
use App\Traits\Toastable;
use Livewire\Volt\Component;

new #[\Livewire\Attributes\Layout('layouts.dashboard')] class extends Component {

    use Toastable, Modalable;


    public function openNewShowModal()
    {
        $this->showModal('new-show-modal');
    }


}; ?>

<div>
    <livewire:modals.new-show-modal/>
    <p>test</p>
    <x-fab
        @click="window.Modals.show('new-show-modal')"
    />
</div>
