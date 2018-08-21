(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.rc_custom_fields = redux.field_objects.rc_custom_fields || {};

    redux.field_objects.rc_custom_fields.init = function (selector) {
        if (!selector) {
            selector = $(document).find(".redux-group-tab:visible").find('.redux-container-rc_custom_fields:visible');
        }
        $(selector).each(
            function () {
                var el      = $(this);
                var parent  = el;
                if (!el.hasClass('redux-field-container')) {
                    parent  = el.parents('.redux-field-container:first');
                }

                if (parent.is(":hidden")) { // Skip hidden fields
                    return;
                }

                if (parent.hasClass('redux-field-init')) {
                    parent.removeClass('redux-field-init');
                } else {
                    return;
                }

                /* add. */
                el.find('.wpl-cf-types ul li i').on('click', function () {
                    var _this       = $(this);
                    var _field      = _this.parents('fieldset');
                    var _content    = _field.find('.wpl-cf-content > ul');
                    $.post(
                        ajaxurl,
                        {
                            'action'    : 'rc_cf_get_field',
                            'type'      : _this.data('type'),
                            'icon'      : _this.data('icon'),
                            'id'        : _field.data('id'),
                            'index'     : _content.find('> li').length,
                            'opt_name'  : rc_custom_fields.opt_name
                        },
                        function (response) {
                            if(response != '') {
                                _content.append(response);
                            }
                        }
                    );
                })

                /* remove. */
                el.find('.wpl-cf-content').on('click', 'ul li > i.el-remove' , function () {
                    $(this).parents('li').remove();
                })

                /* drag and drop. */
                el.find('.wpl-cf-content ul').sortable({
                    placeholder: "ui-state"
                });

                $('body').on('change', '.setting-popup input.title', function(){
                    var _li = $('#'+$(this).parents('.setting-popup').data('id')).find('> span.cf-title');
                    _li.html($(this).val());
                });

                $('body').on('change', '.setting-popup select.setting-col', function(){
                    var _li     = $('#'+$(this).parents('.setting-popup').data('id'));
                    _li.attr('data-col', $(this).val());
                });
            }
        )
    }
})( jQuery );