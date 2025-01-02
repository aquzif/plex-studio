<div class="container bg-gray-800 min-h-48 mx-auto mt-4 rounded-xl relative"
     style="width:800px;"
>
    <div
        class="flex flex-row justify-between items-center p-4"
    >
        <h2
            class="
                text-xl
                {{($episode->downloaded?'text-green-500':'text-white')}}
                "
        >
            Odcinek {{$episode->episode_order_number}} - {{$episode->name}}
        </h2>
        <div class="flex flex-row items-center gap-2" >
            <x-select
                name="qualityInput"
                model="qualityInput"
                :options="$qualities"
                labelAsHTML="true"
            />
            <x-icon-button
                wire:click="toggleEpisodeDownloaded({{$episode->id}})"
            >
                <x-heroicon-c-arrow-down-tray :class="'w-6 h-6 '.($episode->downloaded ? 'text-red-500':'text-green-500')" />
            </x-icon-button>
        </div>
    </div>
    <x-table dense="true" >
        <x-slot:columns>
            <x-table-column class="px-0 w-0" ></x-table-column>
            <x-table-column>Link</x-table-column>
            <x-table-column>Quality</x-table-column>
            <x-table-column>Tools</x-table-column>
        </x-slot:columns>
        <x-slot:rows>
            @foreach($episode->urls()->get() as $url)
                <x-table-row>
                    <x-table-cell  >
                        @if($url->auto_valid)
                            <div

                            >
                                <x-heroicon-o-check-circle class="text-green-500 w-8 h-8 mx-auto"  />
                            </div>
                        @else
                            <div
                                {{ Popper::interactive()->arrow('round')->placement('bottom')
                                    ->pop('Sprawdzono: <br/>'.
                                        Carbon\Carbon::parse($url->last_validated_date)->format('Y-m-d H:i')
                                    ) }}
                            >
                                <x-heroicon-o-x-circle class="text-red-500 w-8 h-8 mx-auto"  />
                            </div>

                        @endif
                    </x-table-cell>
                    <x-table-cell>
                        <a href="{{$url->url}}"
                           class="underline text-wrap break-all
                                {{$url->downloaded ? 'text-green-500' : ($url->invalid? 'text-red-500':'text-white-500')}}
                               " target="_blank"
                        >{{$url->url}}</a>
                    </x-table-cell>
                    <x-table-cell class="text-center" >
                        <x-quality-badge :quality="$url->quality" />
                    </x-table-cell>
                    <x-table-cell class="flex flex-row" >
                        <x-icon-button
                            wire:click="toggleUrlInvalid({{$url->id}})"
                        >
                            <x-heroicon-o-exclamation-circle class="w-6 h-6 text-yellow-400 " />
                        </x-icon-button>
                        <x-icon-button
                            wire:click="toggleUrlDownloaded({{$url->id}})"
                        >
                            <x-heroicon-c-arrow-down-tray :class="'w-6 h-6 '.($url->downloaded ? 'text-red-500':'text-green-500')" />
                        </x-icon-button>
                        <x-icon-button
                            wire:click="deleteUrl({{$url->id}})"
                        >
                            <x-heroicon-c-trash class="w-6 h-6 text-red-500" />
                        </x-icon-button>
                    </x-table-cell>
                </x-table-row>
            @endforeach
        </x-slot:rows>
    </x-table>
</div>
