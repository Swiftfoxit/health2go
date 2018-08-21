(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.rc_icons = redux.field_objects.rc_icons || {};

    redux.field_objects.rc_icons.init = function (selector) {
        if (!selector) {
            selector = $(document).find(".redux-group-tab:visible").find('.redux-container-rc_icons:visible');
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

                el.find("#wpl-redux-icon-search").on('change', function () {
                    var _s = $(this).val();
                    $('.wpl-redux-font li').each(function () {
                        var _icon = $(this).attr('title');
                        if(_icon.indexOf(_s) != -1){
                            $(this).css('display','inline-block');
                        } else {
                            $(this).css('display','none');
                        }
                    })
                });

                var _btn = '';
                $('.wpl-redux-icon-button').on('click',function () {
                    _btn = $(this);
                });

                $('.wpl-redux-font').on('click', 'li', function () {
                    var _icon = $(this).attr('title');
                    _btn.parent().find('input').val(_icon);
                    _btn.find('i').attr('class', _icon);
                    tb_remove();
                });
            })
    }
})( jQuery );