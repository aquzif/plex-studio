<?php

use App\Models\Ledger;
use App\Traits\Modalable;
use App\Traits\Toastable;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new #[\Livewire\Attributes\Layout('layouts.dashboard')] class extends Component {

    use Toastable, Modalable;

    public $movieId;
    public $movie;

    public $qualities = [];
    public $audioLanguages = [];
    public $subtitleLanguages = [];


    public $qualityInput = '';
    public $audioInput = '[]';
    public $subtitleInput = '[]';
    public bool $favourite = false;
    public bool $needsUpdate = false;

    public function mount($movieId)
    {
        $this->movieId = $movieId;
        $this->movie = \App\Models\Show::find($movieId);

        if ($this->movie === null){
            $this->redirect(route('dashboard'));
            return;
        }

        if($this->movie->type === 'series'){
            $this->redirect(route('series', ['serieId' => $this->movie->id]));
        }

        $this->qualities = \App\Utils\Utils::getQualities();
        $this->audioLanguages = \App\Utils\Utils::getAudioLanguages();
        $this->subtitleLanguages = \App\Utils\Utils::getSubtitleLanguages();




        $this->qualityInput = $this->movie->quality;
        if(!$this->movie->audio_languages){
            $this->audioInput = '[]';
        }else{
            $this->audioInput = $this->movie->audio_languages;
        }


        if(!$this->movie->subtitle_languages){
            $this->subtitleInput = '[]';
        }else{
            $this->subtitleInput = $this->movie->subtitle_languages;
        }

        $this->favourite = $this->movie->favourite;
        $this->needsUpdate = $this->movie->needs_update;

//        dd($this->favourite,$this->needsUpdate,'',
//            $this->movie->favourite
//            ,$this->movie->needs_update
//            ,$this->movie->toJson()
//        );

        $this->qualityInput = $this->movie->quality;

    }



    //on quality change
    public function updatedQualityInput()
    {
        $this->movie->update([
            'quality' => $this->qualityInput
        ]);
        $this->sendSuccessToast('Quality updated');
    }

    public function updatedAudioInput()
    {
        $this->movie->update(['audio_languages' => $this->audioInput]);
        $this->sendSuccessToast('Audio updated');
    }

    public function updatedSubtitleInput()
    {
        $this->movie->update(['subtitle_languages' => $this->subtitleInput]);
        $this->sendSuccessToast('Subtitles updated');
    }

    public function toggleFavorite()
    {
        $this->movie->update(['favourite' => $this->favourite]);
        $this->sendSuccessToast('Favourite updated');
    }

    public function toggleNeedsUpdate()
    {
        $this->movie->update(['needs_update' => $this->needsUpdate]);
        $this->sendSuccessToast('Needs update updated');
    }

    public function toggleMovieDownloaded() {
        $this->movie->update(['downloaded' => !$this->movie->downloaded]);
        $this->sendSuccessToast('Downloaded updated');
    }

    public function openAddLinksModal() {
        $this->showModal('add-links-to-movie-modal');
    }

    public function toggleUrlDownloaded($urlId) {
        $url = \App\Models\Url::find($urlId);
        $url->update(['downloaded' => !$url->downloaded]);
    }

    public function toggleUrlInvalid($urlId){
        $url = \App\Models\Url::find($urlId);
        $url->update(['invalid' => !$url->invalid]);
    }

    public function deleteUrl($urlId) {
        $url = \App\Models\Url::find($urlId);
        $url->delete();
    }



}; ?>

