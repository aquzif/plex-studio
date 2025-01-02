<?php

namespace App\Utils;

class Utils
{

    public static function getQualities() {
        return[
            [
                'value' => 'undef',
                'label' => '<p style="color: '.config('plex.qualityColors.unknown').'" >Undef</p>',
            ],
            [
                'value' => '480p',
                'label' => '<p style="color: '.config('plex.qualityColors.bad').'" >480p</p>',
            ],
            [
                'value' => '720p',
                'label' => '<p style="color: '.config('plex.qualityColors.medium').'" >720p</p>',
            ],
            [
                'value' => '1080p',
                'label' => '<p style="color: '.config('plex.qualityColors.good').'" >1080p</p>',
            ],
            [
                'value' => '2160p',
                'label' => '<p style="color: '.config('plex.qualityColors.best').'" >2160p</p>',
            ]
        ];
    }

    public static function getAudioLanguages() {
        return [
            [
                'value' => 'Dubbing',
                'label' => '<p style="color: '.config('plex.qualityColors.best').'" >Dubbing</p>',
            ],
            [
                'value' => 'Lektor',
                'label' => '<p style="color: '.config('plex.qualityColors.best').'" >Lektor</p>',
            ],
            [
                'value' => 'Angielski',
                'label' => '<p style="color: '.config('plex.qualityColors.good').'" >Angielski</p>',
            ],

        ];
    }

    public static function getSubtitleLanguages() {
        return [
            [
                'value' => 'Polski',
                'label' => '<p style="color: '.config('plex.qualityColors.best').'" >Polski</p>',
            ],
            [
                'value' => 'Angielski',
                'label' => '<p style="color: '.config('plex.qualityColors.good').'" >Angielski</p>',
            ],

        ];
    }
}
