<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function fachauswahl(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        if (Auth::check()) {

            $username = Auth::user()->name;


            return view('fachauswahl', ['username' => $username]);

        }else return view('auth.login');
    }
}
