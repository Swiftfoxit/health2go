(function($) {
    "use strict";

    $(document).ready(function () {
        $('.cms-slick-wrap').find('.cms-testimonial').each(function() {
            $('.cms-testimonial-wrap', $(this)).slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                fade: false,
                dots: false,
                autoplay: false,
                asNavFor: $('.cms-testimonial-nav', $(this))
            });
            $('.cms-testimonial-nav', $(this)).slick({
                slidesToShow: 5,
                slidesToScroll: 1,
                asNavFor: $('.cms-testimonial-wrap', $(this)),
                dots: false,
                arrows: true,
                centerMode: true,
                focusOnSelect: true,
                centerPadding: '0px',
                autoplay: false,
            });
        });
    });
}(jQuery));