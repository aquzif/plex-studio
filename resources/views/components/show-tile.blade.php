<?php

    $src = $show['thumbnail'] ?? asset('storage/default_serie.jpg');


?>

<div
    style="
        padding: 10px;
        position: relative;
        width: 156px;
        height: 220px;
    "
>

    <x-popover>
        <x-slot name="trigger">
            <img style="
                height: 200px;
                object-fit: cover;
                display: block;
                width: 136px;
            " src="{{$src}}" />
        </x-slot>
        <div>
            Popover content goes here.
        </div>
    </x-popover>
</div>
