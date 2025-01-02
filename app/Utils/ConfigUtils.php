<?php

namespace App\Utils;

class ConfigUtils {

    public static function getConfig() {
        return session('config',[
            'sortBy' => 'name',
            'sortType' => 'asc',
            'showOnlyIncomplete' => false,
            'showOnlyFavourites' => false,
            'hideDownloadedEpisodes' => false
        ]);
    }

    public static function setConfigValue($key,$value) :void {
        $config = self::getConfig();
        $config[$key] = $value;
        session(['config' => $config]);
    }

}
