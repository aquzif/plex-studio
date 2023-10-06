<?php

namespace App\Utils;

use Illuminate\Support\Facades\Http;

class EpisodateUtils {

    public static function search($search) {

        $search = urlencode($search);

        return Http::get("search?q=$search&page=1")->json();

    }

}
