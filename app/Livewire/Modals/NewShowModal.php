<?php

namespace App\Livewire\Modals;

use App\Livewire\ModalComponent;
use App\Models\Show;
use App\Traits\Loadable;
use App\Traits\Toastable;
use App\Utils\TvDBUtils;
use Livewire\Attributes\On;

class NewShowModal extends ModalComponent {

    use Toastable, Loadable;

    public $name = 'new-show-modal';
    public $fetchedData = [];
    public $dialogStage = 0;
    public $showName;
    public $serialisations = [];
    public $selectedSerialisation = null;
    public $selectedShowId = 0;

    public function mount()
    {

    }

    public function resetComponent()
    {
        $this->showName = '';
        $this->fetchedData = [];
        $this->dialogStage = 0;
        $this->serialisations = [];
        $this->selectedSerialisation = null;
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
        try{
            $clickedShow = null;
            $this->selectedShowId = $showId;
            foreach($this->fetchedData as $show){
                if($show['tvdb_id'] == $showId){
                    $clickedShow = $show;
                    break;
                }
            }
            if(!$clickedShow){
                $this->sendErrorToast('Show not found');
                $this->hideLoader();
                return;
            }


            $showInDatabase = Show::where('tvdb_id',$clickedShow['tvdb_id'])->first();
            if($showInDatabase){
                $this->sendErrorToast('Show already exists in database');
                $this->hideLoader();
                return;
            }
            if($clickedShow['type'] === 'series'){
                $this->dialogStage = 1;
                $this->serialisations = Show::getSeasons($clickedShow['tvdb_id']);
                $this->selectedSerialisation = $this->serialisations[0];
            }else{
                Show::createNewShow('movie',$clickedShow['tvdb_id']);
                $this->sendSuccessToast('Movie added successfully');
                $this->open = false;
                $this->dispatch('refreshShows');
            }
        }catch (\Exception $e){
            $this->sendErrorToast($e->getMessage());
        }
        $this->hideLoader();
    }

    #[On('finishAddingSerial')]
    public function finishAddingSerial() {
//        try{
            Show::createNewShow(
                'series',
                $this->selectedShowId,
                $this->selectedSerialisation['type']
            );
            $this->open = false;
            $this->dispatch('refreshShows');
            $this->sendSuccessToast('Show added successfully');
//        }catch (\Exception $e){
//            $this->sendErrorToast('Error adding show');
//        }
        $this->hideLoader();


    }

    public function changeSelectedSerialisation($type){
        foreach ($this->serialisations as $serialisation) {
            if($serialisation['type'] === $type){
                $this->selectedSerialisation = $serialisation;
                break;
            }
        }
    }

    public function render()
    {
        return view('livewire.modals.new-show-modal');
    }
}
