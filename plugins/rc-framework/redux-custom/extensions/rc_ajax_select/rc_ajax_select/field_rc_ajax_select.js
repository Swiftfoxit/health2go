(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.rc_ajax_select = redux.field_objects.rc_ajax_select || {};

    redux.field_objects.rc_ajax_select.init = function (selector) {
        if ( !selector ) {
            selector = $( document ).find( '.redux-container-rc_ajax_select:visible' );
        }

        $( selector ).each(
            function() {
                var el = $( this );
                var parent = el;

                if ( !el.hasClass( 'redux-field-container' ) ) {
                    parent = el.parents( '.redux-field-container:first' );
                }
                if ( parent.is( ":hidden" ) ) { // Skip hidden fields
                    return;
                }
                if ( parent.hasClass( 'redux-field-init' ) ) {
                    parent.removeClass( 'redux-field-init' );
                } else {
                    return;
                }

                var select = $(this).find('select');

                select.selectize({
                    valueField: 'id',
                    labelField: 'title',
                    searchField: ['id', 'title', 'email', 'login'],
                    options: [],
                    create: false,
                    render: {
                        option: function(item, escape) {
                            var html = '<div><div><span>#' + item.id + '</span> <strong>' + item.title + '</strong></div>';

                            html += item.email != undefined ? '<div>(<small>' + item.email + '</small>)</div>' : '';
                            html += '</div>';

                            return html;
                        }
                    },
                    load: function(query, callback) {

                        if (!query.length) return callback();

                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                action: 'rc_ajax_select',
                                q: query,
                                source: select.data('source'),
                                type: select.data('type')
                            }, error: function () {
                                callback();
                            }, success: function(res) {
                                callback(res);
                            }
                        });
                    }
                });
            }
        );
    };

})( jQuery );