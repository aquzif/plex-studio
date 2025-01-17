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


    public function mount($page = 'default')
    {

        if(($page !== 'series' && $page !== 'movie')){
            $this->redirect('/series');
            return;
        }

        $this->page = $page;

        $this->refreshShows();
    }

    #[On('refreshShows')]
    public function refreshShows()
    {
        $config = \App\Utils\ConfigUtils::getConfig();

        $conditions = [
            [
                'type', match ($this->page){
                    'series' => 'series',
                    'movie' => 'movie',
                }
                ],
        ];

        if($config['show_only_incomplete']){
            $conditions[] = ['downloaded', false];
        }

        if($config['show_only_favourites']){
            $conditions[] = ['favourite', true];
        }

        $series = \App\Models\Show::
        where($conditions)->orderBy(
            $config['sort_by'],
            $config['sort_type'],
        )->get();

        $seriesNew = [];

        foreach ($series as $serie) {
            $status = $serie->getStatus();
            if($config['show_only_incomplete'] && $status['fullyDownloaded']){
                $serie->downloaded = true;
                $serie->save();
                continue;
            }else if(!$status['fullyDownloaded']){
                $serie->downloaded = false;
                $serie->save();
            }
            $seriesNew[] = $serie;
        }

        $this->series = $seriesNew;

    }

    public function openNewShowModal()
    {
        $this->showModal('new-show-modal');
    }

    public function changePage($page) {
        $page = Str::replace(' ', '-',$page);
        $this->redirect('/'.$page);
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

<div>
    <livewire:modals.new-show-modal/>

    <div class="flex justify-center items-center">
        <div class="flex items-center">
            @foreach(['series','movie','accounts','download list'] as $tab)
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
            <div
                 @click="$wire.redirectToShow({{$show['id']}})"
                 wire:key="{{$show->id}}"
                 x-show="searchValue === '' || '{{$show->name}}'.toLowerCase().includes(searchValue.toLowerCase())"
            >
{{--                <x-show-tile :show="$show" wire:key="{{$show->id}}" />--}}
                <livewire:show-tile :show="$show" :key="$show->id" />
            </div>
        @endforeach
    </div>

    <x-fab
        @click="window.Modals.show('new-show-modal')"
    />
</div>
