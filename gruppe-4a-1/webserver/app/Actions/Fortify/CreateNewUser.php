<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Code;
use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Validation\ValidationException;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;
    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:20'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            ],
            [
                'name.required' => 'Der Name ist erforderlich.',
                'name.string' => 'Der Name muss aus Buchstaben bestehen!',
                'name.max' => 'Der Name ist zu lang. Es sind max. 20 Zeichen erlaubt.',

                'email.required' => 'Der Email ist erforderlich.',
                'email.email' => 'Bitte geben Sie eine gültige E-Mail Adresse ein.',
                'email.unique' => 'Diese E-Mail Adresse ist bereits vergeben.',

                'password.required' => 'Das Passwort ist erforderlich.',
                'password.min' => 'Das Passwort muss mindestens 8 Zeichen lang sein.',
                'password.confirmed' => 'Die Passwörter stimmen nicht überein.',
        ])->validate();

        $code = Code::where('code', $input['code'])->first();

        if(!$code | $code->used === 1)
        {
            throw ValidationException::withMessages([
                'code' => 'Der eingegebene Code ist ungültig.',
            ]);
        }

        //Der Code wurde genutzt.
        $code->used = true;
        $code->save();

        return User::create([

            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => $code->role,
        ]);
    }
}
