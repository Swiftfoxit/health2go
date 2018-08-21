(function( $ ) {
    "use strict";

    $('.alerts-sections').on('click', 'li.sections', function () {
        $(this).parents('.alerts-form').find('li.active').removeClass('active');
        $(this).addClass('active');
    });

    $('.alerts-sections').on('click', '.alerts-remove', function () {
        $(this).parents('li').remove();
    });
    
})( jQuery );