<?php

namespace App\Utils;

class UrlUtils {

    static function getQualityFromUrl($url){
        $quality = 'unknown';
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

    static function getSeasonAndEpisodeFromUrl($url){
        $season = null;
        $episode = null;
        $matches = [];
        if(preg_match('/s(\d{1,2})e(\d{1,2})/i', $url, $matches)){
            $season = $matches[1];
            $episode = $matches[2];
        }elseif(preg_match('/(\d{1,2})x(\d{1,2})/i', $url, $matches)) {
            $season = $matches[1];
            $episode = $matches[2];
        }
        return ['season' => (int)$season, 'episode' => (int)$episode];
    }

}
