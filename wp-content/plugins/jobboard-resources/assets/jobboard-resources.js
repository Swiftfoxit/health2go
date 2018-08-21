/**
 * @team: FsFlex Team
 * @since: 1.0.0
 * @author: KP
 */
(function ($) {
    var jr_handle = {
        init: function () {
            this.events.downloadButtonClick();
        },
        events: {
            downloadButtonClick: function () {
                $(document).on('click', '.jr-button-download', function (e) {
                    e.preventDefault();
                    var _this = $(this);
                    _this.prev().removeClass('jr-error');
                    _check_val = true;
                    /**
                     * Check email value
                     */
                    var input_filed = _this.prev(),
                        email = input_filed.val();
                    if (typeof email === 'undefined' || email.length === 0) {
                        jr_handle.handles.noticeEmailError(_this);
                        input_filed.focus();
                        _check_val = false;
                    }
                    if (email.length > 0) {
                        var atpos = email.indexOf("@");
                        var dotpos = email.lastIndexOf(".");
                        if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= email.length) {
                            jr_handle.handles.noticeEmailError(_this);
                            input_filed.focus();
                            _check_val = false;
                        }
                    }

                    /**
                     * handle when email value is true
                     */
                    if (_check_val) {
                        var nonce = $('#_wp_nonce_jr_download').val(),
                            id = _this.attr('data-id');
                        $.ajax({
                            url: data_ajax.ajax_url,
                            type: 'POST',
                            beforeSend: function () {
                                input_filed.attr('disabled', 'disabled');
                                _this.after('<span class="jr-wait fa fa-spin fa-spinner"></span>');
                            },
                            data: {
                                action: 'jr_download_handle',
                                nonce: nonce,
                                email: email,
                                rs_id: id
                            }
                        })
                            .done(function (data) {
                                if (typeof data !== "undefined") {
                                    _this.parent().after('<p class="jr-notice">'+data.msg+'</p>');
                                    setTimeout(function () {
                                        $('.jr-notice').remove();
                                    },5000);
                                }
                            })
                            .fail(function () {
                                return false;
                            })
                            .always(function () {
                                $('.jr-wait').remove();
                                input_filed.removeAttr('disabled');
                            });
                    }
                });
            }
        },
        handles: {
            noticeEmailError: function (_this) {
                _this.prev().addClass('jr-error');
            }
        }
    };
    jr_handle.init();

})(jQuery);
