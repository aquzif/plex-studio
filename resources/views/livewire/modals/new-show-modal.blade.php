<x-modal
    showTitle="false"
    showButtons="false"
>
    <div
        style="max-width: 780px"
    >
        <div class="my-2 w-96 mx-auto">
            <x-input label="Nazwa filmu/serialu" wire:model.live.debounce.500ms="showName" name="showName" />
        </div>
        <div class="flex flex-wrap content-between" >

            @foreach($fetchedData as $show)
                <x-show-tile :show="$show" />
            @endforeach

        </div>

    </div>

</x-modal>
