<?php

use App\Models\Ledger;
use App\Traits\Modalable;
use App\Traits\Toastable;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new #[\Livewire\Attributes\Layout('layouts.dashboard')] class extends Component {

    use Toastable, Modalable;

    public $seriesId;
    public $seasonId;

}; ?>

<div >

    {{$seriesId}}
    {{$seasonId}}

</div>
