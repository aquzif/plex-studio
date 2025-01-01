<?php

use App\Models\Ledger;
use App\Traits\Modalable;
use App\Traits\Toastable;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new #[\Livewire\Attributes\Layout('layouts.dashboard')] class extends Component {

    use Toastable, Modalable;

    public $series = [];
    public $page = 'series';
    public $order = 'asc';


    public function mount()
    {
        $this->refreshShows();
    }

    #[On('refreshShows')]
    public function refreshShows()
    {
        $this->series = \App\Models\Show::
        where('type', match ($this->page){
            'series' => 'series',
            'movie' => 'movie',
        })->orderBy('name', $this->order)
            ->get();
    }

    public function openNewShowModal()
    {
        $this->showModal('new-show-modal');
    }

    public function changePage($page) {
        $this->page = $page;
        $this->refreshShows();
    }

    public function reorder() {

        $this->order = $this->order === 'asc' ? 'desc' : 'asc';
        $this->refreshShows();

    }

    public function redirectToShow($showId){
        $show = \App\Models\Show::find($showId);
        if($show->type === 'series'){
            $this->redirect(route('series', $show));
        }else{
            $this->redirect(route('movie', $show));
        }
    }


}; ?>

<div >
    <livewire:modals.new-show-modal/>

    <div class="flex justify-center items-center">
        <div class="flex items-center">
            @foreach(['series','movie'] as $tab)
                <x-tab
                    active="{{$page === $tab}}"
                    wire:key="{{$tab}}"
                    wire:click="changePage('{{$tab}}')"
                >
                    {{ucfirst($tab)}}
                </x-tab>

            @endforeach

        </div>
    </div>

    <div
        class="container mx-auto flex flex-wrap flex-row justify-between"
        style="max-width: 900px;"
    >

        @foreach($series as $show)
            <div wire:click="redirectToShow({{$show['id']}})"
                 wire:key="{{$show->id}}"
            >
                <x-show-tile :show="$show" wire:key="{{$show->id}}" />
{{--                <livewire:show-tile :show="$show" />--}}
            </div>
        @endforeach
    </div>

    <x-fab
        @click="window.Modals.show('new-show-modal')"
    />
</div>
