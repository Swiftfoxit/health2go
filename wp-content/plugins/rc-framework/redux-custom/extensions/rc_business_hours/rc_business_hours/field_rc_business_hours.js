(function( $ ) {
    "use strict";
    redux.field_objects = redux.field_objects || {};
    redux.field_objects.rc_business_hours = redux.field_objects.rc_business_hours || {};
    redux.field_objects.rc_business_hours.init = function (selector) {
        if (!selector) {
            selector = $(document).find(".redux-group-tab:visible").find('.redux-container-rc_business_hours:visible');
        }
        $(selector).each(
            function () {
                var el = $(this);
                var parent = el;
                if (!el.hasClass('redux-field-container')) {
                    parent = el.parents('.redux-field-container:first');
                }

                if (parent.is(":hidden")) {
                    return;
                }

                if (parent.hasClass('redux-field-init')) {
                    parent.removeClass('redux-field-init');
                } else {
                    return;
                }

                el.find( "select" ).select2({
                    width: 'resolve'
                });

                el.find('button.add').on('click', function () {
                    var weekday = el.find('select.weekday').val();
                    var open    = el.find('select.hour-open').val();
                    var close   = el.find('select.hour-close').val();
                    var name    = el.find('.regular-business-hours').data('name');

                    if(!weekday || !open || !close){
                        return false;
                    }

                    var html    = '<li>';
                    html += '<span class="weekday">' + weekday + '</span>';
                    html += ' <span class="hour-open">' + open + '</span>';
                    html += ' <span class="sp">-</span>';
                    html += ' <span class="hour-close">' + close + '</span>';
                    html += ' <a class="remove-hour" href="javascript:void(0)"><span class="dashicons dashicons-no-alt"></span></a>';
                    html += '<input type="hidden" name="' + name + '[' + weekday + '][open]" value="' + open + '">';
                    html += '<input type="hidden" name="' + name + '[' + weekday + '][close]" value="' + close + '">';
                    html += '</li>';
                    el.find('.regular-business-hours').prepend(html);
                });

                el.find('.regular-business-hours').on('click', '.remove-hour', function () {
                    $(this).parents('li').remove();
                });
            })
    }
})( jQuery );