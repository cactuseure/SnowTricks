import { register, format } from 'timeago.js';
import axios from 'axios';

document.addEventListener('DOMContentLoaded', () => {
    const figure = document.querySelector('body#figure');
    if (!figure) {
        return;
    }
    loadComments();

    datetimeComments();
});

window.page = 1;

function fetchComments(buttonLoadMore) {
    const blockContent = document.querySelector('.bloc-content');
    const perPage = blockContent.dataset.perPage;
    const url = blockContent.dataset.url;
    const trickId = blockContent.dataset.trickId;

    axios.post(url, {}, {
        params: {
            'page': window.page,
            'perPage': perPage,
            'trickId': trickId,
        }
    }).then(response => {
        if (200 === response.status) {
            const contentHTML = document.createElement('div');
            contentHTML.innerHTML = response.data;

            const blocContent = document.querySelector('.bloc-content');
            // blocContent.innerHTML = '';


            for (const $item of Array.from(contentHTML.children)) {
                blocContent.append($item);
                if ($item.dataset.lastItem === 'true') {
                    buttonLoadMore.disabled = true;
                    buttonLoadMore.querySelector('.spinner').hidden = true;
                }
            }

            page++;
        }
    });

    // fetch(url, {
    //     method: 'POST',
    //     body: JSON.stringify({
    //         'page': window.page,
    //         'perPage': perPage,
    //         'trickId': trickId,
    //     })
    // })
    //     .then(response => response.json()) // Convertir la réponse en JSON
    //     .then(data => {
    //         const commentList = document.getElementById('comment-list');
    //
    //         data.items.forEach(commentData => {
    //             let cache = document.createElement('div');
    //             cache.innerHTML = commentData;
    //             const commentHTML = cache.firstElementChild;
    //
    //             commentList.appendChild(commentHTML);
    //         });
    //
    //         buttonLoadMore.disabled = false;
    //         buttonLoadMore.querySelector('.spinner').hidden = true;
    //
    //         datetimeComments();
    //
    //         if (!data.isMoreResults) {
    //             buttonLoadMore.hidden = true;
    //         } else {
    //             window.page++; // Incrémenter la page uniquement s'il y a plus de résultats
    //         }
    //     })
    //     .catch(error => {
    //         console.error('Error during data retrieval:', error);
    //         buttonLoadMore.disabled = false;
    //     });
}


function loadComments() {
    const buttonLoadMore = document.getElementById('load-more-comments');
    if (!buttonLoadMore) {
        return;
    }
    fetchComments(buttonLoadMore);

    buttonLoadMore.addEventListener('click', (event) => {
        event.preventDefault();

        fetchComments(buttonLoadMore);
    })
}

function datetimeComments(){
    const localeFunc = (number, index, totalSec) => {
        return [
            ['à l\'instant', 'dans un instant'],
            ['il y a %s secondes', 'dans %s secondes'],
            ['il y a 1 minute', 'dans 1 minute'],
            ['il y a %s minutes', 'dans %s minutes'],
            ['il y a 1 heure', 'dans 1 heure'],
            ['il y a %s heures', 'dans %s heures'],
            ['il y a 1 jour', 'dans 1 jour'],
            ['il y a %s jours', 'dans %s jours'],
            ['il y a 1 semaine', 'dans 1 semaine'],
            ['il y a %s semaines', 'dans %s semaines'],
            ['il y a 1 mois', 'dans 1 mois'],
            ['il y a %s mois', 'dans %s mois'],
            ['il y a 1 an', 'dans 1 an'],
            ['il y a %s ans', 'dans %s ans'],
        ][index];
    };
    register('fr', localeFunc);
    const elements = document.querySelectorAll('.timeago');
    elements.forEach(element => {
        const timestamp = new Date(element.textContent).getTime();
        if ( !isNaN(timestamp) ){
            element.textContent = format(timestamp,'fr');
        }
    });
}