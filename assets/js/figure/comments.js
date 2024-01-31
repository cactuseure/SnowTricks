import axios from 'axios';

document.addEventListener('DOMContentLoaded', () => {
    const figure = document.querySelector('body#figure');
    if (!figure) {
        return;
    }
    loadComments();
});

window.page = 1;

function fetchComments(buttonLoadMore) {
    const blockContent = document.querySelector('.bloc-content');
    const perPage = blockContent.dataset.perPage;
    const url = blockContent.dataset.url;
    const trickId = blockContent.dataset.trickId;
    buttonLoadMore.querySelector('.spinner').hidden = false;

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
            if (contentHTML.children.length === 0){
                buttonLoadMore.disabled = true;
            }

            for (const $item of Array.from(contentHTML.children)) {
                blocContent.append($item);
                if ($item.dataset.lastItem === 'true') {
                    buttonLoadMore.disabled = true;
                }
            }

            buttonLoadMore.querySelector('.spinner').hidden = true;
            page++;
        }
    });
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

