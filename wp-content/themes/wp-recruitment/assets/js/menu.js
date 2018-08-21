(function ($) {
    "use strict";
    $(document).ready(function(){
        var $menu = $('.cshero-navigation-right');
        $menu.find('.jobboard-widget').each(function(){
            var $submenu = $(this).find('.widget-content');
            if($submenu.length == 1){
                $(this).on('hover', function(){
                    if($submenu.offset().left + $submenu.width() > $(window).width()){
                        $submenu.addClass('back');
                    }else if($submenu.offset().left < 0){
                        $submenu.addClass('back');
                    }
                });
            }
        });

        /* Menu drop down*/
        $('.nav-menu li.menu-item-has-children').append('<span class="cs-menu-toggle"></span>');
        $('.cs-menu-toggle').on('click', function(){
            $(this).prev().toggleClass('submenu-open');
            $(this).parent().find('> .sub-menu').slideToggle();
        });
        /* Page Fixed Menu */
        $('.header-fixed-page').parents('body').addClass('remove-margin-top');
        $('#cshero-header.no-sticky').parents('body').addClass('remove-margin-top');
    });

})(jQuery);
