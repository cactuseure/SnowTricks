const $ = require("jquery");
document.addEventListener('DOMContentLoaded', () => {

    const trickForm = document.querySelector('body#trick-form');

    if (!trickForm) {
        return;
    }

    document
        .querySelectorAll('.add_item_link')
        .forEach(btn => {
            btn.addEventListener("click", addFormToCollection);
        });

    document
        .querySelectorAll('li.youtubeVideos_field')
        .forEach(li => {
            addTagFormDeleteLink(li);
        });



    if (document.getElementById('btn-remove-trick-cover')) {
        document.getElementById('btn-remove-trick-cover').addEventListener('click', function () {
            document.getElementById('form-field-trick-cover').hidden = false;
            document.getElementById('form-trick-cover').hidden = true;
        });
    }
});


/*
* Ajoute un nouveau champ au CollectionType form
* */
const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('li');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    if (collectionHolder.classList.contains('trick-pictures')){
        let counter = collectionHolder.querySelectorAll('li').length;

        item.querySelector('label').textContent = 'Image n°' + (counter + 1);
    }

    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;
    console.log(item);
    addTagFormDeleteLink(item);
};

/*
* Ajoute le btn de suppression du nouvel item dans le form
* */
const addTagFormDeleteLink = (item) => {
    const removeFormButton = document.createElement('button');
    removeFormButton.classList.add('btn', 'btn-dark')
    removeFormButton.innerText = 'Effacer ce champ';

    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        // remove the li for the tag form
        resetCounter(e,item);
        item.remove();
    });
}

function resetCounter(e,item){
    let counterChild = 1;

    for (const child of e.target.parentElement.parentElement.children) {
        if (child!==item && child.classList.contains('trick-pictures')){
            console.log(child);
            child.querySelector('label').textContent = 'Image n°' + counterChild++
        }
    }
}