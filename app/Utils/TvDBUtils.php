<?php

namespace App\Utils;

use App\Models\Settings;
use Http;

class TvDBUtils {

    static string $API_PIN = '';
    static string $API_KEY = '';
    static string $HOST = 'https://api4.thetvdb.com/v4';
    static string $token = '';

    static function checkToken(): void {

        $settings = Settings::getSettings();

        self::$API_KEY = $settings->tvdb_api_key;
        self::$API_PIN = $settings->tvdb_api_pin;

        if(self::$token <> '')
            return;

        $url = self::$HOST."/login";


        $result = Http::withOptions([
            'verify' => false,
        ])->post($url, [
            'apikey' => self::$API_KEY,
            'pin' => self::$API_PIN
        ]);


        if($result->status() <> 200)
            throw new \Exception('Error getting token to TVDB API');

        $json = $result->json();
        self::$token = $json['data']['token'];

    }

    static function getSeasonThumbPath($seasonID){
        self::checkToken();

        $url = self::$HOST . "/seasons/$seasonID/extended";

        $result = Http::withOptions([
            'verify' => false,
        ])->withToken(self::$token)->get($url);

        foreach ($result->json()['data']['artwork'] as $art) {
            if($art['type'] === 7)
                return $art['thumbnail'];
        }

        if(!empty($result->json()['data']['artwork']))
            return $result->json()['data']['artwork'][0]['thumbnail'];
        return false;
    }

    static function getSeasonTypeEpisodes($serieID, $type){
        self::checkToken();

        $url = self::$HOST . "/series/$serieID/episodes/$type";

        $result = Http::withOptions([
            'verify' => false,
        ])->withToken(self::$token)->get($url);

        return $result->json()['data']['episodes'];

    }

    static function getMovieByID($id)
    {

        self::checkToken();

        $url = self::$HOST . "/movies/$id";

        $result = Http::withOptions([
            'verify' => false,
        ])->withToken(self::$token)->get($url);

        return $result->json()['data'];
    }

    static function getShowTranslation($id,$lang = 'pol') {
        self::checkToken();

        $url = self::$HOST . "/series/$id/translations/$lang";

        $result = Http::withOptions([
            'verify' => false,
        ])->withToken(self::$token)->get($url);

        return $result->json()['data'];
    }

    static function getEpisodesTranslations($id,$type,$lang = 'pol') {
        self::checkToken();

        $url = self::$HOST . "/series/$id/episodes/$type/$lang";

        $result = Http::withOptions([
            'verify' => false,
        ])->withToken(self::$token)->get($url);

        return $result->json()['data'];
    }

    static function getEpisodesBySeriesID($id,$type = 'official'){

        self::checkToken();

        $url = self::$HOST."/series/$id/episodes/$type";

        $result = Http::withOptions([
            'verify' => false,
        ])->withToken(self::$token)->get($url);

        if($result->status() <> 200)
            throw new \Exception('Error getting episodes from TVDB API');

        $json = $result->json();
        return $json['data'];
    }

    static function getSeriesByID($id) {

        self::checkToken();

        $url = self::$HOST."/series/$id/extended";

        $result = Http::withOptions([
            'verify' => false,
        ])->withToken(self::$token)->get($url);


        if($result->status() <> 200)
            throw new \Exception('Error getting series from TVDB API');

        $json = $result->json();
        return $json['data'];

    }

    static function search(string $query): array {

        self::checkToken();

        $url = self::$HOST.'/search';
        $data = [
            'q' => $query,
            'limit' => 50,
        ];

        $result = Http::withOptions([
            'verify' => false,
        ])->withToken(self::$token)->get($url, $data);
        $json = $result->json();

        //show only type series and movie
        $json['data'] = array_filter($json['data'], function($item){
            return $item['type'] == 'series' || $item['type'] == 'movie';
        });


        if($result->status() <> 200)
            throw new \Exception('Error searching series on TVDB API');

        return array_slice($json['data'], 0, 10);

    }


}
