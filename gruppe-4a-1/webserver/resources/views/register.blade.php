@component('components.site',['title' => 'Login','style' => 'css/login.css'])
    @component('components.layout')
        <div class="form-block">
            <h1>Registrieren</h1>
            <form method="POST" action="{{ route('register') }}">

                @csrf
                <div>
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="input_text">
                    @error('name')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="input_text">
                    @error('email')<span class="error">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label for="password">Passwort</label>
                    <input type="password" name="password" id="password" required class="input_text">
                    @error('password')<span class="error">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label for="password_confirmation">Passwort bestätigen</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required class="input_text">
                </div>

                <div>
                    <label for="code">Verknüpfungscode</label>
                    <input type="text" name="code" id="code" required class="input_text">
                    @error('code')<span class="error">{{ $message }}</span>@enderror
                </div>

                <button type="submit" class="button">Registrieren</button>

                <div class="link">
                    <a href="/login">Einloggen</a>
                    <a href="/register">Passwort vergessen?</a>
                </div>
            </form>
        </div>

    @endcomponent()
@endcomponent()
