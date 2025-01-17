<?php

namespace App\Schedules;

use App\Connectors\JDownloaderConnector;
use App\Enums\UrlStatus;
use App\Models\Settings;
use App\Models\Url;
use App\Utils\jDownloaderUtils;
use App\Utils\StringUtils;
use App\Utils\UrlUtils;
use Illuminate\Support\Facades\Storage;

class JDownloadUpdate {

    private static JDownloaderConnector $jDownloader;

    public static function rescheduleLinksCheck() {
        $settings = Settings::first();
        self::$jDownloader = new JDownloaderConnector($settings->jdownloader_email, $settings->jdownloader_password, $settings->jdownloader_device);

        $urls = Url::where('package_name',  '!=', null)
            ->where(function ($query) {
                $query->where('auto_download_status', 'ready to download')
                    ->orWhere('auto_download_status', 'fetching avalability status');
            })
            ->where('last_validated_date',null)
            ->limit(20)
            ->get();

        if(!$urls){
            $urls = Url::where('package_name',  '!=', null)
                ->where(function ($query) {
                    $query->where('auto_download_status', 'ready to download')
                        ->orWhere('auto_download_status', 'fetching avalability status');
                })
                ->orderBy('last_validated_date')
                ->limit(20)
                ->get();
        }

        $uuids = [];




    }

    public static function run() {


        $settings = Settings::first();
        self::$jDownloader = new JDownloaderConnector($settings->jdownloader_email, $settings->jdownloader_password, $settings->jdownloader_device);


        self::addNewLinksTojDownloader();
        self::fetchUUIDsToUrls();
        self::checkLinksAvalability();
        self::getStatusOfUrlsInDownload();
        self::requestCheckLinksAvalability();
        self::startDownloadingLinks();
        self::moveDownloadedFilesToPlex();



    }

    public static function test() {

        $packageName = StringUtils::randomString(35);

//        $res = self::$jDownloader->callAction('/linkgrabberv2/addLinks',[
//            'autostart' => false,
//            'links'=> 'http://212.183.159.230/100MB.zip',
//            'packageName' => $packageName,
//            'overwritePackagizerRules' => true
//        ]);
//
//        dd('asd');

        $packages = json_decode(self::$jDownloader->callAction('/linkgrabberv2/queryPackages'
            ,[
                "availableOfflineCount" => true,
                "availableOnlineCount" => true,
                "availableTempUnknownCount" => true,
                "bytesTotal" => true,

            ]
        ));



        $name = $packages->data[0]->name;
        $uuid = $packages->data[0]->uuid;

        $res = json_decode(self::$jDownloader->callAction(
            "/linkgrabberv2/moveToDownloadlist?[]&[$uuid]"
        ));





        dd($res);

    }

    public static function getStatusOfUrlsInDownload() {

        $packages = JDownloaderUtils::getPackagesInDownload();

        foreach ($packages as $package) {
            if(isset($package->finished) && $package->finished)
                continue;

            $url = Url::where('package_name',$package->name)->first();

            if(!$url)
                continue;

            $url->update([
                'download_status' => $package->status
            ]);

        }


    }

    public static function startDownloadingLinks(){
        $urls = Url::where('status',UrlStatus::WAITING_FOR_START_DOWNLOAD)
            ->get();

        foreach ($urls as $url) {
            JDownloaderUtils::startDownloadPackage($url->package_uuid);
            $url->update([
               'status' => UrlStatus::DOWNLOADING
            ]);
        }
    }


    public static function checkLinksAvalability() {

        $urls = Url::where(function ($query) {
                $query->where('status', UrlStatus::READY)
                    ->orWhere('status', UrlStatus::INSERTED)
                    ->orWhere('status',UrlStatus::UNAVAILABLE);
            })->get();

        $uuids = [];
        foreach ($urls as $url) {
            $uuids[] = $url->package_uuid;
        }

        $packages = JDownloaderUtils::getPackagesInLinkGrabber($uuids);

        foreach ($packages as $package) {
            foreach ($urls as $url) {
                if($package->name === $url->package_name){
                    $url->update([
                        'auto_valid' => $package->offlineCount == 0,
                        'status' => $package->offlineCount == 0 ? UrlStatus::READY : UrlStatus::UNAVAILABLE
                    ]);
                }
            }
        }

    }

    public static function requestCheckLinksAvalability() {

        $urls = Url::where('package_name',  '!=', null)
            ->where(function ($query) {
                $query->where('status', UrlStatus::READY)
                    ->orWhere('status', UrlStatus::INSERTED)
                    ->orWhere('status',UrlStatus::UNAVAILABLE);
            })
            ->orderBy('last_validated_date')
            ->limit(20)
            ->get();


        $uuids = [];
        foreach ($urls as $url) {
            $uuids[] = $url->package_uuid;
        }

        JdownloaderUtils::requestLinkGrabberRefresh($uuids);


        foreach ($urls as $url) {
            $url->update([
                'last_validated_date' => now()
            ]);
        }


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
                ->where('status', '=', UrlStatus::DOWNLOADING)
                ->where('package_name', $package->name)
                ->first();

            if(!$url)
                continue;

            $show = $url->episode()->first()->show()->first();
            $season = $url->episode()->first()->season()->first();




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
                        'status' => UrlStatus::COMPLETED
                    ]);

                }
            }
        }
    }

    public static function fetchUUIDsToUrls() {
        $urls = Url::where('episode_id', '!=', '0')
            ->where('status',  UrlStatus::WAITING_FOR_UUID)
            ->get();

        $packages = JDownloaderUtils::getPackagesInLinkGrabber();

        foreach ($urls as $url) {
            foreach ($packages as $package) {
                if($package->name === $url->package_name){
                    $url->update([
                        'status' => UrlStatus::INSERTED,
                        'package_uuid' => $package->uuid
                    ]);
                }
            }
        }

    }

    public static function addNewLinksTojDownloader() {
        $urls = Url::where('episode_id', '!=', '0')
            ->where('package_name',  null)
            ->where('status', UrlStatus::CREATED)
            ->get();

        foreach ($urls as $url) {
            $url->update([
                'package_name' => jDownloaderUtils::addLinkToGrabber($url->url),
                'status' => UrlStatus::WAITING_FOR_UUID
            ]);
        }

    }

}
