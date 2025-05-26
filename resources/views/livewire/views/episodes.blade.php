<?php

use App\Models\Ledger;
use App\Models\Season;
use App\Models\Show;
use App\Traits\Modalable;
use App\Traits\Toastable;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new #[\Livewire\Attributes\Layout('layouts.dashboard')] class extends Component {

    use Toastable, Modalable;

    public $seriesId;
    public $seasonId;
    public $serie;
    public $season;
    public $episodes = [];

    public $qualities = [];
    public $audioLanguages = [];
    public $subtitleLanguages = [];


    public $qualityInput = '';
    public $audioInput = '[]';
    public $subtitleInput = '[]';
    public $notesInput = "";
    public bool $needsUpdate = false;

    public bool $anyEpisodeDownloaded = false;

    public function mount($seriesId, $seasonId)
    {

        $this->seriesId = $seriesId;
        $this->seasonId = $seasonId;



        $this->serie = Show::find($seriesId);
        if ($this->serie === null){
            $this->redirect(route('dashboard'));
            return;
        }
        $this->season = $this->serie->seasons()->where('id', $seasonId)->first();

        $this->notesInput = $this->season->notes;
        $this->refreshEpisodes();

        $this->anyEpisodeDownloaded = false;
        foreach($this->season->episodes()->get() as $episode){
            if($episode->downloaded){
                $this->anyEpisodeDownloaded = true;
                break;
            }
        }

        if ($this->season === null){
            $this->redirect(route('dashboard'));
            return;
        }

        $this->qualities = \App\Utils\Utils::getQualities();
        $this->audioLanguages = \App\Utils\Utils::getAudioLanguages();
        $this->subtitleLanguages = \App\Utils\Utils::getSubtitleLanguages();

        $this->qualityInput = $this->season->quality;
        if(!$this->season->audio_languages){
            $this->audioInput = '[]';
        }else{
            $this->audioInput = $this->season->audio_languages;
        }

        if(!$this->season->subtitle_languages){
            $this->subtitleInput = '[]';
        }else{
            $this->subtitleInput = $this->season->subtitle_languages;
        }

        $this->needsUpdate = $this->season->needs_update;

        $this->qualityInput = $this->season->quality;



    }

    #[On('refreshEpisodes')]
    public function refreshEpisodes()
    {
        $config = \App\Utils\ConfigUtils::getConfig();
        if($config['hideDownloadedEpisodes'])
            $this->episodes = $this->season->episodes()->where('downloaded', false)->get();
        else
            $this->episodes = $this->season->episodes()->get();
    }


    public function updatedQualityInput()
    {
        $this->season->update([
            'quality' => $this->qualityInput
        ]);
        $this->sendSuccessToast('Quality updated');
    }

    public function updatedAudioInput()
    {
        $this->season->update(['audio_languages' => $this->audioInput]);
        $this->sendSuccessToast('Audio updated');
    }

    public function updatedSubtitleInput()
    {
        $this->season->update(['subtitle_languages' => $this->subtitleInput]);
        $this->sendSuccessToast('Subtitles updated');
    }

    public function propagateQualityOverEpisodes() {
        foreach($this->episodes as $episode){
            $episode->update(['quality' => $this->qualityInput]);
        }
        $this->sendSuccessToast('Quality propagated');
    }

    public function toggleNeedsUpdate()
    {
        $this->season->update(['needs_update' => $this->needsUpdate]);
        $this->sendSuccessToast('Needs update updated');
    }

    public function openAddLinksModal() {
        $this->showModal('add-links-to-series-modal');
    }

    public function deleteUrl($urlId) {
        $url = \App\Models\Url::find($urlId);
        $url->delete();
    }

    public function toggleEpisodesDownloaded() {

        $this->anyEpisodeDownloaded = !$this->anyEpisodeDownloaded;
        foreach($this->episodes as $episode){
            $episode->update(['downloaded' => $this->anyEpisodeDownloaded]);
        }
        $this->sendSuccessToast('Episodes downloaded updated');

    }

    public function updatedNotesInput()
    {

        $this->season->update(['notes' => $this->notesInput]);
        $this->sendSuccessToast('Notes updated');
    }

}; ?>

<div >
    <livewire:modals.add-links-to-series-modal
        :serie-id="$seriesId"
    />
    <div class="container bg-gray-800 min-h-48 mx-auto mt-16 rounded-xl relative"
         style="width:800px;"
    >
        <h2
            class="text-4xl text-white p-4"
        >{{$serie->name}} - {{$season->name}}</h2>
        <x-icon-button class="absolute right-4 top-4"
                       wire:click="openAddLinksModal()"
        >
            <x-heroicon-m-plus />
        </x-icon-button>
        <x-icon-button class="absolute right-12 top-4"
                       wire:click="toggleEpisodesDownloaded()"
        >
            <x-heroicon-c-arrow-down-tray
                :class="$this->anyEpisodeDownloaded ? 'text-red-500' : 'text-green-500'"
            />
        </x-icon-button>
        <div class="grid grid-cols-5 gap-4 px-4">
            <div class="col-span-5">
                <x-textarea
                    name="notesInput"
                    label="Notes"
                    class="h-32"
                    wire:model.live.debounce.500ms="notesInput"
                >

                </x-textarea>
            </div>
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
                <x-button
                    class="mt-6"
                    wire:click="propagateQualityOverEpisodes()"
                >Propagate quality</x-button>
            </div>
            <div class="col-span-1">
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
    @foreach($this->episodes as $episode)
        <livewire:episode-card :episode="$episode" :key="$episode->id"  key="{{ rand(1,999999) }}" />

    @endforeach

</div>
