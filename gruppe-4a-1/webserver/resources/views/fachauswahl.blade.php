@component('components.site',['title' => 'Fachauswahl','style' => 'css/fachauswahl.css'])
    @component('components.layout')
        <div class="heading">
            <h1>Fachauswahl</h1>
            <div class="kategorien">
                <a href="/themen?fach=mathe" class="category-heading"><h3>Mathematik</h3>
                    <div><img src="images/math.png" alt="Mathe" class="fach-picture"></div>
                </a>
                <a href="/themen?fach=deutsch" class="category-heading"><h3>Deutsch</h3>
                    <div><img src="images/deutsch.png" alt="Deutsch" class="fach-picture"></div>
                </a>
                <a href="/themen?fach=englisch" class="category-heading"><h3>Englisch</h3>
                    <div><img src="images/englisch.png" alt="Englisch" class="fach-picture"></div>
                </a>
            </div>
        </div>
        <span>{{ $username ?? '' }}</span>
    @endcomponent()
@endcomponent()
