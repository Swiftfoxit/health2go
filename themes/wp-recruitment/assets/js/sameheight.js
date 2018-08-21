(function ($) {
    "use strict";

    /* matchHeight example */

    $(function() {
        $('.vc_row-o-equal-height .wpb_column .vc_column-inner').matchHeight();
        $('.columns-same .col-same').matchHeight();
        // example of update callbacks (uncomment to test)
        $.fn.matchHeight._beforeUpdate = function(event, groups) {
            //var eventType = event ? event.type + ' event, ' : '';
            //console.log("beforeUpdate, " + eventType + groups.length + " groups");
        }

        $.fn.matchHeight._afterUpdate = function(event, groups) {
            //var eventType = event ? event.type + ' event, ' : '';
            //console.log("afterUpdate, " + eventType + groups.length + " groups");
        }
    });

})(jQuery);