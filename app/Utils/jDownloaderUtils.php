<?php

namespace App\Utils;

use App\Connectors\JDownloaderConnector;
use App\Models\Settings;

class jDownloaderUtils {

    private static ?JDownloaderConnector $jDownloader = null;


    private static function checkJDownloaderObject(){
        if(!self::$jDownloader){
            $settings = Settings::first();
            self::$jDownloader = new JDownloaderConnector($settings->jdownloader_email, $settings->jdownloader_password, $settings->jdownloader_device);
        }
    }

    public static function getPackagesInLinkGrabber($uuids = []){
        self::checkJDownloaderObject();

        $opts = [
            "availableOfflineCount" => true,
            "availableOnlineCount" => true,
            "availableTempUnknownCount" => true,
            "bytesTotal" => true,
            //"packageUUIDs" => [1737116552286]

        ];

        if(count($uuids) > 0)
            $opts['packageUUIDs'] = $uuids;

         return json_decode(self::$jDownloader->callAction('/linkgrabberv2/queryPackages'
            ,$opts
        ))->data;

    }

    public static function getPackagesInDownload($uuids = []){
        self::checkJDownloaderObject();

        $opts = [
            "bytesLoaded" => true,
            "bytesTotal" => true,
            "childCount" => true,
            "comment" => true,
            "enabled" => true,
            "eta" => true,
            "finished" => true,
            "status" => true
            //"packageUUIDs" => [1737116552286]
        ];

        if(count($uuids) > 0)
            $opts['packageUUIDs'] = $uuids;

        return json_decode(self::$jDownloader->callAction('/downloadsV2/queryPackages'
            ,$opts
        ))->data;

    }

    public static function addLinkToGrabber($url){
        self::checkJDownloaderObject();
        $packageName = StringUtils::randomString(35);
        $res = self::$jDownloader->callAction('/linkgrabberv2/addLinks',[
            'autostart' => false,
            'links'=> $url,
            'packageName' => $packageName,
            'overwritePackagizerRules' => true
        ]);

        return $packageName;
    }

    public static function requestLinkGrabberRefresh($uuids) {
        $uuid = implode(",",$uuids);

        $res = json_decode(self::$jDownloader->callAction(
            "/linkgrabberv2/startOnlineStatusCheck?[]&[$uuid]"
        ));
    }

    public static function startDownloadPackage($packageUUID) {

        self::$jDownloader->callAction(
            "/linkgrabberv2/moveToDownloadlist?[]&[$packageUUID]"
        );

    }

}
