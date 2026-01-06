@component('components.site',['title' => 'Login','style' => 'css/login.css'])
    @component('components.layout')
        <div>
            <h1>Login</h1>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <label for="benutzername">E-Mail</label>
                <input type="text" name="email"
                       placeholder="Email" id="email"
                       class="input_text" required>
                @error('email')<span class="error">{{ $message }}</span>@enderror


                <label for="passwort">Passwort</label>
                <input type="password" name="password" placeholder="Passwort"
                       id="password" class="input_text" required>
                @error('password')<span class="error">{{ $message }}</span>@enderror

                <input type="submit" value="Einloggen" class="button">

                <div class="link">
                    <a href="/register">Registrieren</a>
                    <a href="/register">Passwort vergessen?</a>
                </div>
            </form>
        </div>
    @endcomponent()
@endcomponent()
