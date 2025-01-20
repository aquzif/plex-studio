<?php

use App\Models\Ledger;
use App\Traits\Modalable;
use App\Traits\Toastable;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new #[\Livewire\Attributes\Layout('layouts.dashboard')] class extends Component {

    public $page = 'download list';
    public $downloads = [];

    public function mount() {
        $this->loadData();
    }

    public function changePage($page) {
        $page = Str::replace(' ', '-',$page);
        $this->redirect('/'.$page);
    }

    public function loadData()
    {
        $this->downloads = \App\Utils\JDownloaderUtils::getPackagesInDownload();

        foreach ($this->downloads as &$item) {
            $url = \App\Models\Url::where('package_name', $item->name)->first();
            if(!$url){
                continue;
            }

            if($url->episode_id){
                $episode = \App\Models\Episode::find($url->episode_id);
                $item->name = $episode->show->name.' - '.$episode->episode_order_number.'. '.$episode->name;
            }else{

            }
        }
    }


}
?>

<div wire:poll="loadData" >

    <div class="flex justify-center items-center ">
        <div class="flex items-center">
            @foreach(['series','movie','accounts','download list'] as $tab)
                <x-tab
                    active="{{$page === $tab}}"
                    wire:key="{{$tab}}"
                    wire:click="changePage('{{$tab}}')"
                >
                    {{ucfirst($tab)}}
                </x-tab>

            @endforeach

        </div>
    </div>

    <div class="container bg-gray-800 min-h-48 mx-auto mt-16 rounded-xl relative"
         style="width:800px;"
    >

        <h2
            class="text-4xl text-white p-4"
        >jDownloader downloads list</h2>
        <div>
            <x-table  >
                <x-slot:columns>
                    <x-table-column>Name</x-table-column>
                    <x-table-column>State</x-table-column>
                    <x-table-column>Status</x-table-column>
                </x-slot:columns>
                <x-slot:rows>

                    @foreach($downloads as $download)
                        <x-table-row wire:key="{{$download->uuid}}" >
                            <x-table-cell class="text-wrap break-after-avoid" >
                                {{$download->name}}
                            </x-table-cell>
                            <x-table-cell>
                                <span class="block" >
                                    {{\App\Utils\UnitsUtils::bytesToHuman($download->bytesLoaded)}}
                                    /
                                    {{ \App\Utils\UnitsUtils::bytesToHuman($download->bytesTotal)  }}
                                    ({{round($download->bytesLoaded/$download->bytesTotal*100,2)}}%)
                                </span>

                                <x-progress
                                    :max="$download->bytesTotal"
                                    :value="$download->bytesLoaded"
                                    :error="isset($account->errorType)"
                                />
                            </x-table-cell>
                            <x-table-cell class="text-wrap" >
                               {{$download?->status ?? 'Oczekiwanie...'}}
                            </x-table-cell>

                        </x-table-row>

                    @endforeach

                </x-slot:rows>
            </x-table>
        </div>
    </div>

</div>
