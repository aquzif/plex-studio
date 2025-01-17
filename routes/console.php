<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use App\Models\Episode;
use App\Models\Season;
use App\Utils\ImageUtils;
use App\Utils\TvDBUtils;
use App\Utils\WPlikUtils;
use Intervention\Image\Image;
use App\Models\Url;


Artisan::command('schedule:jdownloaderupdate', function () {
    \App\Schedules\JDownloadUpdate::run();
})->everyMinute()->withoutOverlapping(10);

Artisan::command('sync:tvdb',function (){

    $shows = \App\Models\Show::all();

    $results = [];

    foreach ($shows as $show) {

        if($show->seasons_type == '')
            $show->seasons_type = 'official';
        if($show->type == 'movie') continue;

        $tvdbEpisodes = TvDBUtils::getEpisodesBySeriesID($show->tvdb_id,$show->seasons_type);
        $tvdbShow = TvDBUtils::getSeriesByID($show->tvdb_id);

        $lang = in_array('pol',$tvdbShow['nameTranslations']) ? 'pol' : 'eng';

        $nameTranslation = TvDBUtils::getShowTranslation($show->tvdb_id,$lang);
        $episodesTranslation = TvDBUtils::getEpisodesTranslations($show->tvdb_id,$show->seasons_type,$lang);

        $tvdbSeasons = [];

        $show['name'] = $nameTranslation['name'] ?? $show['name'];

        foreach ($tvdbShow['seasons'] as $season) {
            if($season['type']['type'] === $show->seasons_type)
                $tvdbSeasons[] = $season;
        }

        foreach ($tvdbSeasons as $tvdbSeason) {
            if(!$show->seasons()->where('season_order_number',$tvdbSeason['number'])->exists()){

                print 'Dla serialu "'.$show['name'].'" został zarejestrowany nowy sezon: '.$tvdbSeason['number']."\n";

                $results[] = [
                    'type' => 'ADD_SEASON',
                    'show' => $show['name'],
                    'season' => $tvdbSeason['number']
                ];

                $season = [
                    'show_id' => $show['id'],
                    'tvdb_id' => $tvdbSeason['id'],
                    'name' => $tvdbSeason['number'] > 0 ? 'Sezon '.$tvdbSeason['number'] : 'Specials',
                    'season_order_number' => $tvdbSeason['number'],
                    'thumb_path' => isset($tvdbSeason['image']) ? ImageUtils::downloadImage($tvdbSeason['image']) : '/images/missing.jpg',
                    'episodes' => []
                ];
                $season = Season::create($season);
            }
        }



        foreach ($tvdbEpisodes['episodes'] as $tvdbEpisode) {
            $season = $show->seasons()->where('season_order_number',$tvdbEpisode['seasonNumber'])->first();
            if($season->episodes()->where('episode_order_number',$tvdbEpisode['number'])->exists()) {

                $episode = $season->episodes()->where('episode_order_number',$tvdbEpisode['number'])->first();

                $epName = null;
                foreach ($episodesTranslation['episodes'] as $episodeTranslation) {
                    if($episodeTranslation['id'] === $tvdbEpisode['id']){
                        $epName = $episodeTranslation['name'];
                        break;
                    }
                }

                $episode['name'] = $epName ?? $tvdbEpisode['name'] ?? '';
                $episode['release_date'] = $tvdbEpisode['firstAired'] ?? $tvdbEpisode['aired'] ?? null;
                $episode->save();


            }else{

                $epName = null;
                foreach ($episodesTranslation['episodes'] as $episodeTranslation) {
                    if($episodeTranslation['id'] === $tvdbEpisode['id']){
                        $epName = $episodeTranslation['name'];
                        break;
                    }
                }
                print 'Dla serialu "'.$show['name'].'" został zarejestrowany nowy odcinek: '.($epName ?? $tvdbEpisode['name']).' ('.$tvdbEpisode['number'].")\n";

                $results[] = [
                    'type' => 'ADD_EPISODE',
                    'show' => $show['name'],
                    'season' => $tvdbEpisode['seasonNumber'],
                    'episode' => $tvdbEpisode['number'],
                    'name' => $epName ?? $tvdbEpisode['name']
                ];

                Episode::create([
                    'name' => $epName ?? $tvdbEpisode['name'] ?? '',
                    'season_id' => $season['id'],
                    'episode_order_number' => $tvdbEpisode['number'],
                    'show_id' => $show['id'],
                    'tvdb_id' => $tvdbEpisode['id'],
                    'downloaded' => false,
                    'release_date' => $tvdbEpisode['firstAired'] ?? $tvdbEpisode['aired'] ?? null,
                ]);
            }
        }

        $show->save();



    }

    foreach (Episode::all() as $episode) {
        //if released yesterday
        if($episode->release_date === null) continue;
        $dateAsObj = \Carbon\Carbon::parse($episode->release_date);

        if($dateAsObj->diffInDays(now()) === 1){
            $results[] = [
                'type' => 'RELEASE',
                'show' => $episode->show->name,
                'season' => $episode->season->season_order_number,
                'episode' => $episode->episode_order_number,
                'release_date' => $dateAsObj->format('Y-m-d'),
                'name' => $episode->name
            ];
        }
    }

   /* $emails = ['aquzif@gmail.com','automat@aquzif.com'];

    //send email
    print 'do wysłania: '.count($results)."\n";
    if(count($results) > 0) {
        //$email = new NewDataMail($results);
        //Mail::to('aquzif@gmail.com')->send($email);
        //Mail::to('automat@aquzif.com')->send($email);
        Mail::send('emails.new-data', ['results'=>$results], function($message) use ($emails)
        {
            $message->to($emails)->subject('Nowości w plex');
        });

    }*/



    Artisan::call('fix_thumbs');

})->dailyAt('12:00');
Artisan::command('fix_thumbs',function (){
    $shows = \App\Models\Show::all();
    foreach ($shows as $show) {

        print $show->name."\n";

        foreach ($show->seasons()->get() as $season) {
            print "Season: ".$season['season_order_number']."\n";
            $newImg = TvDBUtils::getSeasonThumbPath($season['tvdb_id']);

            if($newImg === false) {
                $season['thumb_path'] = '/images/missing.jpg';
                $season->save();
                continue;
            }

            if($season['thumb_path'] === '/images/missing.jpg'){
                $season['thumb_path'] = ImageUtils::downloadImage($newImg);
                $season->save();
            }else{
                ImageUtils::replaceImageWithDownloadedImage(
                    $newImg,
                    $season['thumb_path']
                );
            }

            //$img = Image::make(Storage::path($season['thumb_path']));
            //$img->resize(null, 200, function ($constraint) {
            //    $constraint->aspectRatio();
            //});

            //$img->save(Storage::path($season['thumb_path']));




        }
    }
});


Artisan::command('upgrade', function () {

    foreach (\App\Models\Show::all() as $show) {

        $show->audio_languages = fixJSONValue($show->audio_languages);
        $show->subtitle_languages = fixJSONValue($show->subtitle_languages);
        $show->save();

        foreach ($show->seasons()->get() as $season) {
            $season->audio_languages = fixJSONValue($season->audio_languages);
            $season->subtitle_languages = fixJSONValue($season->subtitle_languages);
            $season->save();
        }

    }

    foreach (\App\Models\Url::all() as $url) {
        if($url->quality === 'undefined')
            $url->quality = 'unknown';
        $url->save();
    }

});
function fixJSONValue($val){

    if(!json_decode($val)){
        return '[]';
    }

    $data = json_decode($val);

    if(!is_array($data)){
        return '[]';
    }

    if(count($data) == 0){
        return '[]';
    }

    if(is_object($data[0]) && isset($data[0]->ord)){
        return json_encode(array_map(function($item){
            return $item->title;
        },$data));
    }

    return $val;

}
