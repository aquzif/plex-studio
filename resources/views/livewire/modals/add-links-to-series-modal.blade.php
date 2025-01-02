<x-modal>
    <x-slot:title>Add links</x-slot:title>

    @if($finished)
        Loading finished:
        <p>Amount of links: {{$allLinksNumber}}</p>
        <p>Links valid: {{$addedLinksNumber}}</p>
        <p>Invalid links: {{$invalidLinksNumber}}</p>
        Invalid links are in text area below. You can edit them and try to add again.
    @endif

    <x-textarea
        name="linksInput"
        wire:model="linksInput"
        x-ref="addLinksToMovieInput"
        x-init="$watch('open', value => open && $nextTick(() => {
            setTimeout(() => {
                $refs.addLinksToMovieInput.focus();
           }, 50);
       }));"
    />

    <x-slot:buttons>
        <x-button
            wire:click="addLinksToMovie()"
        >
            Save
        </x-button>
        <x-button
            color="red"
            wire:click="closeModal()"
        >
            Cancel
        </x-button>
    </x-slot:buttons>
</x-modal>

