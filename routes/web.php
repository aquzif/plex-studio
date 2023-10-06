<?php

use App\Http\Controllers\SeriesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/xt7', function (Request $request){
   $fields = $request->validate([
       'url' => 'required'
   ]);

   return view('xt7',[
       'url' => $fields['url']
   ]);

});

Route::get('/images/{image}', function ($image) {
    $path = storage_path('app/images/'.$image);
    if(!File::exists($path)) abort(404);
    $file = File::get($path);
    $type = File::mimeType($path);
    $response = Response::make($file,200);
    $response->header("Content-Type",$type);

    //make image cacheable for 30 days
    $response->header("Cache-Control","max-age=2592000, public");

    return $response;
});

Route::fallback(function () {
    return view('react');
});
