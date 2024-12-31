<?php

use App\Models\Ledger;
use Livewire\Volt\Component;
use Livewire\Attributes\On;


new class extends Component {

    public $nav;

    public function mount() {
        $this->nav = [
            [
                'name' => 'Dashboard',
                'href' => '/',//route('home'),
                'active' => request()->routeIs('dashboard'),
                'icon' => 'heroicon-o-home',
            ]
        ];

    }



}; ?>

<div>
    <livewire:new-ledger-modal />
    <div class="w-80 h-full bg-gray-900 p-6 fixed hidden lg:block ">

        <div class="text-white text-xl font-bold mb-8">
            Bill Tracker
        </div>

        <nav class="flex flex-col justify-between" style="height: calc(100% - 100px)">
            <div>
                @foreach($nav as $item)
                    <x-nav-item icon="{{$item['icon']}}" name="{{$item['name']}}" :href="$item['href']"
                                 :active="$item['active']"/>
                @endforeach
            </div>
            @if(auth()->user()->isAdmin())
                <div>
                    <x-nav-item icon="heroicon-o-cog" name="Admin area" href="{{ route('settings.admin') }}"
                                :active="request()->routeIs('settings.admin')"/>
                </div>
            @endif
        </nav>
    </div>
    <div class="z-40 fixed w-full h-full" style="background-color: rgba(0,0,0,0.3)"
         x-show="navOpen"
         x-on:click="navOpen = false"
         x-transition.opacity
    >

    </div>
    <div class="w-80 h-full bg-gray-900 p-6 fixed lg:hidden transition z-50"
         style="transform: translateX(-100%);"
         x-bind:style="! navOpen ? 'transform: translateX(-100%);' : 'transform: translateX(0%);'"
    >
        <div class="text-white text-xl font-bold mb-8">
            Bill Tracker
        </div>
        <nav class="flex flex-col justify-between" style="height: calc(100% - 45px)" >
            <div>
                @foreach($nav as $item)
                    <x-nav-item icon="{{$item['icon']}}" name="{{$item['name']}}" :href="$item['href']"
                                :active="$item['active']" />
                @endforeach
            </div>
            @if(auth()->user()->isAdmin())
                <div>
                    <x-nav-item icon="heroicon-o-cog" name="Admin area" href="{{ route('settings.admin') }}"
                                :active="request()->routeIs('settings.admin')"/>
                </div>
            @endif

        </nav>
    </div>

</div>

