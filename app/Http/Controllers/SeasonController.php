<?php

namespace App\Http\Controllers;

use App\Models\Season;
use Illuminate\Http\Request;

class SeasonController extends Controller
{
    public function index()
    {
        return Season::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'show_id' => ['required', 'integer'],
            'name' => ['required'],
            'season_order_number' => ['required', 'integer'],
            'thumb_path' => ['required'],
        ]);

        return Season::create($request->validated());
    }

    public function show(Season $season)
    {
        return $season;
    }

    public function update(Request $request, Season $season)
    {
        $request->validate([
            'show_id' => ['required', 'integer'],
            'name' => ['required'],
            'season_order_number' => ['required', 'integer'],
            'thumb_path' => ['required'],
        ]);

        $season->update($request->validated());

        return $season;
    }

    public function destroy(Season $season)
    {
        $season->delete();
        return response()->json();
    }
}
