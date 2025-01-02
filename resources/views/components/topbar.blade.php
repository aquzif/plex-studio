<div class="h-16 w-full border-b-2 border-b-gray-200 flex flex-row justify-between bg-white
    dark:bg-neutral-800 dark:border-b-neutral-700
">
    <livewire:modals.user-config-modal />
    <div class="m-4 text-xl ml-6 flex flex-row"  >
{{--        <div class="lg:hidden md:block" >--}}
{{--            <x-heroicon-o-bars-3 class="w-8 h-8 mr-4" @click="navOpen = true" />--}}
{{--        </div>--}}
        <div class=" dark:text-neutral-300 flex flex-row cursor-pointer "
            x-on:click="window.location.href = '/'"
        >
            <img src="{{asset('/favicon.svg')}}" class="w-8 h-8" />
            <span class="dark:text-white text-gray-800 block ml-2 mr-6"
                style="
                        font-size: 1.25rem;
                        line-height: 1.6;
                        white-space: nowrap;
                        font-family: monospace;
                        font-weight: 700;
                        letter-spacing: 0.1rem;
                "
            >Plex Studio
            </span>
        </div>
        <x-heroicon-o-arrow-up-circle
            x-show="
                window.location.pathname.includes('/series/')
                || window.location.pathname.includes('/movie/')
            "
            class="ml-2 w-8 h-8 text-white cursor-pointer"
            x-on:click="window.Navigator.redirectToUp()"
        />
    </div>
    <div class="p-2 container max-w-lg" >
        <x-input
            name="searchValue"
            placeholder="Search"
            class="w-full"
            x-model.debounce="searchValue"
        />
    </div>
    <div class="flex flex-row items-center content-center" >
        <x-theme-switcher />
        <x-icon-button class="mr-2"
            x-on:click="Livewire.dispatch('openModal',{name: 'user-config-modal'})"
        >
            <x-heroicon-s-cog-6-tooth />
        </x-icon-button>
        <div class="flex flex-row " >
            <x-dropdown>
                <x-slot:ignite>
                    <div class="flex flex-row mr-6 cursor-pointer" x-on:click="show = !show" >
                        <div class="w-8 h-8 my-4 mx-2 bg-gray-300 rounded-full">
                            <img src="{{\App\Utils\AvatarUtils::getAvatarPath(auth()->user())}}" class="w-8 h-8 rounded-full">
                        </div>
                        <p class="font-semibold text-sm py-5 dark:text-neutral-300">
                            {{auth()->user()->name}}
                        </p>
                        <x-ts-icon name="chevron-down" class="w-5 h-5 ml-1 my-5 text-gray-400" />
                    </div>
                </x-slot:ignite>
                <x-dropdown-item @click="window.location.href = '/profile'" >Profile</x-dropdown-item>
                @if(auth()->user()->isAdmin())
                    <x-dropdown-item @click="window.location.href = '/settings'" >Site settings</x-dropdown-item>
                @endif
                <x-dropdown-item @click="window.location.href = '/logout'" >Logout</x-dropdown-item>
            </x-dropdown>
        </div>
    </div>




</div>
