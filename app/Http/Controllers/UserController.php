<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function avatar() {
        return Storage::response(auth()->user()->avatar);
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }

}
