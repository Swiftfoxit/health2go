(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.rc_taxonomy_level = redux.field_objects.rc_taxonomy_level || {};

    redux.field_objects.rc_taxonomy_level.init = function (selector) {
        if (!selector) {
            selector = $(document).find(".redux-group-tab:visible").find('.redux-container-rc_taxonomy_level:visible');
        }
        $(selector).each(
            function () {
                var el = $(this);
                var parent = el;
                if (!el.hasClass('redux-field-container')) {
                    parent = el.parents('.redux-field-container:first');
                }

                if (parent.is(":hidden")) { // Skip hidden fields
                    return;
                }

                if (parent.hasClass('redux-field-init')) {
                    parent.removeClass('redux-field-init');
                } else {
                    return;
                }

                var default_params = {
                    width: 'resolve',
                    triggerChange: true,
                    allowClear: true
                };

                el.find(".redux-wpl-taxonomy-level").on('change', function () {

                    var _select = $(this);
                    var _select_next = _select.parent().find('select.redux-wpl-taxonomy-level-' + _select.data('level'));

                    if(_select_next.length > 0) {

                        var _child = _select.nextAll('select');

                        _child.each(function () {
                            $(this).html('<option></option>');
                            $(this).select2(default_params);
                        });

                        _select.parent().find('select').prop("disabled", true);

                        $.post(
                            ajaxurl,
                            {
                                'action': 'rc_taxonomy_level',
                                'parent': _select.val(),
                                'taxonomy': _select.data('taxonomy'),
                                'level': _select.data('level')
                            },
                            function (response) {
                                _select_next.html(response);
                                _select_next.select2(default_params);
                                _select.parent().find('select').prop("disabled", false);
                            }
                        );
                    }
                });

                el.find( ".redux-wpl-taxonomy-level" ).select2( default_params );
            })
    }
})( jQuery );