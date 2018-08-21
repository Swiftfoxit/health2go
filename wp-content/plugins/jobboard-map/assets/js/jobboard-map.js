(function ($) {
    "use strict";

    /* Create maps. */
    $('.map-content').each(function () {

        var current = $(this);
        var mouse = false;
        var markers = [];

        /**options*********************************************/

        /* Marker options. */
        var marker_options = {
            icon: {size: new google.maps.Size(34, 34), scaledSize: new google.maps.Size(34, 34)},
            draggable: false
        };

        /* Custom icon. */
        if (jb_map.args['icon'] != '') {
            marker_options.icon.url = jb_map.args['icon'];
        }

        /**create**********************************************/

        /* Create default map. */
        var map = new google.maps.Map(current.get(0), jb_map.map);
        /* Create control. */
        if (jb_map.args['searchControl']) {
            map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push($(jb_map.args['templateControls']).get(0));
            map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push($(jb_map.args['templateSearch']).get(0));
        }
        /* Create info window */
        var infowindow = new google.maps.InfoWindow();
        /* Create marker clusterer */
        var clusterer = new MarkerClusterer(map, [], {imagePath: jb_map.args['icons']});
        /* Create search box. */
        var searchBox = null;

        /**events**********************************************/

        /* Map mouse click. */
        map.addListener('click', function () {
            if (mouse == true) {
                map.setOptions({scrollwheel: true});
            }
        });
        /* Map mouse over. */
        map.addListener('mouseover', function () {
            mouse = true;
        });
        /* Map mouse out. */
        map.addListener('mouseout', function () {
            mouse = false;
            map.setOptions({scrollwheel: false});
        });

        /**controls**********************************************/

        /* Show map or search form. */
        current.on('click', '.tabs-map, .tabs-search', function () {

            $(this).parents('.map-tabs-control').find('> *').removeClass('active');
            $(this).addClass('active');

            if ($(this).hasClass('tabs-search')) {

                current.find('.map-search-control').addClass('active');
                /* Search click outside. */
                current.find('.map-search-control.active').on('click', function (e) {
                    if ($(e.target).parents('.map-search-control').length === 0) {
                        current.find('.tabs-map').trigger('click');
                    }
                });
                /* Search change. */
                current.find('.search-keyword').on('change', function () {
                    current.find('.search-button').trigger('click');
                });
                /* Search enter. */
                current.find('.search-keyword').on('keypress', function (e) {
                    if (e.which == 13) {
                        current.find('.search-button').trigger('click');
                    }
                });
                /* Search focus */
                current.find('.search-keyword').focus();

                searchbox();

            } else {
                current.find('.map-search-control').removeClass('active');
            }
        });

        /* Search */
        current.on('click', '.search-button', function () {
            search();
        });

        /**init*************************************************/
        marker_options.map = map;
        /* Add default markers. */
        addmarkers(jb_map.args['markers']);
        /* Auto detect locations. */
        geoLocation();
        $( document ).on('click',function() {
            searchbox();
        });

        /**functions********************************************/

        function geoLocation() {
            if (jb_map.args['geoLocation'] != true) {
                return false;
            }

            if (!navigator.geolocation) {
                return false;
            }

            navigator.geolocation.getCurrentPosition(function (position) {
                map.setCenter({lat: position.coords.latitude, lng: position.coords.longitude});
                map.setZoom(jb_map.zoom);
                search(position.coords.latitude, position.coords.longitude, 200);
            });
        }

        function search(lat, lng, radius) {
            var options = {};
            var map_center = map.getCenter();
            var map_height = current.find('.gm-style').height();
            var search_btn = current.find('.search-button');

            options.lat = lat ? lat : map_center.lat();
            options.lng = lng ? lng : map_center.lng();
            options.radius = radius ? radius : getRadius(map_height, map.getZoom());
            options.s = current.find('.search-keyword').val();

            search_btn.find('i:first-child').css('display', 'none');
            search_btn.find('i.jobboard-loading').attr('style', '');
            search_btn.prop('disabled', true);

            var location = current.find('.search-geolocation').val();
            if (location.length > 0) {
                $.get({
                    'url': 'https://maps.google.com/maps/api/geocode/json',
                    'data': {'sensor': 'false', 'address': location}
                }, function (data) {
                    if (data.results[0].geometry.location !== undefined) {
                        map.setCenter(data.results[0].geometry.location);
                        map.setZoom(7);
                        current.find('.map-search-control').removeClass('active');
                        current.find('.tabs-search').removeClass('active');
                        current.find('.tabs-map').addClass('active');
                    }
                });
            }

            $.post(jb_map.args['ajaxUrl'], {'action': 'jb_map_search', 'options': options}, function (response) {

                if (Object.keys(response).length > 0) {
                    console.log(response);
                    current.find('.tabs-map').trigger('click');
                    addmarkers(response);
                }

                search_btn.find('i:first-child').attr('style', '');
                search_btn.find('i.jobboard-loading').css('display', 'none');
                search_btn.prop('disabled', false);
            });
        }

        function searchbox() {
            if (searchBox !== null) {
                return false;
            }

            searchBox = new google.maps.places.SearchBox(current.find('.search-geolocation').get(0));

            searchBox.addListener('places_changed', function () {
                var places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                var bounds = new google.maps.LatLngBounds();

                places.forEach(function (place, index) {

                    if (!place.geometry || index > 0) {
                        return;
                    }

                    if (place.geometry.viewport) {
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });

                map.fitBounds(bounds);
            });
        }

        function addmarkers(jobs) {

            if (Object.keys(jobs).length == 0) {
                return false;
            }

            var bounds = new google.maps.LatLngBounds(null);
            var fitbounds = false;

            /* Add markers. */
            $.each(jobs, function (index, value) {

                if (markers[index]) {
                    return false;
                }

                marker_options.position = {lat: parseFloat(value.lat), lng: parseFloat(value.lng)};
                var marker = new google.maps.Marker(marker_options);

                /* Marker click. */
                marker.addListener('click', function () {
                    infowindow.close();
                    infowindow.setContent(value.info.toString());
                    infowindow.open(map, marker);
                });

                clusterer.addMarker(marker);
                bounds.extend(marker_options.position);
                markers[index] = marker;
                fitbounds = true;
            });

            if (fitbounds == true) {
                map.fitBounds(bounds);
            }
        }

        /**custom html******************************************/

        /* Custom cluster html. */
        ClusterIcon.prototype.onAdd = function () {

            this.div_ = document.createElement('DIV');

            if (this.visible_) {
                var pos = this.getPosFromLatLng_(this.center_);
                this.div_.style.cssText = this.createCss(pos);
                var innerHtml;

                if (this.cluster_.markers_.length > 0) {
                    innerHtml = "<span class='jb_map_sums'>" + this.sums_.text + "</span>";
                }

                this.div_.innerHTML = innerHtml;
                this.div_.style.backgroundSize = 'cover';
                this.div_.style.backgroundRepeat = 'no-repeat';
            }

            var panes = this.getPanes();
            panes.overlayMouseTarget.appendChild(this.div_);
            var that = this;

            google.maps.event.addDomListener(this.div_, 'click', function () {
                that.triggerClusterClick();
            });
        };
    });

    function getRadius(px, zoom) {
        var scales = [
            156412,
            78271.52,
            39135.76,
            19567.88,
            9783.94,
            4891.97,
            2445.98,
            1222.99,
            305.75,
            305.75,
            152.87,
            76.44,
            38.22,
            19.11,
            9.55,
            4.78,
            2.39,
            1.19,
            0.60,
            0.30,
            0.15];
        return (px / 2) * scales[zoom] * 0.000621371;
    }
})(jQuery);