<div >
    <livewire:modals.add-links-to-movie-modal
        :movieId="$movieId"
    />
    <div class="container bg-gray-800 min-h-48 mx-auto mt-16 rounded-xl relative"
        style="width:800px;"
    >
        <h2
            class="text-4xl text-white p-4"
        >{{$movie->name}}</h2>
        <x-icon-button class="absolute right-4 top-4"
            wire:click="openAddLinksModal()"
        >
            <x-heroicon-m-plus />
        </x-icon-button>
        <div class="grid grid-cols-5 gap-4 px-4">
            <div class="col-span-3">
                <x-select
                    name="audioInput"
                    label="Audio"
                    model="audioInput"
                    :options="$audioLanguages"
                    labelAsHTML="true"
                    multiple="true"
                />
            </div>
            <div class="col-span-2">
                <x-select
                    name="subtitleInput"
                    label="Subtitles"
                    model="subtitleInput"
                    :options="$subtitleLanguages"
                    labelAsHTML="true"
                    multiple="true"
                />
            </div>

        </div>

        <div class="grid grid-cols-3 gap-4 p-4">
            <div class="col-span-1">
                <x-select
                    name="qualityInput"
                    label="Quality"
                    model="qualityInput"
                    :options="$qualities"
                    labelAsHTML="true"
                />
            </div>
            <div class="col-span-1">

            </div>
            <div class="col-span-1">
                <x-checkbox
                    name="favourite"
                    label="Favorite"
                    wire:click="toggleFavorite()"
                    wire:model="favourite"
                />
                <x-checkbox
                    class="my-2"
                    name="needsUpdate"
                    label="Need update"
                    wire:click="toggleNeedsUpdate()"
                    wire:model="needsUpdate"
                />
            </div>

        </div>


    </div>
    <div class="container bg-gray-800 min-h-48 mx-auto mt-4 rounded-xl relative"
         style="width:800px;"
    >
        <x-icon-button class="absolute right-4 top-4"
            wire:click="toggleMovieDownloaded()"
        >
            <x-heroicon-c-arrow-down-tray :class="$movie->downloaded?'text-red-500':'text-green-500'"  />
        </x-icon-button>
        <h2
            class="
                text-2xl p-4
                {{($movie->downloaded?'text-green-500':'text-white')}}
            "
        >Links</h2>
        <x-table dense="true" >
            <x-slot:columns>
                <x-table-column class="px-0 w-0" ></x-table-column>
                <x-table-column>Link</x-table-column>
                <x-table-column>Quality</x-table-column>
                <x-table-column>Tools</x-table-column>
            </x-slot:columns>
            <x-slot:rows>
                @foreach($movie->urls()->get() as $url)
                    <x-table-row>
                        <x-table-cell  >
                            @if($url->auto_valid)
                                <div

                                >
                                    <x-heroicon-o-check-circle class="text-green-500 w-8 h-8 mx-auto"  />
                                </div>
                            @else
                                <div
                                    {{ Popper::interactive()->arrow('round')->placement('bottom')
                                        ->pop('Sprawdzono: <br/>'.
                                            Carbon\Carbon::parse($url->last_validated_date)->format('Y-m-d H:i')
                                        ) }}
                                >
                                    <x-heroicon-o-x-circle class="text-red-500 w-8 h-8 mx-auto"  />
                                </div>

                            @endif
                        </x-table-cell>
                        <x-table-cell>
                            <a href="{{$url->url}}"
                               class="underline text-wrap break-all
                                {{$url->downloaded ? 'text-green-500' : ($url->invalid? 'text-red-500':'text-white-500')}}
                               " target="_blank"
                            >{{$url->url}}</a>
                        </x-table-cell>
                        <x-table-cell class="text-center" >
                            <x-quality-badge :quality="$url->quality" />
                        </x-table-cell>
                        <x-table-cell class="flex flex-row" >
                            <x-icon-button
                                wire:click="toggleUrlInvalid({{$url->id}})"
                            >
                                <x-heroicon-o-exclamation-circle class="w-6 h-6 text-yellow-400 " />
                            </x-icon-button>
                            <x-icon-button
                                wire:click="toggleUrlDownloaded({{$url->id}})"
                            >
                                <x-heroicon-c-arrow-down-tray :class="'w-6 h-6 '.($url->downloaded ? 'text-red-500':'text-green-500')" />
                            </x-icon-button>
                            <x-icon-button
                                wire:click="deleteUrl({{$url->id}})"
                            >
                                <x-heroicon-c-trash class="w-6 h-6 text-red-500" />
                            </x-icon-button>
                        </x-table-cell>
                    </x-table-row>
                @endforeach
            </x-slot:rows>
        </x-table>
    </div>
</div>
