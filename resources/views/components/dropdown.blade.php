<div x-data="{open: false}" class="relative"  >

    <div
        @click="open = !open"
        @click.away="open = false"
    >

        @if(isset($ignite))
            {{$ignite}}
        @else
            <div class="bg-indigo-500 px-4 py-2 text-white rounded-md">
                {{$title ?? 'Dropdown'}}
            </div>
        @endif
    </div>
    <div
        x-show="open"
        class="absolute bg-white rounded-md shadow-lg w-full my-2 border-gray-200 border py-1  dark:bg-neutral-700 dark:border-neutral-800"
        style="z-index: 9999"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        {{$slot}}
    </div>
</div>
