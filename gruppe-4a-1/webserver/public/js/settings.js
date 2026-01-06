document.addEventListener('DOMContentLoaded', () => {

    const menuItems = document.querySelectorAll('.menu-item');
    const contentDiv = document.getElementById('content');



    if (menuItems.length === 0 || !contentDiv) {
        console.warn("Menu items oder contentDiv nicht gefunden.");
        return; // verhindert Crash
    }

    const contentData = {
        profil: {
            title: "Profil & Konto Einstellungen",
            text: "Hier sind deine Profil Informationen",
            templateId: "profil-template"
        },
        darstellung: {
            title: "Darstellung & Barrierefreiheits Einstellungen",
            text: "Hier kannst du Darstellung & Barrierefreiheit einstellen.",
            templateId: "darstellung-template"
        },
        fortschritt: {
            title: "Lernfortschritts & Datenschutz Einstellungen",
            text: "Hier kannst du deinen Lernfortschritt einsehen.",
            templateId: "fortschritt-template"
        },
        admin: {
            title: "Administrator-Bereich",
            text: "Admin-Einstellungen verwalten.",
            templateId: "admin-template"
        },
        lehrer: {
            title: "Lehrer-Bereich",
            text: "Einstellungen für Lehrer.",
            templateId: "lehrer-template"
        },
        eltern: {
            title: "Eltern-Bereich",
            text: "Einstellungen für Eltern.",
            templateId: "eltern-template"
        }
    };

    menuItems.forEach(item => {
        item.addEventListener('click', () => {

            menuItems.forEach(i => i.classList.remove('active'));
            item.classList.add('active');

            const key = item.dataset.content;
            const data = contentData[key];

            let templateHTML = "";
            if (data.templateId) {
                const template = document.getElementById(data.templateId);
                if (template) templateHTML = template.innerHTML;
            }

            contentDiv.innerHTML = `
                <h2>${data.title}</h2>
                <p>${data.text}</p>
                ${templateHTML}
            `;
        });
    });

    // Ersten Menüpunkt anklicken wenn vorhanden
    if (menuItems[0]) {
        menuItems[0].click();
    }
});
