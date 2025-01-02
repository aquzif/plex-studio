<?php

namespace App\Livewire\Modals;

use App\Livewire\ModalComponent;
use App\Models\Show;
use App\Traits\Loadable;
use App\Traits\Toastable;
use App\Utils\ConfigUtils;
use App\Utils\TvDBUtils;
use Livewire\Attributes\On;

class UserConfigModal extends ModalComponent {

    use Toastable, Loadable;

    public $name = 'user-config-modal';

    public $sortBy = '';
    public $sortType = '';
    public $showOnlyIncomplete = false;
    public $showOnlyFavourites = false;
    public $hideDownloadedEpisodes = false;

    public $sortByOptions = [
      [
        'value' => 'name',
        'label' => 'Name'
      ]
    ];

    public $sortTypeOptions = [
      [
        'value' => 'asc',
        'label' => 'Ascending'
      ],
      [
        'value' => 'desc',
        'label' => 'Descending'
      ]
    ];


    public function mount()
    {
        $this->resetComponent();
    }

    public function resetComponent(): void
    {
        $config = ConfigUtils::getConfig();

        $this->sortBy = $config['sortBy'];
        $this->sortType = $config['sortType'];
        $this->showOnlyIncomplete = $config['showOnlyIncomplete'];
        $this->showOnlyFavourites = $config['showOnlyFavourites'];
        $this->hideDownloadedEpisodes = $config['hideDownloadedEpisodes'];
    }

    public function updatedSortBy()
    {

        ConfigUtils::setConfigValue('sortBy', $this->sortBy);
        $this->dispatch('refreshShows');
    }

    public function updatedSortType()
    {
        ConfigUtils::setConfigValue('sortType', $this->sortType);
        $this->dispatch('refreshShows');
    }

    public function updateInputsData() {
        ConfigUtils::setConfigValue('showOnlyIncomplete', $this->showOnlyIncomplete);
        ConfigUtils::setConfigValue('showOnlyFavourites', $this->showOnlyFavourites);
        ConfigUtils::setConfigValue('hideDownloadedEpisodes', $this->hideDownloadedEpisodes);
        $this->dispatch('refreshShows');
        $this->dispatch('refreshEpisodes');
    }

    public function render()
    {
        return view('livewire.modals.user-config-modal');
    }


}
