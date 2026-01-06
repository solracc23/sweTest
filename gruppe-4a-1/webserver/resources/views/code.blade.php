@component('components.site',['title' => 'Login','style' => 'css/code.css'])
    @component('components.layout')
    <div class="form-block">
        <h1>Verknüpfungscodes erstellen</h1>
        <form method="POST" action="{{ route('code.store') }}">

            @csrf
            <div>
                <label for="name">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="input_text">
                @error('name')<span class="error">{{ $message }}</span>@enderror
            </div>

            <div>
                <label for="role">Rolle</label>
                <select name="role" id="role" class="input_selection">
                    <option value="admin">Admin</option>
                    <option value="lehrer">Lehrer</option>
                    <option value="eltern">Eltern</option>
                    <option value="schüler">Schüler</option>
                </select>
            </div>
            <button type="submit" class="button">Erstellen</button>
        </form>
    </div>

    @endcomponent()
@endcomponent()
