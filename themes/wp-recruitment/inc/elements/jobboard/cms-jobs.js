(function($) {
    "use strict";

    $(document).ready(function () {

        $('.jobs-carousel').each(function() {

            $(this).owlCarousel({
                loop    :($(this).attr('data-loop')) == '1' ? true : false,
                autoplay    :($(this).attr('data-autoplay')) == '1' ? true : false,
                dots    : false,
                nav    :($(this).attr('data-nav')) == '1' ? true : false,
                navText : ['<i class="zmdi zmdi-long-arrow-left"></i>','<i class="zmdi zmdi-long-arrow-right"></i>'],
                margin  :parseInt($(this).attr('data-margin')),
                responsive:{
                    0:{
                        items:parseInt($(this).attr('data-item-xs'))
                    },
                    768:{
                        items:parseInt($(this).attr('data-item-sm'))
                    },
                    992:{
                        items:parseInt($(this).attr('data-item-md'))
                    },
                    1200:{
                        items:parseInt($(this).attr('data-item-lg'))
                    }
                }
            })
            
        });
        
    });
}(jQuery));


