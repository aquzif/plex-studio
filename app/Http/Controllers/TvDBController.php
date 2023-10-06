<?php

namespace App\Http\Controllers;

use App\Utils\TvDBUtils;
use Exception;
use Illuminate\Http\Request;

class TvDBController extends Controller
{
    /**
     * @throws Exception
     */
    public function search(Request $request) {

        $fields = $request->validate([
            'query' => ['required'],
        ]);

        $query = $fields['query'];
        return TvDBUtils::search($query);
    }



    private function getSeasonName($number){
        if($number == 0)
            return 'Special';
        return 'Sezon '.$number;
    }

    public function seasons(Request $request, $id){

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
