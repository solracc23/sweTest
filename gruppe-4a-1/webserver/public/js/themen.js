function toggleAufgabe(themaElement) {
    const container = themaElement.closest('.thema-with-aufgabe');
    const aufgabe = container.querySelector('.aufgabe');

    if (aufgabe.style.display === 'none') {
        aufgabe.style.display = 'flex';
        themaElement.style.borderBottomLeftRadius = '0px';
        themaElement.style.borderBottomRightRadius = '0px';
    } else {
        aufgabe.style.display = 'none';
        themaElement.style.borderBottomLeftRadius = '15px';
        themaElement.style.borderBottomRightRadius = '15px';
    }
}

async function checkAnswer(button) {
    const container = button.closest('.mathe-aufgabe');
    const input = container.querySelector('.aufgabe-input');
    const userAnswer = input.value.trim();
    const correctAnswer = container.dataset.correct;

    // Leere Eingabe ignorieren
    if (!userAnswer) {
        return;
    }

    // Erst exakte Übereinstimmung prüfen
    if (userAnswer === correctAnswer) {
        markAsCorrect(container, input, button);
        return;
    }

    // Button deaktivieren während der Prüfung
    button.disabled = true;
    button.textContent = '⏳';

    try {
        // LLM API aufrufen
        const response = await fetch('/api/check-answer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_answer: userAnswer,
                correct_answer: correctAnswer
            })
        });

        const data = await response.json();

        if (data.result === true) {
            markAsCorrect(container, input, button);
        } else {
            markAsWrong(container, button);
        }
    } catch (error) {
        console.error('Fehler bei der LLM-Prüfung:', error);
        markAsWrong(container, button);
    }
}

function markAsCorrect(container, input, button) {
    container.style.backgroundColor = '#90EE90';
    input.disabled = true;
    button.disabled = true;
    button.textContent = '✓';

    // Aufgabe als abgeschlossen in der Datenbank speichern
    const taskId = container.dataset.id;
    if (taskId) {
        saveTaskCompletion(taskId);
    }
}

async function saveTaskCompletion(taskId) {
    try {
        const response = await fetch('/task/complete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                task_id: taskId
            })
        });

        const data = await response.json();
        if (!data.success) {
            console.error('Fehler beim Speichern der Aufgabe:', data.message);
        }
    } catch (error) {
        console.error('Fehler beim Speichern der Aufgabe:', error);
    }
}

function markAsWrong(container, button) {
    container.style.backgroundColor = '#FFB6C1';
    button.disabled = false;
    button.textContent = '✓';
    setTimeout(() => {
        container.style.backgroundColor = '';
    }, 1000);
}

function showSolution(button) {
    const container = button.closest('.mathe-aufgabe');
    const input = container.querySelector('.aufgabe-input');
    const checkButton = container.querySelector('.aufgabe-check');
    const correctAnswer = container.dataset.correct;

    // Prüfen ob bereits bestätigt wurde (data-confirmed)
    if (button.dataset.confirmed === 'true') {
        // Zweiter Klick: Lösung anzeigen
        input.value = correctAnswer;
        input.disabled = true;
        checkButton.disabled = true;
        button.disabled = true;
        button.textContent = '👁';
        container.style.backgroundColor = '#FFD700';
    } else {
        // Erster Klick: Bestätigung anfordern
        button.dataset.confirmed = 'true';
        button.textContent = 'Sicher?';
        button.style.backgroundColor = '#FFA500';

        // Nach 3 Sekunden zurücksetzen falls nicht geklickt
        setTimeout(() => {
            if (button.dataset.confirmed === 'true' && button.textContent === 'Sicher?') {
                button.dataset.confirmed = 'false';
                button.textContent = 'Lösung';
                button.style.backgroundColor = '';
            }
        }, 3000);
    }
}
