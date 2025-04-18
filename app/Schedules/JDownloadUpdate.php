<?php

namespace App\Schedules;

use App\Connectors\JDownloaderConnector;
use App\Enums\UrlStatus;
use App\Models\Settings;
use App\Models\Show;
use App\Models\Url;
use App\Utils\FileUtils;
use App\Utils\JDownloaderUtils;
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

        //echo  'JDownloader update started' with timestamp
        echo 'JDownloader update started at: '.now()->format('Y-m-d H:i:s')."\n";
        JDownloaderUtils::refreshAccounts();
        echo 'Adding links to jDownloader at '.now()->format('Y-m-d H:i:s')."\n";
        self::addNewLinksTojDownloader();
        echo 'Fetching UUIDs to URLs at '.now()->format('Y-m-d H:i:s')."\n";
        self::fetchUUIDsToUrls();
        echo 'Merging zip packages at '.now()->format('Y-m-d H:i:s')."\n";
        self::mergeZipPackages();
        echo 'Fetching UUIDs at '.now()->format('Y-m-d H:i:s')."\n";
        self::fetchUUIDsToUrls();
        echo 'Checking links avalability at '.now()->format('Y-m-d H:i:s')."\n";
        self::checkLinksAvalability();
        echo 'Checking status of urls in download at '.now()->format('Y-m-d H:i:s')."\n";
        self::getStatusOfUrlsInDownload();
        echo 'Requesting check links avalability at '.now()->format('Y-m-d H:i:s')."\n";
        self::requestCheckLinksAvalability();
        echo 'Starting downloading links at '.now()->format('Y-m-d H:i:s')."\n";
        self::startDownloadingLinks();
        echo 'Moving downloaded files to plex at '.now()->format('Y-m-d H:i:s')."\n";
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

            $urls = Url::where('package_name',$package->name)->get();

            if(!$urls)
                continue;

            foreach ($urls as $url) {
                $url->update([
                    'download_status' => $package->status
                ]);
            }


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
                    ->orWhere('status', UrlStatus::UNAVAILABLE);
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

        JDownloaderUtils::requestLinkGrabberRefresh($uuids);


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

            $show =
                $url->episode_id === "0"
                ? Show::where('id',$url->movie_id)->first()
                : $url->episode()->first()->show()->first();




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
                    $dirPath = config('plex.movies_dir');

                    $fileName = explode('/',$file);
                    $fileName = $fileName[count($fileName)-1];

                }else{
                    $season = $url->episode()->first()->season()->first();
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


                }

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

    public static function fetchUUIDsToUrls() {
        $urls = Url::get();

        echo ' - Getting packages at: '.now()->format('Y-m-d H:i:s')."\n";
        $packages = JDownloaderUtils::getPackagesInLinkGrabber();
        echo ' - Getting links at: '.now()->format('Y-m-d H:i:s')."\n";
        $links = JDownloaderUtils::getLinksInLinkGrabber();

//        dd($packages,$links,$urls->toArray());

        echo ' - Updating urls at: '.now()->format('Y-m-d H:i:s')."\n";
        echo count($urls)+count($packages)+count($links)."\n";
        foreach ($urls as $url) {
            foreach ($packages as $package) {
                foreach ($links as $link) {
                    if(
                        //$package->name === $url->package_name
                        $package->uuid == $link->packageUUID
                        && \Str::lower($link->url) == \Str::lower($url->url)
                    ){
                        $url->update([
                            'status' =>
                                UrlStatus::from($url->status) === UrlStatus::WAITING_FOR_UUID
                                    ? UrlStatus::INSERTED : $url->status,
                            'package_uuid' => $package->uuid
                        ]);
                    }
                }
            }
        }

    }

    public static function mergeZipPackages(){
        $links = JDownloaderUtils::getLinksInLinkGrabber();
        $packages = JDownloaderUtils::getPackagesInLinkGrabber();

        $mergePackages = [];

        foreach ($links as $link) {
            foreach ($packages as $package) {

                if($link->packageUUID !== $package->uuid)
                    continue;

                $ext = FileUtils::getExtensionFromName($link->url);

                if($ext !== 'zip' && $ext !== 'rar')
                    continue;

                $url = Url::where('url', $link->url)->first();

                if(!FileUtils::isPartArchive($link->url))
                    continue;

                $nameWithoutPart = explode('/',$link->url);
                $nameWithoutPart = $nameWithoutPart[count($nameWithoutPart)-1];
                $nameWithoutPart = explode('.part',$nameWithoutPart)[0];
                $nameWithoutPart = $nameWithoutPart.'.'.$ext;
                if(!isset($mergePackages[$nameWithoutPart])){
                    $mergePackages[$nameWithoutPart] = [
                        'package_name' => $url->package_name,
                        'package_uuids' => [$url->package_uuid],
                        'url_ids' => [$url->id]
                    ];
                }else{

                    $mergePackages[$nameWithoutPart]['package_uuids'][] = $url->package_uuid;
                    $mergePackages[$nameWithoutPart]['url_ids'][] = $url->id;
                    $mergePackages[$nameWithoutPart]['package_uuids']
                        = array_unique($mergePackages[$nameWithoutPart]['package_uuids']);

//                    JDownloaderUtils::mergeDownloadPackages(
//                        $mergePackages[$nameWithoutPart]['package_name'],[
//                            $mergePackages[$nameWithoutPart]['package_uuid'],
//                            $url->package_uuid
//                        ]
//                    );
//
//                    $url->update([
//                        'package_name' => $mergePackages[$nameWithoutPart]['package_name'],
//                        'status' => UrlStatus::WAITING_FOR_UUID
//                    ]);

                }



            }
        }

        foreach ($mergePackages as $mergePackage) {

            if(count($mergePackage['package_uuids']) <= 1)
                continue;

            JDownloaderUtils::mergeDownloadPackages(
                $mergePackage['package_name'],
                $mergePackage['package_uuids']
            );

            foreach ($mergePackage['url_ids'] as $urlId) {
                $url = Url::find($urlId);
                $url->update([
                    'package_name' => $mergePackage['package_name'],
                    'package_uuid' => null,
                    'status' => UrlStatus::WAITING_FOR_UUID
                ]);
            }
        }


    }

    public static function addNewLinksTojDownloader() {
        $urls = Url::where('package_name',  null)
            ->where('status', UrlStatus::CREATED)
            ->get();




        foreach ($urls as $url) {
            $packageName = JDownloaderUtils::addLinkToGrabber($url->url);
            $url->update([
                'package_name' => $packageName,
                'status' => UrlStatus::WAITING_FOR_UUID
            ]);

        }
    }

}
