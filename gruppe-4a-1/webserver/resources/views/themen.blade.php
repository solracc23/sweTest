@component('components.site',['title' => 'Startseite','style' => 'css/themen.css'])
    @component('components.layout')

        <div class="thema-aufgaben">
            <div class="news">
                <h1>
                    @if($fach === 'mathe')
                        Mathematik Aufgaben
                    @elseif($fach === 'deutsch')
                        Deutsch Aufgaben
                    @elseif($fach === 'englisch')
                        Englisch Aufgaben
                    @else
                        Aufgaben
                    @endif
                    @if(isset($woche) && $woche)
                        – Woche {{ $woche }}
                    @endif
                </h1>
                @if(isset($woche) && $woche)
                    <div class="woche-filter-info">
                        <p>Zeigt nur Aufgaben aus Woche {{ $woche }}.</p>
                        <a href="{{ route('themen', ['fach' => $fach]) }}" class="filter-link">Alle Aufgaben anzeigen</a>
                        <a href="{{ route('wochenaufgaben') }}" class="filter-link">← Zurück zur Wochenübersicht</a>
                    </div>
                @endif
            </div>
            <div>
                <div class="themen">

                    <?php
                    $help = 0;
                    ?>

                    @foreach($themen as $thema)
                        @php
                            $hatAufgaben = ($fach === 'mathe' && isset($aufgabenProKategorie[$thema]) && count($aufgabenProKategorie[$thema]) > 0)
                                        || ($fach !== 'mathe' && isset($pdfsProKategorie[$thema]) && count($pdfsProKategorie[$thema]) > 0);
                        @endphp

                        @if($hatAufgaben)
                            <div class="thema-with-aufgabe" data-id="<?=$help?>">
                                <div class="thema" onclick="toggleAufgabe(this)">
                                    <p><?= Str::title($thema) ?></p>
                                    <img src="images/dropdown-arrow.svg"/>
                                </div>

                                <div class="aufgabe" style="display: none;">
                                    @if($fach === 'mathe')
                                        @if(isset($kategorieBeschreibungen[$thema]) && $kategorieBeschreibungen[$thema])
                                            <p class="kategorie-beschreibung">{{ $kategorieBeschreibungen[$thema] }}</p>
                                        @endif
                                        <div class="mathe-aufgaben">
                                            @foreach($aufgabenProKategorie[$thema] as $aufgabe)
                                                <div class="mathe-aufgabe"
                                                     data-id="{{ $aufgabe->getId() }}"
                                                     data-expression="{{ $aufgabe->getDisplayExpression() }}"
                                                     data-correct="{{ $aufgabe->getCorrectAnswer() }}">
                                                    <span class="aufgabe-expression">
                                                        <span
                                                            class="aufgabe-text">{{ $aufgabe->getDescription() }} </span>
                                                        <span
                                                            class="aufgabe-text">{{ implode(' ', $aufgabe->getTokensBeforeGap()) }}</span>
                                                        <input type="text" class="aufgabe-input" placeholder="?"/>
                                                        <span
                                                            class="aufgabe-text">{{ implode(' ', $aufgabe->getTokensAfterGap()) }}</span>
                                                    </span>
                                                    <button class="aufgabe-check" onclick="checkAnswer(this)">
                                                        ✓
                                                    </button>
                                                    <button class="aufgabe-loesung" onclick="showSolution(this)">
                                                        Lösung
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        {{-- PDF-Aufgaben für Deutsch/Englisch --}}
                                        @if(isset($pdfsProKategorie[$thema]))
                                            @foreach($pdfsProKategorie[$thema] as $index => $pdf)
                                                <div class="aufgaben-blätter" data-pdf-url="{{ asset('storage/' . $pdf->path) }}">
                                                    <div class="top-bar">
                                                        <button class="btn prev-page" data-pdf-index="{{ $help }}-{{ $index }}">
                                                            <img class="icon" src="/images/arrow-left.svg" alt="arrow-left">
                                                        </button>
                                                        <span class="page-info">
                                                            Seite <span class="page-num" data-pdf-index="{{ $help }}-{{ $index }}">1</span>
                                                            von <span class="page-count" data-pdf-index="{{ $help }}-{{ $index }}">?</span>
                                                        </span>
                                                        <button class="btn next-page" data-pdf-index="{{ $help }}-{{ $index }}">
                                                            <img class="icon" src="/images/arrow-right.svg" alt="arrow-right">
                                                        </button>
                                                        <div class="search-bar">
                                                            <input type="text"
                                                                   class="search-input"
                                                                   placeholder="In PDF suchen..."
                                                                   data-pdf-index="{{ $help }}-{{ $index }}">
                                                            <button class="search-btn btn" data-pdf-index="{{ $help }}-{{ $index }}">🔎</button>
                                                            <button class="search-prev btn" data-pdf-index="{{ $help }}-{{ $index }}"><img class="icon" src="/images/arrow-left.svg" alt="arrow-left"></button>
                                                            <button class="search-next btn" data-pdf-index="{{ $help }}-{{ $index }}"><img class="icon" src="/images/arrow-right.svg" alt="arrow-right"></button>
                                                            <span class="search-results" data-pdf-index="{{ $help }}-{{ $index }}"></span>
                                                        </div>
                                                        <div class="dwld"><a href="{{ asset('storage/' . $pdf->path) }}" download class="download">Download</a></div>
                                                    </div>
                                                    <div class="canvas-wrapper">
                                                        <canvas class="pdf-canvas" id="pdf-canvas-{{ $help }}-{{ $index }}"></canvas>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <p>Keine Aufgaben vorhanden.</p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                                <?php $help++; ?>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <script src="{{ asset('js/themen.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
        <script src="{{ asset('js/pdf.js') }}"></script>
        @if(request('section'))
            @push('scripts')
                <script src="{{ asset ('js/scrollSearch.js') }}"></script>
            @endpush
        @endif
    @endcomponent()
@endcomponent()
