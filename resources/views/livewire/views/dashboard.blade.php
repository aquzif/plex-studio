<?php

use App\Models\Ledger;
use App\Traits\Modalable;
use App\Traits\Toastable;
use Livewire\Volt\Component;

new #[\Livewire\Attributes\Layout('layouts.dashboard')] class extends Component {

    use Toastable, Modalable;

    public $series = [];


    public function mount(){
        $this->series = \App\Models\Show::all();
    }

    public function openNewShowModal()
    {
        $this->showModal('new-show-modal');
    }


}; ?>

<div>
    <livewire:modals.new-show-modal/>

    <div
        class="container mx-auto flex flex-wrap flex-row"
        style="max-width: 900px;"
    >

        @foreach($series as $show)
            <x-show-tile :show="$show"/>
        @endforeach
    </div>

    <x-fab
        @click="window.Modals.show('new-show-modal')"
    />
</div>
