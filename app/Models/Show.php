<?php

namespace App\Models;

use App\Utils\ImageUtils;
use App\Utils\TvDBUtils;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Show extends Model
{
    public $timestamps = false;
    protected $with = ['seasons','urls'];

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
                    'thumb_path' => isset($season['image']) ? ImageUtils::downloadImage($season['image']) : null,
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

}
