<x-modal
    showTitle="false"
    showButtons="false"
>
    <div
        style="max-width: 780px"
        x-data="{stage: @entangle('dialogStage').live}"
    >
        <div x-show="stage === 0" >
            <div class="my-2 w-96 mx-auto">
                <x-input
                    x-init="$watch('open', value => open && $nextTick(() => {
                        setTimeout(() => {
                            $refs.showNameInput.focus();
                       }, 50);
                   }));"

                    label="Nazwa filmu/serialu" wire:model.live.debounce.500ms="showName" name="showName"
                    x-ref="showNameInput"
                />
            </div>
            <div class="flex flex-wrap content-between" >

                @foreach($fetchedData as $show)
                    <x-new-show-tile :show="$show" />
                @endforeach

            </div>
        </div>
        <div x-show="stage === 1" >

        </div>
    </div>

</x-modal>

