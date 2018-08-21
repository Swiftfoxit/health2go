/**
 * @team: FsFlex Team
 * @since: 1.0.0
 * @author: KP
 */
(function ($) {
    var jb_events = {
        init: function () {
            this.events.filter_events();
            this.events.register_event();
            this.events.validate_event_register();
            this.events.close_event_reg_form();
        },
        handle: {
            filer_events_handle: function (term_id) {
                if (term_id === '0') {
                    $('.jb-events').removeClass('jb-event-hidden');
                    return;
                }
                $('.jb-events').each(function () {
                    var data_filter = $(this).attr('data-jbfilter');

                    if (data_filter.indexOf(term_id) !== -1) {
                        $(this).removeClass('jb-event-hidden');
                    } else {
                        $(this).addClass('jb-event-hidden');
                    }
                });
            }
        },
        events: {
            filter_events: function () {
                $(document).on('click', '.jb-filter-event-types', function (e) {
                    e.preventDefault();

                    var _this = $(this);
                    if (_this.hasClass('jb-event-active')) {
                        return;
                    }
                    $('.jb-filter-event-types').removeClass('jb-event-active');
                    _this.addClass('jb-event-active');
                    var term_id = _this.attr('id').replace('jb-filter-event-type-', '');
                    jb_events.handle.filer_events_handle(term_id);
                });
            },
            register_event: function () {
                $(document).on('click', '#register-event', function (e) {
                    e.preventDefault();
                    var _this = $(this);
                    $('.je-viewing-form').remove();
                    $.ajax({
                        url: data_ajax.ajax_url,
                        async: false,
                        type: 'POST',
                        beforeSend: function () {
                        },
                        data: {
                            action: 'show_event_register_form',
                            id: _this.attr('data-event')
                        }
                    })
                        .done(function (data) {
                            if (data !== "error") {
                                _this.after(data);
                            }
                        })
                        .fail(function () {
                            return false;
                        })
                        .always(function () {
                            return false;
                        });
                });
            },
            close_event_reg_form: function () {
                $(document).on('click', '.je-viewing-close', function (e) {
                    e.preventDefault();
                    $('.je-viewing-form').remove();
                });
            },
            validate_event_register: function () {
                $(document).on('click', '.je-btn-reg', function (e) {
                    e.preventDefault();
                    var _this = $(this);
                    e.preventDefault();
                    var _name = $(".je-reg-name"),
                        _email = $(".je-reg-email"),
                        _phone = $(".je-reg-number"),
                        _check = {
                            'name': _name,
                            'email': _email,
                            'phone': _phone
                        },
                        _check_val = true;
                    $.each(_check, function () {
                        $(this).removeClass('je-error');
                        if ($(this).val() === "" || $(this).val() === null) {
                            $(this).addClass('je-error');
                            _check_val = false;
                        }
                    });
                    if (_check.email.length > 0) {
                        var atpos = _check.email.val().indexOf("@");
                        var dotpos = _check.email.val().lastIndexOf(".");
                        if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= _check.email.val().length) {
                            _check.email.addClass('je-error');
                            _check_val = false;
                        }
                    }
                    var nonce = $('#_wp_nonce_register_event').val();
                    if (_check_val === true) {
                        $.ajax({
                            url: data_ajax.ajax_url,
                            type: 'POST',
                            beforeSend: function () {
                            },
                            data: {
                                action: 'je_save_register_event',
                                nonce: nonce,
                                name: _check.name.val(),
                                email: _check.email.val(),
                                phone: _check.phone.val(),
                                id_event: _this.attr('data-id')
                            }
                        })
                            .done(function (data) {
                                if (typeof data !== "undefined" && data.stt === 'error_email') {
                                    $('.je-viewing-form-body').html(data.msg);
                                    _check.email.addClass('je-error');
                                    return false;
                                }
                                if (typeof data !== "undefined" && data.stt === 'done') {
                                    $('.je-viewing-form-body').html(data.msg);
                                    setTimeout(function () {
                                        window.location.reload();
                                    }, 2000);
                                }
                                $('.je-viewing-form-body').html(data.msg);
                            })
                            .fail(function () {
                                return false;
                            })
                            .always(function () {
                                // _wait.hide();
                            });
                    }
                });
            }
        }
    }
    jb_events.init();
})(jQuery);
