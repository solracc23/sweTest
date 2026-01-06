@component('components.site',['title' => 'PDF Upload','style' => 'css/pdf-upload.css'])
    @component('components.layout')
        <div class="upload-container">
            <div class="upload-header">
                <h1>PDF Upload</h1>
                <p class="subtitle">Lade Arbeitsblätter als PDF hoch</p>
            </div>

            <form action="{{ route('pdf.store') }}" method="POST" enctype="multipart/form-data" id="pdf-upload-form">
                @csrf

                <!-- Drag & Drop Zone -->
                <div class="editor-section">
                    <label>PDF-Datei hochladen</label>
                    <div class="dropzone" id="dropzone">
                        <input type="file" name="pdf" id="pdf-input" accept=".pdf" hidden>
                        <div class="dropzone-content">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                            <p class="dropzone-text">PDF hierher ziehen oder <span
                                    class="browse-link">durchsuchen</span></p>
                            <p class="dropzone-hint">Nur PDF-Dateien erlaubt</p>
                        </div>
                        <div class="file-preview" id="file-preview" style="display: none;">
                            <span class="file-name" id="file-name"></span>
                            <button type="button" class="remove-file" id="remove-file">&times;</button>
                        </div>
                    </div>
                </div>

                <!-- Fach-Auswahl -->
                <div class="editor-section">
                    <label for="subject-select">Fach</label>
                    <select id="subject-select" name="subject" class="form-select" required>
                        <option value="">— Fach wählen —</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->subjectName }}">{{ ucfirst($subject->subjectName) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Kategorie-Auswahl -->
                <div class="editor-section">
                    <label for="category-select">Kategorie</label>
                    <select id="category-select" name="category" class="form-select" required disabled>
                        <option value="">— Zuerst Fach wählen —</option>
                    </select>
                </div>

                <!-- Wochen-Zuordnung (optional) -->
                <div class="editor-section">
                    <label for="week-input">Woche zuordnen <span class="optional-badge">(optional)</span></label>
                    <input
                        type="number"
                        id="week-input"
                        name="week"
                        class="form-input"
                        placeholder="—"
                        min="1"
                        max="52"
                    >
                </div>

                <!-- Submit Button -->
                <div class="button-group">
                    <button type="submit" class="btn btn-primary" id="btn-submit" disabled>
                        Hochladen
                    </button>
                </div>
            </form>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const dropzone = document.getElementById('dropzone');
                const fileInput = document.getElementById('pdf-input');
                const filePreview = document.getElementById('file-preview');
                const fileName = document.getElementById('file-name');
                const removeFile = document.getElementById('remove-file');
                const submitBtn = document.getElementById('btn-submit');
                const subjectSelect = document.getElementById('subject-select');
                const categorySelect = document.getElementById('category-select');

                // Kategorien nach Fach
                const categoriesBySubject = @json($categories->groupBy('subject_name'));

                // Click to browse
                dropzone.addEventListener('click', () => fileInput.click());

                // Drag events
                ['dragenter', 'dragover'].forEach(event => {
                    dropzone.addEventListener(event, (e) => {
                        e.preventDefault();
                        dropzone.classList.add('dragover');
                    });
                });

                ['dragleave', 'drop'].forEach(event => {
                    dropzone.addEventListener(event, (e) => {
                        e.preventDefault();
                        dropzone.classList.remove('dragover');
                    });
                });

                // Drop handler
                dropzone.addEventListener('drop', (e) => {
                    const files = e.dataTransfer.files;
                    if (files.length && files[0].type === 'application/pdf') {
                        fileInput.files = files;
                        showFile(files[0]);
                    }
                });

                // File input change
                fileInput.addEventListener('change', () => {
                    if (fileInput.files.length) {
                        showFile(fileInput.files[0]);
                    }
                });

                // Remove file
                removeFile.addEventListener('click', (e) => {
                    e.stopPropagation();
                    fileInput.value = '';
                    filePreview.style.display = 'none';
                    dropzone.querySelector('.dropzone-content').style.display = 'flex';
                    validateForm();
                });

                function showFile(file) {
                    fileName.textContent = file.name;
                    filePreview.style.display = 'flex';
                    dropzone.querySelector('.dropzone-content').style.display = 'none';
                    validateForm();
                }

                function validateForm() {
                    const hasFile = fileInput.files.length > 0;
                    const hasSubject = subjectSelect.value;
                    const hasCategory = categorySelect.value;
                    submitBtn.disabled = !(hasFile && hasSubject && hasCategory);
                }

                // Fach-Änderung -> Kategorien aktualisieren
                subjectSelect.addEventListener('change', function () {
                    const subject = this.value;
                    categorySelect.innerHTML = '<option value="">— Kategorie wählen —</option>';

                    if (subject && categoriesBySubject[subject]) {
                        categorySelect.disabled = false;
                        categoriesBySubject[subject].forEach(cat => {
                            const option = document.createElement('option');
                            option.value = cat.category_name;
                            option.textContent = cat.category_name.charAt(0).toUpperCase() + cat.category_name.slice(1);
                            categorySelect.appendChild(option);
                        });
                    } else {
                        categorySelect.disabled = true;
                        categorySelect.innerHTML = '<option value="">— Zuerst Kategorie zum Fach hinzufügen —</option>';
                    }
                    validateForm();
                });

                categorySelect.addEventListener('change', validateForm);
            });
        </script>
    @endcomponent()
@endcomponent()
