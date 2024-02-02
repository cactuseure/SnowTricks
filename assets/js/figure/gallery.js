import Viewer from 'viewerjs';

document.addEventListener('DOMContentLoaded', () => {
    const figure = document.querySelector('body#figure');
    if (!figure) {
        return;
    }

    function mediaGallery() {
        let mediaLists = document.querySelector('.trick-images');
        const gallery = new Viewer(mediaLists);
    }

    mediaGallery();
});