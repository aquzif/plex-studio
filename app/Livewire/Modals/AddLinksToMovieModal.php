<?php

namespace App\Livewire\Modals;

use App\Livewire\ModalComponent;
use App\Models\Show;
use App\Traits\Loadable;
use App\Traits\Toastable;
use App\Utils\TvDBUtils;
use Livewire\Attributes\On;

class AddLinksToMovieModal extends ModalComponent {

    use Toastable, Loadable;

    public $name = 'add-links-to-movie-modal';
    public $linksInput = '';
    public $allLinksNumber = 0;
    public $addedLinksNumber = 0;
    public $invalidLinksNumber = 0;
    public $duplicatedLinksNumber = 0;
    public $movieId;
    public $movie;
    public $finished = false;

    public function mount()
    {
        $this->movie = Show::findOrFail($this->movieId);
    }

    public function resetComponent(): void
    {
        $this->linksInput = '';
    }

    public function closeModal()
    {
        $this->linksInput = '';
        $this->open = false;
    }

    public function addLinksToMovie() {

        $this->invalidLinksNumber = 0;
        $this->addedLinksNumber = 0;
        $this->duplicatedLinksNumber = 0;
        $this->finished = false;


        //split links by new line
        $links = explode("\n", $this->linksInput);
        $invalidLinks = [];
        if(count($links) <= 1 && $links[0] === ''){
            $this->sendErrorToast('No links provided');
            return;
        }

        $this->allLinksNumber = count($links);

        foreach($links as $link){
            $link = trim($link);

            if($link === ''){
                $this->allLinksNumber--;
                continue;
            }
            //check if link is valid url
            if(!filter_var($link, FILTER_VALIDATE_URL)){
                $invalidLinks[] = $link;
                continue;
            }

            //check if link already exists
            if($this->movie->urls()->where('url', $link)->exists()){
                $this->duplicatedLinksNumber++;
                continue;
            }

            $this->movie->urls()->create([
                'url' => $link
            ]);
            $this->addedLinksNumber++;


        }
        $this->finished = true;
        $this->invalidLinksNumber = count($invalidLinks);
        $this->linksInput = implode("\n", $invalidLinks);
    }



    public function render()
    {
        return view('livewire.modals.add-links-to-movie-modal');
    }
}
