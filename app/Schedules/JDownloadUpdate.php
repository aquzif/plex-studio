<?php

namespace App\Schedules;

use App\Models\Settings;
use App\Models\Url;
use App\Utils\JDownloaderUtils;
use App\Utils\StringUtils;
use App\Utils\UrlUtils;
use Illuminate\Support\Facades\Storage;

class JDownloadUpdate {

    private static JDownloaderUtils $jDownloader;

    public static function run() {


        $settings = Settings::first();
        self::$jDownloader = new JDownloaderUtils($settings->jdownloader_email, $settings->jdownloader_password, $settings->jdownloader_device);


        self::moveDownloadedFilesToPlex();
        self::addLinksToJdownlader();



    }

    public static function moveDownloadedFilesToPlex()
    {

        $packages = json_decode(self::$jDownloader->callAction('/downloadsV2/queryPackages'
            ,[
                "finished" => true,
            ]
        ));

        foreach ($packages->data as $package) {

            if(!isset($package->finished) || !$package->finished)
                continue;

            $url = Url::where('auto_download', true)
                ->where('auto_download_status', '=', 'downloading')
                ->where('package_name', $package->name)
                ->first();

            $show = $url->episode()->first()->show()->first();
            $season = $url->episode()->first()->season()->first();

            if(!$url)
                continue;


            foreach (Storage::drive('plex')->allFiles(
                UrlUtils::joinPaths(
                    config('plex.jd_files_dir'),
                    $package->name
                )
            ) as $file) {

                if(!in_array(pathinfo($file, PATHINFO_EXTENSION),['mp4','mkv','avi','flv','mov','wmv','webm','3gp','mpg','mpeg','m4v','m2v','ts','mts','m2ts','vob','divx','xvid','rm','rmvb','asf','ogm','ogv','3g2','3gp2','3gpp','mpv','m1v','m2v','m2t','m2ts','m4b','m4p','m4v','m4a','m4r','3ga','3gpa','3gpp','3gp2','3gp'
                ])){
                    continue;
                }

                if($show->type == 'movie') {
                    //TODO
                    dd('TODO');
                }else{
                    $showDir = $show->directory_name;
                    $seasonNumber =
                        $season->season_order_number < 10 ? '0'.$season->season_order_number : $season->season_order_number;

                    $dirPath = UrlUtils::joinPaths(
                        config('plex.series_dir')
                        ,$showDir
                        ,'Season '.$seasonNumber
                    );

                    Storage::drive('plex')->makeDirectory($dirPath);

                    //get last exploded
                    $fileName = explode('/',$file);
                    $fileName = $fileName[count($fileName)-1];


                    Storage::drive('plex')->move(
                        $file,
                        UrlUtils::joinPaths(
                            $dirPath,
                            $fileName
                        )
                    );

                    Storage::drive('plex')->deleteDirectory(
                        UrlUtils::joinPaths(
                            config('plex.jd_files_dir'),
                            $package->name
                        )
                    );

                    $packageId = $package->uuid;
                    $res = self::$jDownloader->callAction(
                        "/downloadsV2/cleanup?[]&[$packageId]&DELETE_FINISHED&REMOVE_LINKS_ONLY&SELECTED"
                    );

                    $url->update([
                        'auto_download_status' => 'completed'
                    ]);

                }
            }
        }
    }

    public static function addLinksToJDownlader()
    {
        $urls = Url::where('auto_download', true)
            ->where('auto_download_status', '=', 'pending')
            ->get();

        foreach ($urls as $url) {

            $packageName = StringUtils::randomString(35);

            $res = self::$jDownloader->callAction('/linkgrabberv2/addLinks',[
                'autostart' => true,
                'links'=> $url->url,
                'packageName' => $packageName,
                'overwritePackagizerRules' => true
            ]);

            $url->update([
                'package_name' => $packageName,
                'auto_download_status' => 'downloading'
            ]);

        }

    }

}
