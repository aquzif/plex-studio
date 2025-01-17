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

        $this->sortBy = $config['sort_by'];
        $this->sortType = $config['sort_type'];
        $this->showOnlyIncomplete = $config['show_only_incomplete'];
        $this->showOnlyFavourites = $config['show_only_favourites'];
        $this->hideDownloadedEpisodes = $config['hide_downloaded_episodes'];
    }

    public function updatedSortBy()
    {

        ConfigUtils::setConfigValue('sort_by', $this->sortBy);
        $this->dispatch('refreshShows');
    }

    public function updatedSortType()
    {
        ConfigUtils::setConfigValue('sort_type', $this->sortType);
        $this->dispatch('refreshShows');
    }

    public function updateInputsData() {
        ConfigUtils::setConfigValue('show_only_incomplete', $this->showOnlyIncomplete);
        ConfigUtils::setConfigValue('show_only_favourites', $this->showOnlyFavourites);
        ConfigUtils::setConfigValue('hide_downloaded_episodes', $this->hideDownloadedEpisodes);
        $this->dispatch('refreshShows');
        $this->dispatch('refreshEpisodes');
    }

    public function render()
    {
        return view('livewire.modals.user-config-modal');
    }


}
