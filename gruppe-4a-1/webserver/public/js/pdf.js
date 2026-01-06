pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

const pdfInstances = new Map();

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAllPDFs);
} else {
    initAllPDFs();
}

async function initAllPDFs() {
    const pdfContainers = document.querySelectorAll('.aufgaben-blätter');

    if (pdfContainers.length === 0) {
        console.warn('Keine PDF-Container gefunden');
        return;
    }

    await new Promise(resolve => requestAnimationFrame(resolve));


    const loadPromises = Array.from(pdfContainers).map(async (container) => {
        const pdfUrl = container.dataset.pdfUrl;
        const pdfIndex = container.querySelector('.prev-page')?.dataset.pdfIndex;
        const canvas = container.querySelector('.pdf-canvas');

        if (!pdfUrl || !canvas || !pdfIndex) {
            console.error('Fehlende Attribute für PDF-Container:', { pdfUrl, pdfIndex, canvas });
            return;
        }

        try {
            await initPDF(pdfUrl, pdfIndex, canvas, container);
        } catch (error) {
            console.error(`Fehler beim Laden von PDF ${pdfIndex}:`, error);
            canvas.innerHTML = '<p style="color: red; padding: 20px;">PDF konnte nicht geladen werden</p>';
        }
    });

    await Promise.allSettled(loadPromises);
}

async function initPDF(pdfUrl, pdfIndex, canvas, container) {
    console.log('Initialisiere PDF:', pdfIndex, pdfUrl);

    // Loading-Indikator
    canvas.innerHTML = '<p style="padding: 20px; text-align: center;">Lädt PDF...</p>';

    const loadingTask = pdfjsLib.getDocument({
        url: pdfUrl,
        cMapUrl: 'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/cmaps/',
        cMapPacked: true
    });

    const pdfDoc = await loadingTask.promise;

    const pdfInstance = {
        pdfDoc: pdfDoc,
        currentPage: 1,
        searchResults: [],
        searchIndex: -1,
        canvas: canvas,
        container: container,
        isRendering: false
    };

    pdfInstances.set(pdfIndex, pdfInstance);

    // Seitenzahl anzeigen
    const pageCountEl = container.querySelector(`.page-count[data-pdf-index="${pdfIndex}"]`);
    if (pageCountEl) {
        pageCountEl.textContent = pdfDoc.numPages;
    }

    setupEventListeners(pdfIndex, container);
    await renderPage(pdfIndex, 1);
}

async function renderPage(pdfIndex, pageNum) {
    const instance = pdfInstances.get(pdfIndex);
    if (!instance || instance.isRendering) return;

    instance.isRendering = true;

    try {
        const page = await instance.pdfDoc.getPage(pageNum);
        const canvas = instance.canvas;
        const ctx = canvas.getContext('2d');

        const container = instance.container;
        const canvasWrapper = container.querySelector('.canvas-wrapper');

        await new Promise(resolve => requestAnimationFrame(resolve));

        let containerWidth = canvasWrapper?.clientWidth || container.clientWidth || 0;

        if (containerWidth === 0) {
            containerWidth = Math.min(window.innerWidth * 0.9, 800);
        }

        const defaultViewport = page.getViewport({ scale: 1 });

        const targetWidth = containerWidth;
        const scale = Math.min(targetWidth / defaultViewport.width, 2); // Max scale 2

        // Device Pixel Ratio
        const dpr = window.devicePixelRatio || 1;
        const viewport = page.getViewport({ scale: scale });

        canvas.width = Math.floor(viewport.width * dpr);
        canvas.height = Math.floor(viewport.height * dpr);
        canvas.style.width = Math.floor(viewport.width) + 'px';
        canvas.style.height = Math.floor(viewport.height) + 'px';
        canvas.style.maxWidth = '100%';

        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // PDF rendern
        const renderContext = {
            canvasContext: ctx,
            viewport: viewport
        };

        await page.render(renderContext).promise;

        // Seite aktualisieren
        instance.currentPage = pageNum;

        const pageNumEl = container.querySelector(`.page-num[data-pdf-index="${pdfIndex}"]`);
        if (pageNumEl) {
            pageNumEl.textContent = pageNum;
        }

        updateNavigationButtons(pdfIndex);

        console.log(`PDF ${pdfIndex} Seite ${pageNum} gerendert (${canvas.width}x${canvas.height})`);

    } catch (error) {
        console.error(`Fehler beim Rendern von Seite ${pageNum}:`, error);
        instance.canvas.innerHTML = `<p Seite konnte nicht geladen werden</p>`;
    } finally {
        instance.isRendering = false;
    }
}

function updateNavigationButtons(pdfIndex) {
    const instance = pdfInstances.get(pdfIndex);
    if (!instance) return;

    const container = instance.container;
    const prevBtn = container.querySelector(`.prev-page[data-pdf-index="${pdfIndex}"]`);
    const nextBtn = container.querySelector(`.next-page[data-pdf-index="${pdfIndex}"]`);

    if (prevBtn) {
        prevBtn.disabled = instance.currentPage <= 1;
    }
    if (nextBtn) {
        nextBtn.disabled = instance.currentPage >= instance.pdfDoc.numPages;
    }
}

