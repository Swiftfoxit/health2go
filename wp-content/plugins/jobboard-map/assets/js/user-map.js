(function( $ ) {
    "use strict";
    var map = new google.maps.Map($('.map-content').get(0), jobboard_map_user.setting);

    var marker = new google.maps.Marker({
        position: jobboard_map_user.setting.center,
        map: map,
        icon: {
            size: new google.maps.Size(34, 34),
            scaledSize: new google.maps.Size(34, 34),
            url : jobboard_map_user.marker.url
        }
    });

    var infowindow = new google.maps.InfoWindow({
        content: jobboard_map_user.info
    });

    infowindow.open(map, marker);

})( jQuery );