<?php

namespace App\Actions;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class Login implements LoginResponseContract
{
    public function toResponse($request): \Symfony\Component\HttpFoundation\Response|\Illuminate\Http\RedirectResponse
    {
        session()->flash('success', 'Login erfolgreich, Willkommen ' . Auth::user()->name . '.');
        return redirect()->intended('/');
    }
}
