<?php

namespace App\Actions;

use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;

class Logout implements LogoutResponseContract
{
    public function toResponse($request): \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
    {
        session()->flash('logout', 'Sie sind nun abgemeldet.');
        return redirect('/?logout=1');
    }
}