function setupEventListeners(pdfIndex, container) {
    const prevBtn = container.querySelector(`.prev-page[data-pdf-index="${pdfIndex}"]`);
    if (prevBtn) {
        prevBtn.addEventListener('click', async () => {
            const instance = pdfInstances.get(pdfIndex);
            if (instance && instance.currentPage > 1 && !instance.isRendering) {
                await renderPage(pdfIndex, instance.currentPage - 1);
            }
        });
    }

    const nextBtn = container.querySelector(`.next-page[data-pdf-index="${pdfIndex}"]`);
    if (nextBtn) {
        nextBtn.addEventListener('click', async () => {
            const instance = pdfInstances.get(pdfIndex);
            if (instance && instance.currentPage < instance.pdfDoc.numPages && !instance.isRendering) {
                await renderPage(pdfIndex, instance.currentPage + 1);
            }
        });
    }

    // Suche
    const searchBtn = container.querySelector(`.search-btn[data-pdf-index="${pdfIndex}"]`);
    const searchInput = container.querySelector(`.search-input[data-pdf-index="${pdfIndex}"]`);

    if (searchBtn && searchInput) {
        const performSearch = () => {
            const searchTerm = searchInput.value.trim();
            if (searchTerm) {
                searchInPDF(pdfIndex, searchTerm);
            }
        };

        searchBtn.addEventListener('click', performSearch);
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') performSearch();
        });
    }

    // Suchnavigation
    const searchPrev = container.querySelector(`.search-prev[data-pdf-index="${pdfIndex}"]`);
    const searchNext = container.querySelector(`.search-next[data-pdf-index="${pdfIndex}"]`);

    if (searchPrev) {
        searchPrev.addEventListener('click', () => navigateSearchResult(pdfIndex, -1));
    }
    if (searchNext) {
        searchNext.addEventListener('click', () => navigateSearchResult(pdfIndex, 1));
    }
}

async function searchInPDF(pdfIndex, searchTerm) {
    const instance = pdfInstances.get(pdfIndex);
    if (!instance || !searchTerm) return;

    try {
        instance.searchResults = [];
        instance.searchIndex = -1;

        const resultsEl = instance.container.querySelector(`.search-results[data-pdf-index="${pdfIndex}"]`);
        if (resultsEl) {
            resultsEl.textContent = 'Suche läuft...';
        }

        // Alle Seiten durchsuchen
        for (let i = 1; i <= instance.pdfDoc.numPages; i++) {
            const page = await instance.pdfDoc.getPage(i);
            const textContent = await page.getTextContent();
            const pageText = textContent.items.map(item => item.str).join(' ');

            const regex = new RegExp(searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'gi');
            let match;

            while ((match = regex.exec(pageText)) !== null) {
                instance.searchResults.push({
                    pageNum: i,
                    text: match[0],
                    index: match.index
                });
            }
        }

        // Ergebnisse anzeigen
        if (resultsEl) {
            if (instance.searchResults.length > 0) {
                resultsEl.textContent = `${instance.searchResults.length} Ergebnis(se) gefunden`;
                goToSearchResult(pdfIndex, 0);
            } else {
                resultsEl.textContent = 'Keine Ergebnisse gefunden';
            }
        }

    } catch (error) {
        console.error('Fehler bei der Suche:', error);
    }
}

function goToSearchResult(pdfIndex, resultIndex) {
    const instance = pdfInstances.get(pdfIndex);
    if (!instance || resultIndex < 0 || resultIndex >= instance.searchResults.length) return;

    const result = instance.searchResults[resultIndex];
    instance.searchIndex = resultIndex;

    renderPage(pdfIndex, result.pageNum);

    const resultsEl = instance.container.querySelector(`.search-results[data-pdf-index="${pdfIndex}"]`);
    if (resultsEl) {
        resultsEl.textContent = `Ergebnis ${resultIndex + 1} von ${instance.searchResults.length} (Seite ${result.pageNum})`;
    }
}

function navigateSearchResult(pdfIndex, direction) {
    const instance = pdfInstances.get(pdfIndex);
    if (!instance || instance.searchResults.length === 0) return;

    let newIndex = instance.searchIndex + direction;

    if (newIndex < 0) {
        newIndex = instance.searchResults.length - 1;
    } else if (newIndex >= instance.searchResults.length) {
        newIndex = 0;
    }

    goToSearchResult(pdfIndex, newIndex);
}

// Debounced Resize
let resizeTimeout;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
        pdfInstances.forEach((instance, pdfIndex) => {
            if (instance && instance.currentPage && !instance.isRendering) {
                renderPage(pdfIndex, instance.currentPage);
            }
        });
    }, 250);
});
