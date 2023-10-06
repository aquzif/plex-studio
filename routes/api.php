<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\TvDBController;
use App\Http\Controllers\UrlController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(fn() => [
    //Route::middleware('auth:sanctum')->post('/register','register');
    Route::post('/register','register'),
    Route::post('/login','login'),
    Route::middleware('auth:sanctum')->post('/logout','logout'),
]);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function(){
    Route::resource('show', ShowController::class)->except(['create', 'edit']);
    Route::resource('season', SeasonController::class)->except(['create', 'edit']);
    Route::get('tvdb/search', [TvDBController::class,'search']);
    Route::get('tvdb/show/{id}/seasons', [TvDBController::class,'seasons']);
    Route::resource('episode', EpisodeController::class)->except(['create', 'edit']);
    Route::resource('url', UrlController::class)->except(['create', 'edit']);


});



