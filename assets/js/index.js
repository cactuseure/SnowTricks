const $ = require('jquery');
require('bootstrap');

import './figure/comments.js';

/** modal figure delete **/
$('.deleteButton').on('click', function() {
    var figureId = $(this).data('id');
    var url = '/figure/' + figureId + '/delete';
    $('#deleteConfirm').attr('href', url);
});


/** message-flash **/
setTimeout(function() {
    $('.flash-message').fadeOut('fast');
}, 5000);


/** scroll to bottom **/
$(document).ready(function() {
    let arrowBottomCta = $("#arrowBottomCta");

    function toggleArrowVisibility() {
        if ($(window).scrollTop() > 0) {
            arrowBottomCta.fadeOut();
        } else {
            arrowBottomCta.fadeIn();
        }
    }

    $(window).scroll(function() {
        toggleArrowVisibility();
    });

    arrowBottomCta.on("click", function() {
        var sectionId = "tricks-container";
        $("html, body").animate({
            scrollTop: $("#" + sectionId).offset().top
        }, 50);
    });
});


/** scroll to top **/
$(document).ready(function() {
    let scrollToTop = $("#scroll-to-top");
    let footerHeight = 85; // Remplacez cela par la hauteur réelle de votre footer.

    // Cacher l'élément au début
    scrollToTop.hide();

    // Fonction pour afficher ou masquer l'élément en fonction du défilement
    function toggleScrollToTopVisibility() {
        let scrollPosition = $(window).scrollTop();
        let windowHeight = $(window).height();
        let documentHeight = $(document).height();

        if (scrollPosition > 300 && scrollPosition + windowHeight < documentHeight - footerHeight) {
            scrollToTop.fadeIn();
        } else {
            scrollToTop.fadeOut();
        }
    }

    $(window).scroll(function() {
        toggleScrollToTopVisibility();
    });

    scrollToTop.on("click", function() {
        $("html, body").animate({ scrollTop: 0 }, 50);
    });
});

/** load more commentaire **/
