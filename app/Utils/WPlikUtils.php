<?php

namespace App\Utils;

class WPlikUtils {

    static $TOKEN = '397948gr7pv2zdzrkqk5r';

    static function checkUrls($ids){

        $url = 'https://www.wplik.com/api/file/info?key='.self::$TOKEN.'&file_code='.implode(',',$ids);

        return \Http::get($url)->json();

    }

}
