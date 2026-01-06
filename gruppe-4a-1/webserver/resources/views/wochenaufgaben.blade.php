@component('components.site',['title' => 'Wochenaufgaben','style' => 'css/wochenaufgaben.css'])
    @component('components.layout')
        <div class="content-area heading">
            <h1>Wochenaufgaben</h1>
            <div class="kategorien">
                @forelse($wochenProFach as $fach => $wochen)
                    <div class="thema">
                        <div class="themaHeading">
                            <a>{{ ucfirst($fach) }}</a>
                        </div>
                        @foreach($wochen as $index => $woche)
                            <div class="{{ $loop->last ? 'aufgabelast' : 'aufgabe' }}">
                                <a href="{{ route('themen', ['fach' => $fach, 'woche' => $woche]) }}">
                                    Woche {{ $woche }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @empty
                    <div class="keine-aufgaben">
                        <p>Es sind noch keine Wochenaufgaben vorhanden.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endcomponent()
@endcomponent()
