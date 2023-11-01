<?php

use App\Mail\NewDataMail;
use App\Models\Episode;
use App\Models\Season;
use App\Utils\ImageUtils;
use App\Utils\TvDBUtils;
use App\Utils\WPlikUtils;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Intervention\Image\Image;
use App\Models\Url;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('fix:urls',function (){
    $urls = Url::all();
    foreach ($urls as $url) {
        $url->quality = \App\Utils\UrlUtils::getQualityFromUrl($url->url);
        $url->save();
    }
});

Artisan::command('urlcheck:wrzucaj',function () {

    $urls = Url::where(
        'host',
        'wrzucaj.pl'
    )->where('downloaded',false)
        ->where('last_validated_date',
            '<',
            \Carbon\Carbon::now()->subHours(24)
        )->orderBy('last_validated_date')->get();

    $i=0;

    foreach ($urls as $url) {
        $i++;
        if ($i > 50) break;
        if($url->episode()->count() > 0){
            if($url->episode()->first()['downloaded'] === true) continue;
        }
        $client = new \GuzzleHttp\Client([
            'headers' => [
                // ...
                'User-Agent'   => 'curl/7.65.3',
            ],
        ]);
        $data = $client->request('GET',$url->url);

        dd($data);

    }

});
Artisan::command('urlcheck:wplik',function (){
    $urls = Url::where(
        'host',
        'wplik.com'
    )->where('downloaded',false)
    ->where('last_validated_date',
        '<',
        \Carbon\Carbon::now()->subHours(24)
    )->orderBy('last_validated_date')->get();

    $codesToCheck = [];
    $urlsToUpdate = [];

    foreach ($urls as $url) {

        if($url->episode()->count() > 0){
            if($url->episode()->first()['downloaded'] === true) continue;
        }
        $codesToCheck[] = explode('/',$url->url)[3];
        $urlsToUpdate[] = $url;
    }
    if($codesToCheck === []) return;

    $codesToCheck = array_slice($codesToCheck,0,50);

    $result = WPlikUtils::checkUrls($codesToCheck);


    foreach ($urlsToUpdate as $urls) {
        foreach ($result['result'] as $res) {
            if(str_contains($urls->url,$res['filecode'])){
                $urls->last_validated_date = now();
                $urls->auto_valid = $res['status'] !== 404;
                $urls->update();
            }


        }
    }

});

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

    $emails = ['aquzif@gmail.com','automat@aquzif.com'];

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

    }



    Artisan::call('fix_thumbs');

});

