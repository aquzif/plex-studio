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

            $status = $show->getStatus();
            $this->color = $status['color'];
            $this->subValue = $status['value'];
        }else{
            $this->src = $season['thumb_path'] ?? 'default';
            $this->title = $season['name'];



            $episodes = $season->howManyReleasedEpisodes();
            $downloaded = $season->howManyDownloadedReleasedEpisodes();

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
