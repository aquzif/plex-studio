<?php

namespace App\Utils;

class UrlUtils {

    static function getQualityFromUrl($url){
        $quality = 'undefined';
        if(str_contains($url, '480p')){
            $quality = '480p';
        }elseif(str_contains($url, '720p')){
            $quality = '720p';
        }elseif(str_contains($url, '1080p')){
            $quality = '1080p';
        }elseif(str_contains($url, '2160p')) {
            $quality = '2160p';
        }
        return $quality;
    }

}
