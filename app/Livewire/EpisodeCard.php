<?php

namespace App\Livewire;

use App\Enums\UrlStatus;
use App\Models\Episode;
use App\Traits\Toastable;
use App\Utils\Utils;
use Livewire\Component;

class EpisodeCard extends Component
{
    use Toastable;

    public $qualities = [];
    public $qualityInput = '';

    public $episode;

    public function mount() {

        $this->qualities = Utils::getQualities();
        $this->qualityInput = $this->episode->quality;



    }

    public function updatedQualityInput() {
        $this->episode->quality = $this->qualityInput;
        $this->episode->save();
        $this->sendSuccessToast('Quality updated');
    }

    public function refreshEpisode() {
        $this->episode = Episode::find($this->episode->id);
    }

    public function toggleEpisodeDownloaded($episodeId) {
        $episode = \App\Models\Episode::find($episodeId);
        $episode->update(['downloaded' => !$episode->downloaded]);
        $this->refreshEpisode();
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

    public function startAutoDownload($urlId) {
        $url = \App\Models\Url::find($urlId);

        if(!$url->episode()->first()
            ->show()->first()->directory_name){
            $this->sendErrorToast('Show directory name is not set');
            return;
        }

        $url->update([
            'auto_download' => true,
            'status' => UrlStatus::WAITING_FOR_START_DOWNLOAD
        ]);
    }

    public function render()
    {
        return view('livewire.episode-card');
    }

}
