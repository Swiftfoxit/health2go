(function( $ ) {
    "use strict";

    var post_status = $('select#post_status');

    post_status.empty();
    $('#publish').attr('name', 'save').attr('value', jb_order.publish);

    $.each(jb_order.status, function (index, value) {

        var selected = '';

        if(index == jb_order.current){
            selected = ' selected="selected"';
            $('#post-status-display').text(value);
        }

        post_status.append('<option value="' + index + '"' + selected + '>' + value + '</option>');
    });
})( jQuery );