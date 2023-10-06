<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    public function index()
    {
        return Url::all();
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'url' => ['required'],
            'movie_id' => ['required_if:episode_id,null'],
            'episode_id' => ['required_if:movie_id,null'],
        ]);

        if(!isset($fields['movie_id']))
            $fields['movie_id'] = 0;
        if(!isset($fields['episode_id']))
            $fields['episode_id'] = 0;

        $fields['downloaded'] = false;
        $fields['invalid'] = false;

        return Url::firstOrCreate([
            'url' => $fields['url'],
            'movie_id' => $fields['movie_id'],
            'episode_id' => $fields['episode_id']
        ],$fields);
    }

    public function show(Url $url)
    {
        return $url;
    }

    public function update(Request $request, Url $url)
    {

        $fields = $request->validate([
            'url' => ['required'],
            'downloaded' => ['boolean'],
            'invalid' => ['boolean'],
        ]);

        $url->update($fields);


        return $url;
    }

    public function destroy(Url $url)
    {
        $url->delete();

        return response()->json();
    }
}
