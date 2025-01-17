<?php

namespace App\Utils;

class ConfigUtils {

    public static function getConfig() {

        return auth()->user()->getSettings();

    }

    public static function setConfigValue($key,$value) :void {

        $settings = auth()->user()->getSettings();
        $settings->update([
            $key => $value
        ]);

    }

}
