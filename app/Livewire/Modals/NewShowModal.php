<?php

namespace App\Livewire\Modals;

use App\Livewire\ModalComponent;
use App\Models\Show;
use App\Traits\Toastable;
use App\Utils\TvDBUtils;
use Livewire\Attributes\On;

class NewShowModal extends ModalComponent {

    use Toastable;

    public $name = 'new-show-modal';

    public $fetchedData = [];
    public $dialogStage = 0;

    public $showName;

    public function mount()
    {

    }

    public function updatedOpen()
    {
        if(!$this->open) {
            $this->showName = '';
            $this->fetchedData = [];
        }
    }



    public function updatedShowName()
    {
        if($this->showName)
            $this->fetchedData = TvDBUtils::search($this->showName);
        else
            $this->fetchedData = [];
    }

    #[On('newShowSelect')]
    public function onShowSelect($showId){
        $clickedShow = null;
        foreach($this->fetchedData as $show){
            if($show['tvdb_id'] == $showId){
                $clickedShow = $show;
                break;
            }
        }
        if(!$clickedShow)
            return;

        $showInDatabase = Show::where('tvdb_id',$clickedShow['tvdb_id'])->first();
        if($showInDatabase){
            $this->sendErrorToast('Show already exists in database');
            return;
        }
        if($clickedShow['type'] === 'series'){
            $this->dialogStage = 1;
            return;
        }else{
            Show::createNewShow('movie',$clickedShow['tvdb_id']);
            $this->sendSuccessToast('Movie added successfully');
            $this->open = false;
            return;
        }


    }

    public function render()
    {
        return view('livewire.modals.new-show-modal');
    }
}
