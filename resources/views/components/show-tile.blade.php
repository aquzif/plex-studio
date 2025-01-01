<?php

    $red = 'red-600';
    $green = 'green-600';
    $yellow = 'yellow-500';

    $color = 'white';
    $src = '';
    $title = '';
    $subValue = '';


    if(isset($show)){
        $src = $show['thumb_path'] ?? asset('storage/default_serie.jpg');
        $title = $show['name'];
        $type = $show['type'];

        $downloaded = $show['downloaded'] ?? false;
        $favorite = $show['favorite'] ?? false;

        if($type === 'movie') {
            if ($downloaded) {
                $color = $green;
                $subValue = '100%';
            } else {
                $color = $red;
                $subValue = '0%';
            }
        }else{
            $episodes = $show->howManyEpisodes();
            $downloaded = $show->howManyDownloadedEpisodes();

            if($episodes === 0 || $downloaded === 0) {
                $color = $red;
                $subValue = '0%';
            }else if ($episodes === $downloaded){
                $color = $green;
                $subValue = '100%';
            }else{
                $color = $yellow;
                $subValue = round(($downloaded / $episodes) * 100) . '%';
            }

        }
    }else{
        $src = $season['thumb_path'] ?? 'default';
        $title = $season['name'];


        $episodes = $season->howManyEpisodes();
        $downloaded = $season->howManyDownloadedEpisodes();
        if($episodes === 0 || $downloaded === 0) {
            $color = $red;
            $subValue = '0%';
        }else if ($episodes === $downloaded){
            $color = $green;
            $subValue = '100%';
        }else{
            $color = $yellow;
            $subValue = round(($downloaded / $episodes) * 100) . '%';
        }



    }




?>


<div

    style="
        margin: 20px;
        width: 136px;
        position: relative;
        height: 200px;
    "
    {{ Popper::interactive()->arrow('round')->placement('bottom')->pop($title) }}
>


    <x-popover title="{{$title}}">
        <div class="border-{{$color}} borderWithInfo" ></div>
        <div class="border-{{$color}} borderWithInfoRadius" ></div>
        <div class="borderInfo bg-{{$color}} " >{{$subValue}}</div>
        <img style="
                height: 200px;
                object-fit: cover;
                display: block;
                width: 136px;
            " src="{{$src}}" />
    </x-popover>
</div>
