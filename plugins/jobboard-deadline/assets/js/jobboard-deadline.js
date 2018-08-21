(function( $ ) {
    "use strict";
    $('.deadline-clock').each(function () {
        var _time = $(this).data('time');
        if(_time) {
            $(this).countdown(_time)
                .on('update.countdown', function (event) {
                    var format = '%D days %H:%M:%S';
                    $(this).html(event.strftime(format));
                })
                .on('finish.countdown', function(event) {
                    $(this).html($(this).data('expired'))
                        .parent().addClass('disabled');
                });
        }
    });
})( jQuery );