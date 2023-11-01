<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Season;
use App\Models\Show;
use App\Utils\ImageUtils;
use App\Utils\TvDBUtils;
use Illuminate\Http\Request;
use Storage;

class ShowController extends Controller
{
    public function index()
    {
        return Show::all();
    }

    public function show(Show $show){
        return $show;
    }

    public function getByTvdbID(Show $show){
        return $show;
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            //'name' => ['required'],
            'type' => ['required','in:series,movie'],
            'tvdb_id' => ['required'],
            'seasons_type' => ['string'],
            //'thumb_path' => ['required'],
        ]);

        if(!isset($fields['seasons_type']))
            $fields['seasons_type'] = 'official';

        if($fields['type'] === 'series'){
            $mainData = TvDBUtils::getSeriesByID($fields['tvdb_id']);
        }else{
            $mainData = TvDBUtils::getMovieByID($fields['tvdb_id']);
        }

        //check if $mainData['nameTranslations'] contains 'pol' as value
        $lang = in_array('pol',$mainData['nameTranslations']) ? 'pol' : 'eng';

        $nameTranslation = TvDBUtils::getShowTranslation($fields['tvdb_id'],$lang);
        $episodesTranslation = TvDBUtils::getEpisodesTranslations($fields['tvdb_id'],$fields['seasons_type'],$lang);



        $fields['thumb_path'] = ImageUtils::downloadImage($mainData['image']);
        $fields['name'] = $nameTranslation['name'] ?? $mainData['name'];

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
                    'thumb_path' => isset($season['image']) ? ImageUtils::downloadImage($season['image']) : '/images/missing.jpg',
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



        return Show::find($show['id']);
    }

    public function update(Request $request, Show $show)
    {
        $fields = $request->validate([
            //'name' => ['required'],
            'type' => ['in:series,movie'],
            'downloaded' => ['boolean'],
            'favourite' => ['boolean'],
            //'thumb_path' => ['required'],
        ]);

        $show->update($fields);
        return $show;
    }

    public function destroy(Show $show)
    {
        $show->delete();
        return response()->json();
    }
}
