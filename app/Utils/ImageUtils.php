<?php

namespace App\Utils;

use Storage;

class ImageUtils {

    static function downloadImage($url): string {

        $thumb_path = '/images/'.uniqid().'.jpg';
        $thumb = file_get_contents($url);
        Storage::put($thumb_path, $thumb);
        return $thumb_path;

    }

    static function replaceImageWithDownloadedImage($url, $old_image_path): string {
        Storage::delete($old_image_path);
        $thumb = file_get_contents($url);
        Storage::put($old_image_path, $thumb);
        return $old_image_path;

    }

}
