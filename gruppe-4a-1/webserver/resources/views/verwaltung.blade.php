@component('components.site', ['title' => 'Aufgaben & Kategorien Verwaltung', 'style' => 'css/verwaltung.css'])
    @component('components.layout')
        <div class="verwaltung-container">
            <h1>Aufgaben & Kategorien Verwaltung</h1>

            {{-- Tabs für Aufgaben und Kategorien --}}
            <div class="tabs">
                <button class="tab-btn active" data-tab="aufgaben">Aufgaben</button>
                <button class="tab-btn" data-tab="kategorien">Kategorien</button>
            </div>

            {{-- Aufgaben Tab --}}
            <div id="aufgaben-tab" class="tab-content active">
                <div class="section-header">
                    <h2>Alle Aufgaben</h2>
                    <div class="filter-group">
                        <label for="filter-subject">Fach:</label>
                        <select id="filter-subject" onchange="filterTasks()">
                            <option value="">Alle Fächer</option>
                            @foreach($subjects as $subject)
                                <option
                                    value="{{ $subject->subjectName }}">{{ ucfirst($subject->subjectName) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if($tasks->isEmpty())
                    <p class="empty-message">Keine Aufgaben vorhanden.</p>
                @else
                    <div class="list-container">
                        <table class="data-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fach</th>
                                <th>Kategorie</th>
                                <th>Woche</th>
                                <th>Vorschau</th>
                                <th>Aktionen</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tasks as $task)
                                <tr data-subject="{{ $task->subjectName }}">
                                    <td>{{ $task->taskID }}</td>
                                    <td>{{ ucfirst($task->subjectName) }}</td>
                                    <td>{{ $task->category_name ?? '-' }}</td>
                                    <td>{{ $task->week ?? '-' }}</td>
                                    <td class="preview-cell">
                                        @php
                                            $content = is_array($task->content) ? $task->content : json_decode($task->content, true);
                                            $preview = '';
                                            if (isset($content['expression'])) {
                                                $preview = $content['expression'];
                                            } elseif (isset($content['text'])) {
                                                $preview = \Illuminate\Support\Str::limit($content['text'], 50);
                                            } elseif (isset($content['description'])) {
                                                $preview = \Illuminate\Support\Str::limit($content['description'], 50);
                                            }
                                        @endphp
                                        {{ $preview ?: 'Keine Vorschau' }}
                                    </td>
                                    <td>
                                        <form action="{{ route('task.destroy', $task->taskID) }}" method="POST"
                                              class="inline-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Möchten Sie diese Aufgabe wirklich löschen?')">
                                                Löschen
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Kategorien Tab --}}
            <div id="kategorien-tab" class="tab-content">
                <div class="section-header">
                    <h2>Alle Kategorien</h2>
                    <div class="filter-group">
                        <label for="filter-category-subject">Fach:</label>
                        <select id="filter-category-subject" onchange="filterCategories()">
                            <option value="">Alle Fächer</option>
                            @foreach($subjects as $subject)
                                <option
                                    value="{{ $subject->subjectName }}">{{ ucfirst($subject->subjectName) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if($categories->isEmpty())
                    <p class="empty-message">Keine Kategorien vorhanden.</p>
                @else
                    <div class="list-container">
                        <table class="data-table">
                            <thead>
                            <tr>
                                <th>Kategoriename</th>
                                <th>Fach</th>
                                <th>Aktionen</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $category)
                                <tr data-subject="{{ $category->subject_name }}">
                                    <td>{{ $category->category_name }}</td>
                                    <td>{{ ucfirst($category->subject_name) }}</td>
                                    <td>
                                        <form action="{{ route('category.destroy', $category->category_name) }}"
                                              method="POST" class="inline-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Möchten Sie diese Kategorie wirklich löschen? Alle zugehörigen Aufgaben werden gelöscht!')">
                                                Löschen
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <script>
            // Tab-Wechsel
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    // Alle Tabs deaktivieren
                    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

                    // Aktiven Tab setzen
                    this.classList.add('active');
                    document.getElementById(this.dataset.tab + '-tab').classList.add('active');
                });
            });

            // Aufgaben filtern
            function filterTasks() {
                const subject = document.getElementById('filter-subject').value;
                const rows = document.querySelectorAll('#aufgaben-tab tbody tr');

                rows.forEach(row => {
                    if (!subject || row.dataset.subject === subject) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Kategorien filtern
            function filterCategories() {
                const subject = document.getElementById('filter-category-subject').value;
                const rows = document.querySelectorAll('#kategorien-tab tbody tr');

                rows.forEach(row => {
                    if (!subject || row.dataset.subject === subject) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
        </script>
    @endcomponent
@endcomponent
