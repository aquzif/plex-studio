<?php

use App\Models\Ledger;
use App\Traits\Modalable;
use App\Traits\Toastable;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new #[\Livewire\Attributes\Layout('layouts.dashboard')] class extends Component {

    public $page = 'accounts';
    public $accounts = [];

    public function mount() {
        $this->loadData();
    }



    public function changePage($page) {
        $page = Str::replace(' ', '-',$page);
        $this->redirect('/'.$page);
    }

    public function loadData()
    {
        $this->accounts = \App\Utils\JDownloaderUtils::getHosters();
    }



}
?>

<div wire:poll="loadData" >

    <div class="flex justify-center items-center">
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
        >jDownloader linked accounts</h2>
        <div>
            <x-table >
                <x-slot:columns>
                    <x-table-column>Host</x-table-column>
                    <x-table-column>Username</x-table-column>
                    <x-table-column>Traffic</x-table-column>
                    <x-table-column>Status</x-table-column>
                </x-slot:columns>
                <x-slot:rows>

                    @foreach($accounts as $account)
                        <x-table-row wire:key="{{$account->hostname}}-{{$account->username}}" >
                            <x-table-cell>
                                {{$account->hostname}}
                            </x-table-cell>
                            <x-table-cell>
                                {{$account->username}}
                            </x-table-cell>
                            <x-table-cell>
                                <span class="block" >
                                    {{\App\Utils\UnitsUtils::bytesToHuman($account->trafficLeft)}}
                                    /
                                    {{ \App\Utils\UnitsUtils::bytesToHuman($account->trafficMax)  }}
                                </span>
                                <livewire:progressbar
                                    wire:key="{{$account->hostname}}-{{$account->username}}"
                                    max="{{$account->trafficMax}}"
                                    value="{{$account->trafficLeft}}"
                                    error="{{isset($account->errorType)}}"
                                />
                            </x-table-cell>
                            <x-table-cell>
                                @if(isset($account->errorType))
                                    <span class="text-red-500">{{$account->errorType}}</span>
                                @else
                                    <span class="text-green-500">OK</span>
                                @endif
                            </x-table-cell>

                        </x-table-row>

                    @endforeach

                </x-slot:rows>
            </x-table>
        </div>
    </div>


</div>
