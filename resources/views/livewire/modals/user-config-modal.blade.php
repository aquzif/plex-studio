<x-modal>
    <x-slot:title>Settings</x-slot:title>
    <div
        style="min-width: 300px;min-height: 400px;"
    >
        <div class="grid grid-cols-1 gap-4 p-4">
            <div class="col-span-1 flex flex-col gap-2">
                <x-select
                    label="Sort By"
                    name="sortBy"
                    model="sortBy"
                    :options="$sortByOptions"
                />
                <x-select
                    label="Sort Type"
                    name="sortType"
                    model="sortType"
                    :options="$sortTypeOptions"
                />
                <x-checkbox
                    name="showOnlyIncomplete"
                    label="Show only incomplete"
                    wire:click="updateInputsData()"
                    wire:model="showOnlyIncomplete"
                />
                <x-checkbox
                    name="showOnlyFavourites"
                    label="Show only favourites"
                    wire:click="updateInputsData()"
                    wire:model="showOnlyFavourites"
                />
                <x-checkbox
                    name="hideDownloadedEpisodes"
                    label="Hide downloaded episodes"
                    wire:click="updateInputsData()"
                    wire:model="hideDownloadedEpisodes"
                />
            </div>
{{--            <div class="col-span-1 flex flex-col gap-2">--}}

{{--            </div>--}}
    </div>
</x-modal>

