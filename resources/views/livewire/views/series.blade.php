<?php

use App\Models\Ledger;
use App\Traits\Modalable;
use App\Traits\Toastable;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new #[\Livewire\Attributes\Layout('layouts.dashboard')] class extends Component {

    use Toastable, Modalable;

    //get id from param url
    public $seriesId;
    public $show = null;

    public function mount($seriesId)
    {
        $this->seriesId = $seriesId;
        $this->show = \App\Models\Show::find($seriesId);

        if ($this->show === null){
            $this->redirect(route('dashboard'));
            return;
        }

        if($this->show->type === 'movie'){
            $this->redirect(route('movie', ['movieId' => $this->show->id]));
        }
    }

    public function redirectToSerie($seasonId){
        $show = \App\Models\Season::find($seasonId);
        $this->redirect(route('episodes', ['seasonId' => $show->id, 'seriesId' => $this->seriesId]));
    }



}; ?>

<div>
    @if($this->show !== null)

        <div
            class="container mx-auto flex flex-wrap flex-row justify-between"
            style="max-width: 900px;"
        >

            @foreach($show['seasons'] as $season)

                <div
                    wire:click="redirectToSerie({{ $season->id }})"
                >
                    <x-show-tile :season="$season"  />
                </div>

            @endforeach

        </div>

    @endif
</div>
