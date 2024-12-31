<?php

namespace App\Http\Controllers;

class FallbackController extends Controller
{
    public function index()
    {

        if (auth()->check()) {

        } else {
            return redirect()->route('login');
        }

    }
}
