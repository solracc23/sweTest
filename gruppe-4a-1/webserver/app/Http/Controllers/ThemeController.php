<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ThemeController extends Controller
{
    public function change(Request $request): \Illuminate\Http\RedirectResponse
    {
        $theme = $request->input('theme');
        Session::put('theme', $theme);

        return redirect()->back();
    }
}
