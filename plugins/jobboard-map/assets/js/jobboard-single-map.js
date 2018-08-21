jQuery(function ($) {
    "use strict";
    $('.map-content').each(function () {

        var current = $(this);
        var mouse = false;
        var markers = [];
        var map = new google.maps.Map(current.get(0), jb_single_map.map);

        /* Marker options. */
        var marker_options = {
            icon: {size: new google.maps.Size(34, 34), scaledSize: new google.maps.Size(34, 34)},
            draggable: false
        };

        /* Custom icon. */
        if (jb_single_map.args['icon'] != '') {
            marker_options.icon.url = jb_single_map.args['icon'];
        }

        /**create**********************************************/
        /* Create info window */
        var infowindow = new google.maps.InfoWindow();
        /* Create marker clusterer */
        var clusterer = new MarkerClusterer(map, [], {imagePath: jb_single_map.args['icons']});
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
        marker_options.map = map;
        /* Add default markers. */
        addmarkers(jb_single_map.args['markers']);

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
                var zoomChangeBoundsListener =
                    google.maps.event.addListenerOnce(map, 'bounds_changed', function (event) {
                        if (this.getZoom()) {
                            this.setZoom(15);
                        }
                    });
                setTimeout(function () {
                    google.maps.event.removeListener(zoomChangeBoundsListener)
                }, 1000);
            }
        }

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
        map.setZoom(jb_single_map.map.zoom);
        setTimeout(function () {
            map.setZoom(jb_single_map.map.zoom);
        }, 1000);
    });
});