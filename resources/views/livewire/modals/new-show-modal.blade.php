<x-modal>
    @if($dialogStage === 1 && $selectedSerialisation !== null)
        <x-slot:buttons>
            <x-button
                @click="window.asyncEventWithLoader('finishAddingSerial')"
            >Zakończ</x-button>
        </x-slot:buttons>

    @endif
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
        <div x-show="stage === 1"
            style="min-width: 600px;min-height: 300px"
        >
            <x-card-title class="mb-4" >Wybierz serialzację</x-card-title>
            <div class="flex flex-row justify-center mb-4" >
                @foreach($serialisations as $serialisation)
                    <x-tab
                        wire:click="changeSelectedSerialisation('{{$serialisation['type']}}')"
                        active="{{$selectedSerialisation['type'] === $serialisation['type']}}"
                    >{{$serialisation['name']}}</x-tab>
                @endforeach
            </div>

            <x-table>
                <x-slot:columns>
                    <x-table-column>Sezon</x-table-column>
                    <x-table-column>Odcinki</x-table-column>
                </x-slot:columns>
                <x-slot:rows>
                    @if($selectedSerialisation !== null)
                        @foreach( $selectedSerialisation['seasons'] as $season => $episodes)
                            <x-table-row>
                                <x-table-cell>{{$season}}</x-table-cell>
                                <x-table-cell>{{$episodes}}</x-table-cell>
                            </x-table-row>

                        @endforeach
                    @endif
                </x-slot:rows>
            </x-table>
        </div>
    </div>

</x-modal>

