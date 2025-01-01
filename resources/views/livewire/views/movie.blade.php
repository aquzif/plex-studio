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

    public function mount($movieId)
    {
        $this->movieId = $movieId;
        $this->movie = \App\Models\Show::find($movieId);
        $this->getQualities();
        $this->getAudioLanguages();
        $this->getSubtitleLanguages();



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



        if ($this->movie === null){
            $this->redirect(route('dashboard'));
            return;
        }

        $this->quantityInput = $this->movie->quality;

        if($this->movie->type === 'series'){
            $this->redirect(route('series', ['serieId' => $this->movie->id]));
        }

    }

    private function getQualities() {
        $this->qualities = [
            [
                'value' => 'undef',
                'label' => '<p style="color: '.config('plex.qualityColors.unknown').'" >Undef</p>',
            ],
            [
                'value' => '480p',
                'label' => '<p style="color: '.config('plex.qualityColors.bad').'" >480p</p>',
            ],
            [
                'value' => '720p',
                'label' => '<p style="color: '.config('plex.qualityColors.medium').'" >720p</p>',
            ],
            [
                'value' => '1080p',
                'label' => '<p style="color: '.config('plex.qualityColors.good').'" >1080p</p>',
            ],
            [
                'value' => '2160p',
                'label' => '<p style="color: '.config('plex.qualityColors.best').'" >2160p</p>',
            ]
        ];
    }

    private function getAudioLanguages() {
        $this->audioLanguages = [
            [
                'value' => 'Dubbing',
                'label' => '<p style="color: '.config('plex.qualityColors.best').'" >Dubbing</p>',
            ],
            [
                'value' => 'Lektor',
                'label' => '<p style="color: '.config('plex.qualityColors.best').'" >Lektor</p>',
            ],
            [
                'value' => 'Angielski',
                'label' => '<p style="color: '.config('plex.qualityColors.good').'" >Angielski</p>',
            ],

        ];
    }

    private function getSubtitleLanguages() {
        $this->subtitleLanguages = [
            [
                'value' => 'Polski',
                'label' => '<p style="color: '.config('plex.qualityColors.best').'" >Polski</p>',
            ],
            [
                'value' => 'Angielski',
                'label' => '<p style="color: '.config('plex.qualityColors.good').'" >Angielski</p>',
            ],

        ];
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


}; ?>

<div >
    <div class="container bg-gray-800 min-h-48 mx-auto mt-16 rounded-xl relative"
        style="width:800px;"
    >
        <h2
            class="text-4xl text-white p-4"
        >{{$movie->name}}</h2>
        <x-icon-button class="absolute right-4 top-4" >
            <x-heroicon-m-plus />
        </x-icon-button>

        <div class="grid grid-cols-5 gap-4 p-4">
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

        </div>


    </div>
</div>
