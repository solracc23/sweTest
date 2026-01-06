document.addEventListener('DOMContentLoaded', function() {

    const urlParams = new URLSearchParams(window.location.search);
    const section = urlParams.get('section');
    const thema =  urlParams.get('thema');
    if (section) {
        const menuItem = document.querySelector(`[data-content="${section}"]`);
        if (menuItem) {
            setTimeout(() => {
                document.querySelectorAll('.menu-item').forEach(item => {
                    item.classList.remove('active');
                });
                menuItem.classList.add('active');
                menuItem.click();

                menuItem.scrollIntoView({behavior: 'smooth', block: 'center'});
            }, 100)
        }

        }
        if (thema !== null) {
            const themaContainer = document.querySelector(`[data-id="${thema}"]`);
            if (themaContainer) {
                setTimeout(() => {
                    const aufgabe = themaContainer.querySelector('.aufgabe');
                    const themaDiv = themaContainer.querySelector('.thema');

                    if (aufgabe && themaDiv) {
                        aufgabe.style.display = 'flex';
                        themaDiv.style.borderBottomLeftRadius = '0px'
                        themaDiv.style.borderBottomRightRadius = '0px'
                        menuItem.scrollIntoView({behavior: 'smooth', block: 'center'});
                    }
                }, 100);
            }
        }
    });
