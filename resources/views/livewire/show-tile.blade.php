<div

    style="
        margin: 20px;
        width: 136px;
        position: relative;
        height: 200px;
    "
    {{ Popper::interactive()->arrow('round')->placement('bottom')->pop($title) }}
    x-data="{showButtons: false}"
    x-on:mouseenter="showButtons = true"
    x-on:mouseleave="showButtons = false"
>


    <div class="border-{{$color}} borderWithInfo" ></div>
    <div class="border-{{$color}} borderWithInfoRadius" ></div>
    <div class="borderInfo bg-{{$color}} " >{{$subValue}}</div>
    @if(!$season)
        <x-icon-button
            x-show="showButtons || {{$favourite ? 'true' : 'false'}}"
            class="absolute top-0 left-0 "
        >
            @if($favourite)
                <x-heroicon-c-star
                    @click.stop="$wire.toggleFavourite({{$show->id}})"
                    class="w-6 h-6" style="color: yellow;" />
            @else
                <x-heroicon-o-star
                    @click.stop="$wire.toggleFavourite({{$show->id}})"
                    class="w-6 h-6" style="color: yellow;" />
            @endif
        </x-icon-button>
        <x-icon-button
            x-show="showButtons"
            class="absolute bottom-1 right-1 "
        >
            <x-heroicon-s-trash
                @click.stop="if(confirm('Are you sure wanna delete this show?'))$wire.deleteShow()"
                class="w-6 h-6" style="color: red;" />
        </x-icon-button>
    @endif
    <img style="
            height: 200px;
            object-fit: cover;
            display: block;
            width: 136px;
        " src="{{$src}}" />
</div>
