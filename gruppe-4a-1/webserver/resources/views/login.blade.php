<form method="POST" action="{{ route('login') }}">
    @csrf
    <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required autofocus>
    </div>
    <div>
        <label for="password">Passwort</label>
        <input type="password" name="password" id="password" required>
    </div>
    <div>
        <input type="checkbox" name="remember"> Remember me
    </div>
    <button type="submit">Login</button>
</form>
