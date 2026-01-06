<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        Auth::logout();
        session()->flash('register', 'Registrierung erfolgreich! Du kannst dich jetzt einloggen.');
        return redirect()->intended('/');
    }
}
