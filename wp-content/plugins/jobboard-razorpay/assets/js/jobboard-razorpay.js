function JB_Razorpay() {
    this.init()
}

JB_Razorpay.prototype.init = function () {
    (function ($) {
        "use strict";
        $('.package-form').on("submit", function (e) {

            if ($('#payment').val() == 'razorpay') {

                e.preventDefault();

                var currency = $('#currency').val();

                jobboard_razorpay.amount = $('#package_price').val();
                jobboard_razorpay.name = $('#package_name').val();

                jobboard_razorpay.handler = function (response) {
                    $.post(ajaxurl, {
                        'action': 'jobboard_razorpay_insert_order',
                        'payment_id': response.razorpay_payment_id,
                        'payment': 'razorpay',
                        'package_id': $('#package_id').val()
                    }, function () {
                        window.location = jobboard_razorpay.redirect;
                    });
                }

                var razorpay = new Razorpay(jobboard_razorpay);
                razorpay.open();

                return false;
            }
        });

    })(jQuery);
}