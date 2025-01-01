<?php

    $quality = $quality ?? 'unknown';

    $qualityColor = match ($quality) {
        '480p' => config('plex.qualityColors.bad'),
        '720p' => config('plex.qualityColors.medium'),
        '1080p' => config('plex.qualityColors.good'),
        '2160p' => config('plex.qualityColors.best'),
        default => config('plex.qualityColors.unknown'),
    };



$classes = '
    border-2
    rounded-full
    px-2
    py-1
    font-bold
    text-center
    inline-block

';



?>

<div
   {{$attributes->merge([
        'style' => "border-color: $qualityColor; color: $qualityColor"
        ,'class' => $classes
       ])}}
>
    {{$quality}}
</div>
