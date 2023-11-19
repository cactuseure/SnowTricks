const $ = require('jquery');
require('bootstrap');

$('.deleteButton').on('click', function() {
    var figureId = $(this).data('id');
    var url = '/figure/' + figureId + '/delete';
    $('#deleteConfirm').attr('href', url);
});


setTimeout(function() {
    $('.flash-message').fadeOut('fast');
}, 5000);