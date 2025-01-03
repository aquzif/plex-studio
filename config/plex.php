<?php


return [
    'qualityColors' => [
      'bad' => '#008900',
      'medium' => '#00bfff',
      'good' => '#8a00ff',
      'best' => '#ffbf00',
      'unknown' => '#878787',
    ],
    'series_dir' => env('PLEX_FILES_SERIES_DIR',''),
    'movies_dir' => env('PLEX_FILES_MOVIES_DIR',''),
    'jd_files_dir' => env('JD_FILES_DIR',''),
];
