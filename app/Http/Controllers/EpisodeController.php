<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    public function index()
    {
        return Episode::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'season_id' => ['required'],
            'show_id' => ['required'],
            'tvdb_id' => ['required'],
            'downloaded' => ['required'],
        ]);

        return Episode::create($request->validated());
    }

    public function show(Episode $episode)
    {
        return $episode;
    }

    public function update(Request $request, Episode $episode)
    {
        $fields = $request->validate([
            'downloaded' => ['boolean'],
        ]);

        $episode->update($fields);

        return $episode;
    }

    public function destroy(Episode $episode)
    {
        $episode->delete();

        return response()->json();
    }
}
