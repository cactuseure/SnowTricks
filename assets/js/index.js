const $ = require('jquery');
require('bootstrap');

import './figure/comments.js';
import './figure/tricksPagination.js';
import './figure/form.js';
import './figure/gallery.js';

/** modal figure delete **/
$('.deleteButton').on('click', function() {
    let figureId = $(this).data('id');
    let url = '/figure/' + figureId + '/delete';
    $('#deleteConfirm').attr('href', url);
});

/** modal figure images delete **/
$('.deleteTrickImgButton').on('click', function() {
    let figureId = $(this).data('figureId');
    let mediaObjectId = $(this).data('mediaobjectId');
    let url = '/figure/' + figureId + '/delete-media/' + mediaObjectId;
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
    // Cacher l'élément au début
    scrollToTop.hide();

    // Fonction pour afficher ou masquer l'élément en fonction du défilement
    function toggleScrollToTopVisibility() {
        let scrollPosition = $(window).scrollTop();

        if (scrollPosition > 300) {
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



