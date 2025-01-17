<?php

namespace App\Enums;

enum UrlStatus: string {

    case CREATED = 'CREATED'; //url has been freshly created, is not yet inserted into jDownloader
    case WAITING_FOR_UUID = 'WAITING_FOR_UUID'; //url has been inserted into jDownloader, but we are waiting for the UUID
    case INSERTED = 'INSERTED'; //url has been inserted into jDownloader
    case READY = 'READY'; //url is in the jDownload, and has been validated
    case UNAVAILABLE = 'UNAVAILABLE'; //when url has ben checked is unavailable
    case WAITING_FOR_START_DOWNLOAD = 'WAITING_FOR_START_DOWNLOAD'; //when url is marked to download and insert into jDownloader
    CASE DOWNLOADING = 'DOWNLOADING'; //when url downloading in jDownloader
    CASE COMPLETED = 'COMPLETED'; //when url downloading in JDownloader is completed

    public function description(): string {
        return match ($this) {
             self::CREATED => 'url has been freshly created, is not yet inserted into jDownloader',
             self::WAITING_FOR_UUID => 'has been inserted into jDownloader, but we are waiting for the UUID',
             self::INSERTED => 'has been inserted into jDownloader',
             self::READY => 'is in the jDownload, and has been validated',
             self::UNAVAILABLE => 'url has ben checked is unavailable',
             self::WAITING_FOR_START_DOWNLOAD => 'url is marked to download and insert into jDownloader',
             self::DOWNLOADING => 'url downloading in jDownloader',
             self::COMPLETED => 'url downloading in JDownloader is completed',

        };
    }


}
