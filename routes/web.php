<?php

use App\Http\Controllers\FallbackController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsAdminMiddleware;
use App\Http\Middleware\LedgerSelectMiddleware;
use App\Http\Middleware\SetupMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Volt;

//--------------------------------------------------
//     AUTH
//--------------------------------------------------

Volt::route('/login', 'views.login')
    ->name('login');
Volt::route('/register', 'views.register')
    ->name('register');
Route::get('/',[FallbackController::class,'index'])->name('home');
Route::get('/language/{locale}', function ($locale) {
    setcookie('locale', $locale, time() + (86400 * 30), "/");
    App::setLocale($locale);
    return back();
})->name('language');

Route::middleware(['auth', 'verified' ])->group(fn() => [
    //Volt::route('/setup','views.ledgers.create')->name('setup'),

    // without ledger
//    Route::middleware(SetupMiddleware::class)->group(fn() => [
//
        Volt::route('/','views.dashboard')
            ->name('dashboard'),
        Volt::route('/profile','views.profile')
            ->name('profile'),
        Volt::route('/admin','views.admin')
            ->name('settings.admin'),
        Route::middleware(IsAdminMiddleware::class)->group(fn() => [
           Volt::route('/settings','views.site-settings')
               ->name('settings')
        ]),
//    ]),

    //get avatar
    Route::get('/avatar', [UserController::class,'avatar'])->name('avatar'),
    //logout
    Route::get('/logout',[UserController::class,'logout'])->name('logout'),

]);



if(config('app.env') === 'local'){
    Route::view('/sandbox','layouts.sandbox')->name('sandbox');
}

// When ledger is not selected, or other route is passed, select first ledger and redirect or redirect to setup page
Route::fallback([FallbackController::class,'index']);
