@component('components.site',['title' => 'Aufgabenbaukasten - Mathe 5. Klasse','style' => 'css/baukasten.css'])
    @component('components.layout')
        <script>
            window.baukastenConfig = {
                serializeUrl: '{{ route("task.serialize") }}',
                csrfToken: '{{ csrf_token() }}',
                categories: @json($categories->pluck('category_name'))
            };
        </script>
        <script src="{{ asset('js/baukasten.js') }}"></script>
        <div class="baukasten-container">
            <div class="baukasten-header">
                <h1>Aufgaben&shybaukasten</h1>
                <p class="subtitle">Erstelle Mathe-Aufgaben für die 5. Klasse</p>
            </div>

            <div class="task-editor">
                <!-- Beschreibung -->
                <div class="editor-section">
                    <label for="description-input">Beschreibung <span class="optional-badge">(optional)</span></label>
                    <p class="hint">Eine kurze Beschreibung oder Anweisung für die Aufgabe</p>
                    <input
                        type="text"
                        id="description-input"
                        class="description-input"
                        placeholder="z.B. Berechne das Ergebnis"
                        autocomplete="off"
                    >
                </div>

                <!-- Eingabe der Aufgabe -->
                <div class="editor-section">
                    <label for="expression-input">Aufgabe eingeben</label>
                    <p class="hint">Zahlen und Operatoren mit Leerzeichen trennen (z.B. "4 * 3 = 12")</p>
                    <input
                        type="text"
                        id="expression-input"
                        class="expression-input"
                        placeholder="4 * 3 = 12"
                        autocomplete="off"
                    >
                </div>

                <!-- Token-Auswahl -->
                <div class="editor-section">
                    <label>Token auswählen (Auf die Lücke klicken)</label>
                    <p class="hint">Den Teil der Aufgabe wählen, den der Schüler ausfüllen soll</p>
                    <div id="token-display" class="token-display empty">

                    </div>
                </div>

                <!-- Kategorie-Auswahl -->
                <div class="editor-section">
                    <label for="category-select">Kategorie</label>
                    <p class="hint">Die Kategorie der Mathe-Aufgabe</p>
                    <select id="category-select" class="category-select">
                        <option value="">— Kategorie wählen —</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_name }}">{{ ucfirst($category->category_name) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Wochen-Zuordnung (optional) -->
                <div class="editor-section">
                    <label for="week-input">Woche zuordnen <span class="optional-badge">(optional)</span></label>
                    <p class="hint">Die Kalenderwoche, in der diese Aufgabe erscheinen soll (1-52)</p>
                    <input
                        type="number"
                        id="week-input"
                        class="week-input"
                        placeholder="—"
                        min="1"
                        max="52"
                    >
                </div>

                <!-- Vorschau -->
                <div class="editor-section">
                    <div class="preview-section">
                        <h3>Vorschau für die Schüler</h3>
                        <div id="preview-expression" class="preview-expression">
                            —
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="button-group">
                    <button type="button" id="btn-reset" class="btn btn-secondary">
                        Zurücksetzen
                    </button>
                    <button type="button" id="btn-submit" class="btn btn-primary" disabled>
                        Fertig
                    </button>
                </div>
            </div>

            <!-- Ergebnis nach Serialisierung -->
            <div id="result-section" class="result-section">
                <div class="result-card">
                    <h3><span class="checkmark">✓</span> Aufgabe erfolgreich erstellt!</h3>
                    <div class="task-info">
                        <div class="task-info-item">
                            <div class="label">Aufgabe</div>
                            <div class="value" id="result-expression">—</div>
                        </div>
                        <div class="task-info-item">
                            <div class="label">Lücke (Antwort)</div>
                            <div class="value" id="result-answer">—</div>
                        </div>
                        <div class="task-info-item">
                            <div class="label">Anzeige für Schüler</div>
                            <div class="value" id="result-display">—</div>
                        </div>
                        <div class="task-info-item">
                            <div class="label">Kategorie</div>
                            <div class="value" id="result-category">—</div>
                        </div>
                        <div class="task-info-item">
                            <div class="label">Woche</div>
                            <div class="value" id="result-week">—</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endcomponent()
@endcomponent()
