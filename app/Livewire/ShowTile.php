<?php

namespace App\Livewire;

use App\Traits\Toastable;
use Livewire\Component;

class ShowTile extends Component
{

    use Toastable;

    public $show;
    public $season;
    public $color = 'white';
    public $src = '';
    public $title = '';
    public $subValue = '';
    public $favourite = false;


    public function mount($show = null,$season = null) {

        $red = 'red-600';
        $green = 'green-600';
        $yellow = 'yellow-500';


        if(isset($show)){
            $this->src = $show['thumb_path'] ?? asset('storage/default_serie.jpg');
            $this->title = $show['name'];
            $this->type = $show['type'];
            $this->favourite = $show['favourite'] ?? false;

            $this->downloaded = $show['downloaded'] ?? false;
            $this->favorite = $show['favorite'] ?? false;

            if($this->type === 'movie') {
                $urlsCount = $show->urls->count();
                $downloadedUrlsCount = $show->urls->where('downloaded', true)->count();
                if ($this->downloaded ||
                    ($urlsCount === $downloadedUrlsCount && $urlsCount > 0)
                ) {
                    $this->color = $green;
                    $this->subValue = '100%';
                } else {


                    if($urlsCount === 0 || $downloadedUrlsCount === 0) {
                        $this->color = $red;
                        $this->subValue = '0%';
                    }else{
                        $this->color = $yellow;
                        $this->subValue = round(($downloadedUrlsCount / $urlsCount) * 100) . '%';
                    }
                }
            }else{
                $episodes = $show->howManyEpisodes();
                $downloaded = $show->howManyDownloadedEpisodes();



                if($episodes === 0 || $downloaded === 0) {
                    $this->color = $red;
                    $this->subValue = '0%';
                }else if ($episodes === $downloaded){
                    $this->color = $green;
                    $this->subValue = '100%';
                }else{
                    $this->color = $yellow;
                    $this->subValue = round(($downloaded / $episodes) * 100) . '%';
                }

            }
        }else{
            $this->src = $season['thumb_path'] ?? 'default';
            $this->title = $season['name'];



            $episodes = $season->howManyEpisodes();
            $downloaded = $season->howManyDownloadedEpisodes();

            if($episodes === 0 || $downloaded === 0) {
                $this->color = $red;
                $this->subValue = '0%';
            }else if ($episodes === $downloaded){
                $this->color = $green;
                $this->subValue = '100%';
            }else{
                $this->color = $yellow;
                $this->subValue = round(($downloaded / $episodes) * 100) . '%';
            }
        }


    }

    public function toggleFavourite() {
        $this->favourite = !$this->favourite;
        $this->show->favourite = $this->favourite;
        $this->show->save();
        $this->sendSuccessToast(($this->favourite ? 'added to' : 'removed from').'  favorites');
    }

    public function deleteShow() {
        $this->show->delete();
        $this->sendSuccessToast('Show deleted');
        $this->dispatch('refreshShows');
    }

    public function render()
    {
        return view('livewire.show-tile');
    }
}
