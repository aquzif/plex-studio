<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Artisan::command('upgrade', function () {

    foreach (\App\Models\Show::all() as $show) {

        $show->audio_languages = fixJSONValue($show->audio_languages);
        $show->subtitle_languages = fixJSONValue($show->subtitle_languages);
        $show->save();
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
