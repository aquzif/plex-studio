<?php

namespace App\Models;

use App\Utils\ImageUtils;
use App\Utils\TvDBUtils;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Show extends Model
{
    public $timestamps = false;
//    protected $with = ['seasons','urls'];

    protected $fillable = [
        'name',
        'type',
        'tvdb_id',
        'thumb_path',
        'downloaded',
        'favourite',
        'seasons_type',
        'quality',
        'needs_update',
        'audio_languages',
        'subtitle_languages',
    ];

    protected $casts = [
        'downloaded' => 'boolean',
        'favourite' => 'boolean',
        'needs_update' => 'boolean',
    ];

    public function seasons(){
        return $this->hasMany(Season::class)->orderBy('season_order_number');
    }

    public function howManyEpisodes() {
        $seasons = $this->seasons()->where('season_order_number','>',0)->get();
        return $seasons->map(function($season){
            return $season->episodes()->count();
        })->sum();
    }

    public function howManyReleasedEpisodes() {
        $seasons = $this->seasons()->where('season_order_number','>',0)->get();
        return $seasons->map(function($season){
            return $season->episodes()->where('release_date', '<=', now())->count();
        })->sum();
    }

    public function howManyDownloadedEpisodes() {
        $seasons = $this->seasons()->where('season_order_number','>',0)->get();
        return $seasons->map(function($season){
            return $season->episodes()->where('downloaded',true)->count();
        })->sum();
    }

    public function howManyDownloadedReleasedEpisodes() {
        $seasons = $this->seasons()->where('season_order_number','>',0)->get();
        return $seasons->map(function($season){
            return $season->episodes()->where('downloaded',true)->where('release_date', '<=', now())->count();
        })->sum();
    }

    public function urls(){
        return $this->hasMany(Url::class,'movie_id','id');
    }


    public static function boot() {
        parent::boot();

        static::deleting(function($show) {
            Storage::delete($show->thumb_path);
            foreach ($show->seasons()->get() as $item) {
                $item->delete();
            }

        });
    }

    public static function createNewShow($type,$tvdb_id,$seasons_type = 'official'){
        if($type === 'series'){
            $mainData = TvDBUtils::getSeriesByID($tvdb_id);
        }else{
            $mainData = TvDBUtils::getMovieByID($tvdb_id);
        }

        //check if $mainData['nameTranslations'] contains 'pol' as value
        $lang = in_array('pol',$mainData['nameTranslations']) ? 'pol' : 'eng';

        $nameTranslation = TvDBUtils::getShowTranslation($tvdb_id,$lang);
        $episodesTranslation = TvDBUtils::getEpisodesTranslations($tvdb_id,$seasons_type,$lang);



        $fields['thumb_path'] = ImageUtils::downloadImage($mainData['image']);
        $fields['name'] = $nameTranslation['name'] ?? $mainData['name'];
        $fields['type'] = $type;
        $fields['tvdb_id'] = $tvdb_id;
        $fields['seasons_type'] = $seasons_type;

        $show = Show::create($fields);

        if($fields['type'] === 'series'){

            $seasons = [];
            $episodes = TvDBUtils::getEpisodesBySeriesID($show['tvdb_id'], $fields['seasons_type']);

            foreach($mainData['seasons'] as $season){
                if($season['type']['type'] !== $fields['seasons_type'])
                    continue;

                $seasons[] = [
                    'show_id' => $show['id'],
                    'tvdb_id' => $season['id'],
                    'name' => $season['number'] > 0 ? 'Sezon '.$season['number'] : 'Specials',
                    'season_order_number' => $season['number'],
                    'thumb_path' => isset($season['image']) ? ImageUtils::downloadImage($season['image']) : 'default',
                    'episodes' => []
                ];
            }

            foreach ($seasons as $season) {
                $seasonObject = Season::create($season);

                foreach ($episodes['episodes'] as $episode) {
                    if($episode['seasonNumber'] === $season['season_order_number']){
                        $epName = null;
                        foreach ($episodesTranslation['episodes'] as $episodeTranslation) {
                            if($episodeTranslation['id'] === $episode['id']){
                                $epName = $episodeTranslation['name'];
                                break;
                            }
                        }

                        Episode::create([
                            'name' => $epName ?? $episode['name'] ?? '',
                            'season_id' => $seasonObject['id'],
                            'episode_order_number' => $episode['number'],
                            'show_id' => $show['id'],
                            'tvdb_id' => $episode['id'],
                            'downloaded' => false,
                            'release_date' => $episode['firstAired'] ?? $episode['aired'] ?? null,
                        ]);
                    }

                }

            }

        }
    }

    public function getStatus()
    {

        $red = 'red-600';
        $green = 'green-600';
        $yellow = 'yellow-500';

        if($this->type === 'movie') {
            $urlsCount = $this->urls->count();
            $downloadedUrlsCount = $this->urls->where('downloaded', true)->count();
            if ($this->downloaded ||
                ($urlsCount === $downloadedUrlsCount && $urlsCount > 0)
            ) {
                return [
                    'color' => $green,
                    'value' => '100%',
                    'fullyDownloaded' => true
                ];
            } else {


                if($urlsCount === 0 || $downloadedUrlsCount === 0) {
                    return [
                        'color' => $red,
                        'value' => '0%',
                        'fullyDownloaded' => false
                    ];
                }else{
                    return [
                        'color' => $yellow,
                        'value' => round(($downloadedUrlsCount / $urlsCount) * 100) . '%',
                        'fullyDownloaded' => false
                    ];
                }
            }
        }else{
            $episodes = $this->howManyReleasedEpisodes();
            $downloaded = $this->howManyDownloadedReleasedEpisodes();

            if($episodes === 0 || $downloaded === 0) {
                return [
                    'color' => $red,
                    'value' => '0%',
                    'fullyDownloaded' => false
                ];
            }else if ($episodes === $downloaded){
                return [
                    'color' => $green,
                    'value' => '100%',
                    'fullyDownloaded' => true
                ];
            }else{
                return [
                    'color' => $yellow,
                    'value' => round(($downloaded / $episodes) * 100) . '%',
                    'fullyDownloaded' => false
                ];
            }

        }
    }

    public static function getSeasons($id) {

        $toReturn = [];
        $seasons = TvDBUtils::getSeriesByID($id)['seasons'];

        foreach ($seasons as $seasonType) {
            $exists = false;
            foreach ($toReturn as $item) {
                if($item['type'] == $seasonType['type']['type']){
                    $exists = true;
                }
            }

            if(!$exists)
                $toReturn[] = [
                    'name' => $seasonType['type']['name'],
                    'type' => $seasonType['type']['type'],
                    'seasons' => []
                ];
        }

        foreach ($toReturn as &$item) {

            $episodes = TvDBUtils::getSeasonTypeEpisodes($id, $item['type']);

            foreach ($seasons as $season) {
                if($item['type'] == $season['type']['type'])
                    $item['seasons'][$season['number']] = 0;
            }

            //sort keys


            foreach ($episodes as $episode) {
                $item['seasons'][$episode['seasonNumber']] = $item['seasons'][$episode['seasonNumber']]+1;
            }
            ksort($item['seasons']);
        }

        return $toReturn;

    }

}
