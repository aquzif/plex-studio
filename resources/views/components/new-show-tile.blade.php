<?php

    $src = $show['thumbnail'] ?? asset('storage/default_serie.jpg');
    $title = $show['extended_title'] ?? $show['name'] ?? '';


?>

<div
    style="
        padding: 10px;
        position: relative;
        width: 156px;
        height: 220px;
    "
{{--    @click="$store.loader.show();$wire.dispatch('newShowSelect',{showId: {{$show['tvdb_id']}}})"--}}
        @click="window.asyncEventWithLoader('newShowSelect', {showId: {{$show['tvdb_id']}}})"
>

    <x-popover title="{{$title}}" >
        <img style="
                height: 200px;
                object-fit: cover;
                display: block;
                width: 136px;
            " src="{{$src}}" />
    </x-popover>
</div>
