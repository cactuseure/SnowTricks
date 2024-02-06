import axios from 'axios';

document.addEventListener('DOMContentLoaded', () => {
    const home = document.querySelector('body#home');
    if (!home) {
        return;
    }
    loadTricks();
});

window.page = 1; // Initialisation du numéro de page pour la pagination

/*
* Méthode ajax qui récupère l'HTML des Tricks en fonction de la page et du nombre voulu
* */
function fetchTricks(buttonLoadMore) {
    const blockContent = document.querySelector('.list-tricks');
    const perPage = blockContent.dataset.perPage;
    const url = blockContent.dataset.url;
    buttonLoadMore.querySelector('.spinner').hidden = false;

    axios.post(url, {}, {
        params: {
            'page': window.page,
            'perPage': perPage,
        }
    }).then(response => {
        if (200 === response.status) {
            const contentHTML = document.createElement('div');
            contentHTML.innerHTML = response.data;
            const blocContent = document.querySelector('.list-tricks');

            for (const $item of Array.from(contentHTML.children)) {
                blocContent.append($item);

                addEventOnDelBtn();
                if ($item.dataset.lastItem === 'true') {
                    buttonLoadMore.disabled = true;
                    buttonLoadMore.querySelector('.spinner').hidden = true;
                }
            }
            buttonLoadMore.querySelector('.spinner').hidden = true;
            page++;
        }
    });
}

/*
* Affiche les Tricks sur la Homepage
* */
function loadTricks() {
    const buttonLoadMore = document.getElementById('load-more-comments');
    if (!buttonLoadMore) {
        return;
    }
    fetchTricks(buttonLoadMore);

    buttonLoadMore.addEventListener('click', (event) => {
        event.preventDefault();

        fetchTricks(buttonLoadMore);
    })
}


function addEventOnDelBtn(){
    document.querySelectorAll('.deleteButton').forEach(function(button) {
        button.addEventListener('click', function() {
            let figureId = this.getAttribute('data-id');
            let url = '/figure/' + figureId + '/delete';
            document.getElementById('deleteConfirm').setAttribute('href', url);
        });
    });
}