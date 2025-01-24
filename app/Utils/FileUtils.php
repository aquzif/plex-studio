<?php

namespace App\Utils;

class FileUtils {


    public static function getExtensionFromName($name) {
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        return $ext;
    }

    public static function isPartArchive($name) {
        return strpos($name, '.part') !== false;
    }


}
