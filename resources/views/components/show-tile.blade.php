<?php

    $src = $show['thumbnail'] ?? asset('storage/default_serie.jpg');


?>

<div
    style="
        padding: 10px;
        width: 156px;
        height: 220px;
    "
>

    <x-popover>
        <img style="
                height: 200px;
                object-fit: cover;
                display: block;
                width: 136px;
            " src="{{$src}}" />
    </x-popover>
</div>
