@php use App\Http\Controllers\ApiKeyController; @endphp
@component('components.site',['title' => 'Settings','style' => 'css/settings.css'])
    @component('components.layout')
        <script src="{{ asset('js/settings.js') }}"></script>
        <script src="{{ asset('js/scrollSearch.js') }}"></script>
        <script src="{{ asset('js/font-size.js') }}"></script>
        <script>
            function toggleDescriptionField() {
                const subjectSelect = document.getElementById('subject_name');
                const descriptionField = document.getElementById('description_field');
                if (subjectSelect && descriptionField) {
                    if (subjectSelect.value.toLowerCase() === 'mathe') {
                        descriptionField.style.display = 'block';
                    } else {
                        descriptionField.style.display = 'none';
                    }
                }
            }
        </script>

        @php
            $menuItems = [
                ['key' => 'profil', 'label' => 'Profil & Konto', 'roles' => ['admin','lehrer','eltern','schüler']],
                ['key' => 'darstellung', 'label' => 'Darstellung & Barrierefreiheit', 'roles' => ['admin','lehrer','eltern','schüler']],
                ['key' => 'fortschritt', 'label' => 'Lernfortschritt & Datenschutz', 'roles' => ['admin','lehrer','eltern','schüler']],
                ['key' => 'admin', 'label' => 'Administrator-Bereich', 'roles' => ['admin']],
                ['key' => 'lehrer', 'label' => 'Lehrer-Bereich', 'roles' => ['lehrer', 'admin']],
                ['key' => 'eltern', 'label' => 'Eltern-Bereich', 'roles' => ['eltern', 'admin']],
            ];
        @endphp

        <div class="main-container">
            <!--linkes Menü-->
            <div id="menu">
                @foreach($menuItems as $item)
                    @if(in_array(Auth::user()->role,$item['roles']))
                        <div class="menu-item {{ $loop->first ? 'active' : '' }}"
                             data-content="{{ $item['key'] }}">{{ $item['label'] }}</div>
                    @endif
                @endforeach
            </div>
            <!--rechter Inhalt-->
            <div id="content"></div>
        </div>

        <template id="profil-template" class="list">
            @if(Auth::user()->role === 'schüler')
                <div class="user-id-box">
                    <label>Dein Schüler-Code:</label>
                    <div class="user-id-display">
                        <span class="user-id-value">{{ Auth::user()->getStudentCode() }}</span>
                        <button type="button" class="copy-btn" onclick="copyUserId()">Kopieren</button>
                    </div>
                    <p class="user-id-hint">Teile diesen Code mit deinen Eltern, damit sie dich verknüpfen können.</p>
                </div>
                <hr style="margin: 20px 0;">
            @endif
            <p class="info">*Nur für Schüler sichtbar*</p>
        </template>

        <script>
            function copyUserId() {
                const userId = '{{ Auth::user()->getStudentCode() }}';
                navigator.clipboard.writeText(userId).then(() => {
                    const btn = document.querySelector('.copy-btn');
                    const originalText = btn.textContent;
                    btn.textContent = 'Kopiert!';
                    btn.style.backgroundColor = '#90EE90';
                    setTimeout(() => {
                        btn.textContent = originalText;
                        btn.style.backgroundColor = '';
                    }, 2000);
                });
            }
        </script>
        <template id="darstellung-template" class="list">
            <label id="text" >Hintergrundfarbe auswählen</label>
            <div class="box">
                <form method="POST" action="{{ route('theme.change') }}" class="auswahl">
                    @csrf
                    <button type="submit" name="theme" value="white">Hell</button>
                    <button type="submit" name="theme" value="dark">Dunkel</button>
                </form>
            </div>
            <label>Schriftgröße</label>
            <div class="box-schrift" id="text">
                <input type="range"
                       id="font-size-slider"
                       min="12" max="24" step="2"
                       value="{{ session('font_size', 16) }}"
                       data-update-url="{{ route('font-size.update') }}">

                <div class="font-size-display">
                    <span id="current-size">{{ session('font_size', 16) }}px</span>
                </div>
            </div>
            <div class="font-size-indicator">
                <span class="aKl">A</span>
                <span class="aGr">A</span>
            </div>
        </template>

        <template id="fortschritt-template">
            <div class="fortschritt-container">
                <h4>Dein Lernfortschritt in Mathematik</h4>
                <div class="progress-wrapper">
                    <progress value="{{ $progress['percent'] ?? 0 }}" max="100"></progress>
                    <span class="progress-text">{{ $progress['percent'] ?? 0 }}%</span>
                </div>
                <p class="progress-details">
                    Du hast <strong>{{ $progress['completed'] ?? 0 }}</strong> von <strong>{{ $progress['total'] ?? 0 }}</strong> Aufgaben richtig beantwortet.
                </p>
            </div>
        </template>

        <template id="admin-template">
            <h4>API Einstellungen</h4>
            <form action="{{ route('settings.llm-api-key') }}" method="POST"
                  style="display: flex; flex-direction: column; gap: 10px; max-width: 500px; margin-bottom: 30px;">
                @csrf
                <div>
                    <label for="llm_api_key">LLM API Key:</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="password" id="llm_api_key" name="llm_api_key"
                               style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                </div>
                <button type="submit" class="nav-button" style="align-self: flex-start;">API Key speichern</button>
            </form>
            <hr style="margin: 20px 0;">
            <h4>Verknüpfungscodes</h4>
            <a href="{{ route('code') }}" class="nav-button">Neuen Code erstellen</a>
            <p style="text-decoration: underline; font-size: medium; margin-top: 15px;">Verknüpfungscode-Übersicht</p>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Rolle</th>
                    <th>Genutzt?</th>
                    <th></th>
                </tr>
                @isset($codes)
                    @foreach($codes as $code)
                        <tr>
                            <td>{{$code->code_id}}</td>
                            <td>{{$code->name}}</td>
                            <td>{{$code->code}}</td>
                            <td>{{$code->role}}</td>
                            <td>{{$code->used ? 'Ja' : 'Nein'}}</td>
                            <td>
                                <form action="{{ route('code.destroy', $code->code_id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('Sind sie sicher, dass sie das Element löschen wollen?')"
                                            style="align-self: flex-start;">
                                        Löschen
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endisset
            </table>
        </template>

        <template id="lehrer-template">
            <h4>Neue Aufgabe erstellen</h4>
            <a href="{{ route('baukasten') }}" class="baukasten-link"> Hier gehts zum Aufgabenbaukasten für
                Mathematik</a> <br>
            <a href="{{ route('pdf.upload') }}" class="baukasten-link"> Hier gehts zum Aufgabenbaukasten für die
                restlichen Fächer</a>

            <hr style="margin: 20px 0;">

            <h4>Aufgaben & Kategorien verwalten</h4>
            <a href="{{ route('verwaltung') }}" class="baukasten-link">Zur Verwaltungsseite für Aufgaben und Kategorien</a>

            <hr style="margin: 20px 0;">

            <h4>Neue Kategorie hinzufügen</h4>
            <form action="{{ route('category.store') }}" method="POST"
                  style="display: flex; flex-direction: column; gap: 10px; max-width: 400px;">
                @csrf
                <div>
                    <label for="category_name">Kategoriename:</label>
                    <input type="text" id="category_name" name="category_name" required
                           style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label for="subject_name">Fach:</label>
                    <select id="subject_name" name="subject_name" required
                            style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"
                            onchange="toggleDescriptionField()">
                        <option value="">-- Fach auswählen --</option>
                        @isset($subjects)
                            @foreach($subjects as $subject)
                                <option
                                    value="{{ $subject->subjectName }}">{{ ucfirst($subject->subjectName) }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div id="description_field" style="display: none;">
                    <label for="category_description">Beschreibung:</label>
                    <textarea id="category_description" name="description" rows="3"
                              style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; resize: vertical;"
                              placeholder="Beschreibung der Kategorie (optional)"></textarea>
                </div>
                <button type="submit" class="nav-button" style="align-self: flex-start;">Hinzufügen</button>
            </form>
        </template>

        <template id="eltern-template" class="list">
            <h4>Kind verknüpfen</h4>
            <p>Geben Sie den Schüler-Code Ihres Kindes ein, um es mit Ihrem Konto zu verknüpfen.</p>
            <form action="{{ route('parent.link-child') }}" method="POST" class="link-child-form">
                @csrf
                <div class="link-child-input-group">
                    <input type="number" name="student_code" placeholder="Schüler-Code eingeben" required class="student-id-input">
                    <button type="submit" class="link-btn">Verknüpfen</button>
                </div>
            </form>

            <hr style="margin: 25px 0;">

            <h4>Verknüpfte Kinder & Lernfortschritt</h4>
            @if(isset($linkedChildren) && count($linkedChildren) > 0)
                <div class="linked-children-list">
                    @foreach($linkedChildren as $child)
                        <div class="linked-child-card">
                            <div class="child-header">
                                <div class="child-info">
                                    <span class="child-name">{{ $child['name'] }}</span>
                                </div>
                                <form action="{{ route('parent.unlink-child', $child['id']) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="unlink-btn" onclick="return confirm('Möchten Sie die Verknüpfung wirklich entfernen?')">Entfernen</button>
                                </form>
                            </div>
                            <div class="child-progress">
                                <div class="progress-label">Lernfortschritt Mathematik:</div>
                                <div class="progress-bar-container">
                                    <div class="progress-bar-fill" style="width: {{ $child['progress']['percent'] }}%;"></div>
                                </div>
                                <div class="progress-stats">
                                    <span class="progress-percent">{{ $child['progress']['percent'] }}%</span>
                                    <span class="progress-count">{{ $child['progress']['completed'] }} / {{ $child['progress']['total'] }} Aufgaben</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="no-children-hint">Noch keine Kinder verknüpft.</p>
            @endif
        </template>
    @endcomponent()
@endcomponent()
