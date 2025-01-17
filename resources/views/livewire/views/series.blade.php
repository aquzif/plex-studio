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
    public $showDirectory = '';
    public $showSeasons = [];

    public function mount($seriesId)
    {
        $this->seriesId = $seriesId;
        $this->show = \App\Models\Show::find($seriesId);
        $this->showDirectory = $this->show->directory_name;
        $this->showSeasons = $this->show->seasons()->get();


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

    public function updatedShowDirectory($value){
        $this->show->directory_name = $value;
        $this->show->save();
    }

    public function regenerateCatalogue() {

        $name = $this->show->name;
        $tvdbId = $this->show->tvdb_id;

        $this->show->directory_name = "$name {tvdb-$tvdbId}";
        $this->show->save();
        $this->showDirectory = $this->show->directory_name;

    }



}; ?>

<div>
    @if($this->show !== null)

        <div class="container bg-gray-800 min-h-48 mx-auto mt-16 rounded-xl relative"
             style="width:800px;"
        >
            <h2
                class="text-4xl text-white p-4"
            >{{$show->name}}</h2>
            <div class="grid grid-cols-3 gap-4 p-4">
                <x-input
                    label="Katalog"
                    wire:model.live.debounce.500ms="showDirectory"
                    name="showDirectory"
                    placeholder="Katalog"
                    class="p-4"
                />
                <div class="flex align-items-end flex-wrap content-end" >
                    <x-button
                        wire:click="regenerateCatalogue"
                        wire:confirm="Wyczyści to katalog i napisze od nowa. Czy kontynuować?"
                    >
                        generuj
                    </x-button>
                </div>
            </div>

        </div>

        <div
            class="container mx-auto flex flex-wrap flex-row justify-between"
            style="max-width: 900px;"
        >

            @foreach($showSeasons as $season)

                <div
                    wire:click="redirectToSerie({{ $season->id }})"
                    :key="{{$season->id}}"
                    wire:key="{{$season->id}}"
                >
                    <livewire:show-tile
                        :key="$season->id"
                        :season="$season"  />
                </div>

            @endforeach

        </div>

    @endif
</div>
