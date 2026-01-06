document.addEventListener('DOMContentLoaded', function () {
    const descriptionInput = document.getElementById('description-input');
    const expressionInput = document.getElementById('expression-input');
    const tokenDisplay = document.getElementById('token-display');
    const previewExpression = document.getElementById('preview-expression');
    const categorySelect = document.getElementById('category-select');
    const weekInput = document.getElementById('week-input');
    const btnSubmit = document.getElementById('btn-submit');
    const btnReset = document.getElementById('btn-reset');
    const resultSection = document.getElementById('result-section');

    let tokens = [];
    let selectedGapIndex = -1;

    // Operatoren für spezielle Formatierung
    const operators = ['+', '-', '*', '/', '=', '(', ')', '^'];

    // Kategorie-Auswahl Event
    categorySelect.addEventListener('change', function () {
        updateSubmitButton();
    });

    // Token-Erstellung bei Eingabe
    expressionInput.addEventListener('input', function () {
        const value = this.value.trim();

        if (!value) {
            tokens = [];
            selectedGapIndex = -1;
            renderTokens();
            updatePreview();
            updateSubmitButton();
            return;
        }

        // Aufteilen nach Leerzeichen
        tokens = value.split(/\s+/).filter(t => t.length > 0);
        selectedGapIndex = -1;
        renderTokens();
        updatePreview();
        updateSubmitButton();
    });

    // Tokens rendern
    function renderTokens() {
        if (tokens.length === 0) {
            tokenDisplay.innerHTML = ' ';
            tokenDisplay.classList.add('empty');
            return;
        }

        tokenDisplay.classList.remove('empty');
        tokenDisplay.innerHTML = tokens.map((token, index) => {
            const isOperator = operators.includes(token);
            const isSelected = index === selectedGapIndex;
            const classes = ['token'];
            if (isOperator) classes.push('operator');
            if (isSelected) classes.push('selected');

            return `<span class="${classes.join(' ')}" data-index="${index}">${escapeHtml(token)}</span>`;
        }).join('');

        // Event-Listener für Token-Klicks
        tokenDisplay.querySelectorAll('.token').forEach(tokenEl => {
            tokenEl.addEventListener('click', function () {
                const index = parseInt(this.dataset.index);
                selectGap(index);
            });
        });
    }

    // Lücke auswählen
    function selectGap(index) {
        selectedGapIndex = index;
        renderTokens();
        updatePreview();
        updateSubmitButton();
    }

    // Vorschau aktualisieren
    function updatePreview() {
        if (tokens.length === 0) {
            previewExpression.innerHTML = '—';
            return;
        }

        const displayTokens = tokens.map((token, index) => {
            if (index === selectedGapIndex) {
                return '<span class="gap">___</span>';
            }
            return escapeHtml(token);
        });

        previewExpression.innerHTML = displayTokens.join(' ');
    }

    // Submit-Button aktivieren/deaktivieren
    function updateSubmitButton() {
        btnSubmit.disabled = tokens.length === 0 || selectedGapIndex === -1 || !categorySelect.value;
    }

    // HTML escapen
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Zurücksetzen
    btnReset.addEventListener('click', function () {
        descriptionInput.value = '';
        expressionInput.value = '';
        categorySelect.value = '';
        weekInput.value = '';
        tokens = [];
        selectedGapIndex = -1;
        renderTokens();
        updatePreview();
        updateSubmitButton();
        resultSection.classList.remove('visible');
    });

    // Absenden und Serialisieren
    btnSubmit.addEventListener('click', async function () {
        if (tokens.length === 0 || selectedGapIndex === -1) return;

        btnSubmit.disabled = true;
        btnSubmit.textContent = 'Wird erstellt...';

        try {
            const response = await fetch(window.baukastenConfig.serializeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.baukastenConfig.csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    description: descriptionInput.value.trim() || null,
                    expression: tokens.join(' '),
                    tokens: tokens,
                    gap_index: selectedGapIndex,
                    category_name: categorySelect.value,
                    week: weekInput.value ? parseInt(weekInput.value) : null,
                }),
            });

            const data = await response.json();

            if (data.success) {
                // display_expression lokal berechnen
                const displayTokens = data.task.tokens.map((token, index) =>
                    index === data.task.gap_index ? '___' : token
                );
                const displayExpression = displayTokens.join(' ');

                // Ergebnis anzeigen
                document.getElementById('result-expression').textContent = data.task.expression;
                document.getElementById('result-answer').textContent = data.task.correct_answer;
                document.getElementById('result-display').textContent = displayExpression;
                document.getElementById('result-category').textContent = categorySelect.options[categorySelect.selectedIndex].text;
                document.getElementById('result-week').textContent = data.task.week ? 'Woche ' + data.task.week : 'Keine Woche zugeordnet';

                resultSection.classList.add('visible');
                resultSection.scrollIntoView({behavior: 'smooth', block: 'start'});
            } else {
                alert('Fehler: ' + (data.error || 'Unbekannter Fehler'));
            }
        } catch (error) {
            console.error('Fehler:', error);
            alert('Ein Fehler ist aufgetreten. Bitte versuche es erneut.');
        } finally {
            btnSubmit.disabled = false;
            btnSubmit.textContent = 'Fertig';
            updateSubmitButton();
        }
    });
});
