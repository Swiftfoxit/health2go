;(function ($) {
    "use strict";
    redux.field_objects = redux.field_objects || {};
    redux.field_objects.rc_map = redux.field_objects.rc_map || {};
    var je_init = redux.field_objects.rc_map.init = function (selector) {
        if (!selector) {
            selector = $(document).find(".redux-group-tab:visible").find('.redux-container-rc_map:visible');
        }
        $(selector).each(
            function () {
                var el = $(this);
                var parent = el;
                if (!el.hasClass('redux-field-container')) {
                    parent = el.parents('.redux-field-container:first');
                }

                if (parent.is(":hidden")) { // Skip hidden fields
                    return;
                }

                if (parent.hasClass('redux-field-init')) {
                    parent.removeClass('redux-field-init');
                } else {
                    return;
                }

                /* marker selected. */
                var rc_lat = el.find('.rc_map_lat').val();
                var rc_lng = el.find('.rc_map_lng').val();
                var rc_zoom = 13;
                var rc_market = false;

                var rc_center = {lat: 44.4738677, lng: 20.2606416};

                if (rc_lat && rc_lng) {
                    rc_center = {lat: parseFloat(rc_lat), lng: parseFloat(rc_lng)};
                    rc_zoom = parseInt(el.find('.rc_map_zoom').val());
                    rc_market = true;
                }

                /* default map. */
                var map = new google.maps.Map(el.find('.rc_map_content').get(0), {
                    center: rc_center,
                    zoom: rc_zoom,
                    mapTypeId: 'roadmap',
                    mapTypeControl: false
                });

                // Try HTML5 geolocation.
                if (rc_market == false && navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        var pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        map.setCenter(pos);
                        map.setZoom(12);
                    });
                }

                // Create the search box and link it to the UI element.
                var input = el.find('.rc_map_search').get(0);
                var searchBox = new google.maps.places.SearchBox(input);
                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                // Bias the SearchBox results towards current map's viewport.
                map.addListener('bounds_changed', function () {
                    searchBox.setBounds(map.getBounds());
                });

                // Create a marker.
                var marker_options = {map: map, draggable: true};
                if (rc_market == true) {
                    marker_options.position = rc_center;
                }

                var marker = new google.maps.Marker(marker_options);

                // Listen for the event fired when the user selects a prediction and retrieve
                // more details for that place.
                searchBox.addListener('places_changed', function () {
                    var places = searchBox.getPlaces();

                    if (places.length == 0) {
                        marker.setPosition(null);
                        return;
                    }

                    // For each place, get the icon, name and location.
                    var bounds = new google.maps.LatLngBounds();

                    places.forEach(function (place, index) {

                        if (!place.geometry || index > 0) {
                            return;
                        }

                        // marker set position.
                        marker.setPosition(place.geometry.location);
                        set_values_search_box(place.geometry.location);
                        set_values();

                        if (place.geometry.viewport) {
                            // Only geocodes have viewport.
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(place.geometry.location);
                        }
                    });

                    map.fitBounds(bounds);
                });

                /* map events. */
                map.addListener('rightclick', function (event) {
                    // marker set position.
                    marker.setPosition(event.latLng);
                    set_values_search_box(event.latLng);
                    set_values();
                });

                map.addListener('zoom_changed', function (event) {
                    el.find('.rc_map_zoom').val(map.getZoom());
                });

                google.maps.event.addListener(marker, 'dragend', function (evt) {
                    set_values_search_box(evt.latLng);
                    set_values();
                });

                marker.addListener('rightclick', function () {
                    marker.setPosition(null);
                    remove_values();
                });

                /* fixed firefox. */
                el.on('mouseleave', function (evt) {
                    set_values_search_box(evt.latLng);
                    set_values();
                });

                function set_values() {
                    if (marker.position) {
                        el.find('.rc_map_lat').val(marker.position.lat());
                        el.find('.rc_map_lng').val(marker.position.lng());
                    }
                }

                function remove_values() {
                    el.find('.rc_map_lat').val('');
                    el.find('.rc_map_lng').val('');
                    $('input.rc_map_search').val('');
                    $('#_address-textarea').val('');
                }

                function set_values_search_box(latlng) {
                    var geocoder = new google.maps.Geocoder();
                    geocoder.geocode({'location': latlng}, function (results, status) {
                        if (status === 'OK') {
                            if (results[0]) {
                                $('input.rc_map_search').val(results[0].formatted_address);
                                $('#_address-textarea').val(results[0].formatted_address);
                            } else {
                                window.alert('No results found');
                            }
                        }
                    });
                }
            }
        );
    };
    setTimeout(function () {
        je_init();
    },1000);
})(jQuery);