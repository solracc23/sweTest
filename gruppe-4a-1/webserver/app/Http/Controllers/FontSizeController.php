<?php
// App\Http\Controllers\FontSizeController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FontSizeController extends Controller
{
    public function update(Request $request)
    {

        $validated = $request->validate([
            'font_size' => 'required|integer|min:12|max:24'
        ]);


        $newSize = $validated['font_size'];


        session(['font_size' => $newSize]);


        $cookie = cookie('font_size', $newSize, 60 * 24 * 30);


        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'font_size' => $newSize,
                'css_value' => $newSize . 'px',
                'message' => 'Schriftgröße aktualisiert'
            ])->withCookie($cookie);
        }


        return back()->withCookie($cookie);
    }

}
