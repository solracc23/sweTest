

class FontSizeManager {
    constructor() {
        this.csrfToken = null;
        this.init();
    }

    init() {
        console.log('FontSize initialisiert');


        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!this.csrfToken) {
            console.error('CSRF Token nicht gefunden');
        }

        document.addEventListener('input', (e) => {
            if (e.target.id === 'font-size-slider') {
                this.updatePreview(e.target.value);
            }
        });

        document.addEventListener('change', (e) => {
            if (e.target.id === 'font-size-slider') {
                this.saveToServer(e.target.value);
            }
        });


        const slider = document.getElementById('font-size-slider');
        if (slider) {
            this.updatePreview(slider.value);
        }
    }

    updatePreview(size) {
        const display = document.getElementById('current-size');
        if (display) {
            display.textContent = size + 'px';
        }
        document.documentElement.style.setProperty('--font-size', size + 'px');
    }

    saveToServer(size) {
        if (!this.csrfToken) {
            console.error('Kein CSRF Token');
            return;
        }

        const slider = document.getElementById('font-size-slider');
        const url = slider?.dataset.updateUrl || '/font-size/update';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken
            },
            body: JSON.stringify({ font_size: parseInt(size) })
        })
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    console.log(' Schriftgröße gespeichert:', data.font_size + 'px');


                    const display = document.getElementById('current-size');
                    if (display) {
                        display.style.color = 'green';
                        setTimeout(() => display.style.color = '', 1000);
                    }
                }
            })
            .catch(error => {
                console.error(' Fehler:', error);
            });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.fontSizeManager = new FontSizeManager();
});
