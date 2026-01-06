@component('components.site',['title' => 'Kontakt','style' => 'css/kontakt.css'])
    @component('components.layout')
        <div>
            <h1>Kontaktformular</h1>
            <p class ="ueberschrift">Sie wollen uns kontaktieren? Verwenden Sie das untenstehende Kontaktformular.</p>
            <form class = "kontakt-body">
                <label for="name">Name</label>
                <input type="text" placeholder="Ihr Name"
                       id="name" class="input_text" required>

                <label for="passwort">E-Mail</label>
                <input type="text" placeholder="Ihre Mail-Adresse"
                       id="email" class="input_text" required>

                <label for="text">Nachricht</label>
                <input type="text" placeholder="Hier ihre Nachricht eintippen."
                       id="text" class="input_text" required>

                <input type="submit" value="Absenden"
                       class="input_absenden input_other">
            </form>
        </div>
    @endcomponent()
@endcomponent()
