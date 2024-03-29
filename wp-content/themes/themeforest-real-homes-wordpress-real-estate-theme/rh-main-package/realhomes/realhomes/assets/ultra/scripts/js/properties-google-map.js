/**
 * Javascript to handle google map for multiple properties
 */
(function ($) {
    "use strict";

    window.realhomes_update_google_map = function( propertiesMapData ) {
    if (typeof propertiesMapData !== "undefined") {

        let circleOnMap      = false;
        let locationFieldLat = 0;
        let locationFieldLng = 0;

        if ( propertiesMapOptions.geo_location ) {

            // Get location latitude and longitude
            const locationFieldsWrap = document.getElementById( 'location-fields-wrap' );
            locationFieldLat         = locationFieldsWrap.querySelector( '.location-field-lat' ).value;
            locationFieldLng         = locationFieldsWrap.querySelector( '.location-field-lng' ).value;

            if ( locationFieldLat && locationFieldLng ) {
                circleOnMap = true;
            }
        }

        // This function is designed to be used when geolocation is enabled and a circle needs to be drawn on the map.
        const drawCircleOnMap = function ( map ) {
            // Get radius value
            const radiusField = document.getElementById( 'geolocation-radius' );
            const radiusValue = parseInt( radiusField.value );

            // Convert radius to meters
            const radiusMeters = ( propertiesMapOptions.radius_type === 'kilometers' ) ? radiusValue * 1000 : radiusValue * 1609.344;

            // Create and draw circle on map
            const mapCircle = new google.maps.Circle( {
                strokeColor   : propertiesMapOptions.circle_color,
                strokeOpacity : 0.8,
                strokeWeight  : 2,
                fillColor     : propertiesMapOptions.circle_color,
                fillOpacity   : 0.10,
                map,
                center        : {
                    lat : parseFloat( locationFieldLat ),
                    lng : parseFloat( locationFieldLng )
                },
                radius        : radiusMeters
            } );

            // Fit map to circle bounds
            map.fitBounds( mapCircle.getBounds() );
        };

        if (0 < propertiesMapData.length) {

            var fullScreenControl = true;
            var fullScreenControlPosition = google.maps.ControlPosition.RIGHT_BOTTOM;

            var mapTypeControl = true;
            var mapTypeControlPosition = google.maps.ControlPosition.LEFT_BOTTOM;

            if (mapStuff.modernHome) {
                mapTypeControl = false;
                fullScreenControlPosition = google.maps.ControlPosition.LEFT_BOTTOM;
            }

            var mapOptions = {
                zoom: 12,
                maxZoom: 16,
                fullscreenControl: fullScreenControl,
                fullscreenControlOptions: {
                    position: fullScreenControlPosition
                },
                mapTypeControl: mapTypeControl,
                mapTypeControlOptions: {
                    position: mapTypeControlPosition
                },
                scrollwheel: false,
                styles: [{
                    "featureType": "landscape", "stylers": [{
                        "hue": "#FFBB00"
                    }, {
                        "saturation": 43.400000000000006
                    }, {
                        "lightness": 37.599999999999994
                    }, {
                        "gamma": 1
                    }]
                }, {
                    "featureType": "road.highway", "stylers": [{
                        "hue": "#FFC200"
                    }, {
                        "saturation": -61.8
                    }, {
                        "lightness": 45.599999999999994
                    }, {
                        "gamma": 1
                    }]
                }, {
                    "featureType": "road.arterial", "stylers": [{
                        "hue": "#FF0300"
                    }, {
                        "saturation": -100
                    }, {
                        "lightness": 51.19999999999999
                    }, {
                        "gamma": 1
                    }]
                }, {
                    "featureType": "road.local", "stylers": [{
                        "hue": "#FF0300"
                    }, {
                        "saturation": -100
                    }, {
                        "lightness": 52
                    }, {
                        "gamma": 1
                    }]
                }, {
                    "featureType": "water", "stylers": [{
                        "hue": "#0078FF"
                    }, {
                        "saturation": -13.200000000000003
                    }, {
                        "lightness": 2.4000000000000057
                    }, {
                        "gamma": 1
                    }]
                }, {
                    "featureType": "poi", "stylers": [{
                        "hue": "#00FF6A"
                    }, {
                        "saturation": -1.0989010989011234
                    }, {
                        "lightness": 11.200000000000017
                    }, {
                        "gamma": 1
                    }]
                }]
            };

            // Map Styles
            if (undefined !== propertiesMapOptions.styles) {
                mapOptions.styles = JSON.parse(propertiesMapOptions.styles);
            }

            // Setting Google Map Type
            switch (propertiesMapOptions.type) {
                case 'satellite':
                    mapOptions.mapTypeId = google.maps.MapTypeId.SATELLITE;
                    break;
                case 'hybrid':
                    mapOptions.mapTypeId = google.maps.MapTypeId.HYBRID;
                    break;
                case 'terrain':
                    mapOptions.mapTypeId = google.maps.MapTypeId.TERRAIN;
                    break;
                default:
                    mapOptions.mapTypeId = google.maps.MapTypeId.ROADMAP;
            }

            var propertiesMap = new google.maps.Map(document.getElementById("listing-map"), mapOptions);

            // Street view control positioning
            propertiesMap.getStreetView().setOptions({
                addressControlOptions: {position: google.maps.ControlPosition.BOTTOM_CENTER},
                fullscreenControl: false,
            });

            var overlappingMarkerSpiderfier = new OverlappingMarkerSpiderfier(propertiesMap, {
                markersWontMove: true,
                markersWontHide: true,
                keepSpiderfied: true,
                circleSpiralSwitchover: Infinity,
                nearbyDistance: 50
            });

            var mapBounds = new google.maps.LatLngBounds();
            var openedWindows = [];

            // close previously opened info windows
            window.closeOpenedWindows = function () {
                while (0 < openedWindows.length) {
                    var windowToClose = openedWindows.pop();
                    windowToClose.close();
                }
            };

            // attach info box to marker
            var attachInfoBoxToMarker = function (map, marker, infoBox) {
                google.maps.event.addListener(marker, 'click', function () {
                    closeOpenedWindows();
                    var scale = Math.pow(2, map.getZoom());
                    var offsety = ((100 / scale) || 0);
                    var projection = map.getProjection();
                    var markerPosition = marker.getPosition();
                    var markerScreenPosition = projection.fromLatLngToPoint(markerPosition);
                    var pointHalfScreenAbove = new google.maps.Point(markerScreenPosition.x, markerScreenPosition.y - offsety);
                    var aboveMarkerLatLng = projection.fromPointToLatLng(pointHalfScreenAbove);
                    map.setCenter(aboveMarkerLatLng);
                    map.panTo(aboveMarkerLatLng);
                    infoBox.open(map, marker);
                    openedWindows.push(infoBox);
                    // lazy load info box image to improve performance
                    var infoBoxImage = infoBox.getContent().getElementsByClassName('prop-thumb');
                    if (infoBoxImage.length) {
                        if (infoBoxImage[0].dataset.src) {
                            infoBoxImage[0].src = infoBoxImage[0].dataset.src;
                        }
                    }

                });
            };

            // Loop to generate marker and info windows based on properties data
            var markers = [];
            var map = {
                '&amp;': '&',
                '&#038;': "&",
                '&lt;': '<',
                '&gt;': '>',
                '&quot;': '"',
                '&#039;': "'",
                '&#8217;': "’",
                '&#8216;': "‘",
                '&#8211;': "–",
                '&#8212;': "—",
                '&#8230;': "…",
                '&#8221;': '”'
            };

            for (var i = 0; i < propertiesMapData.length; i++) {

                if (propertiesMapData[i].lat && propertiesMapData[i].lng) {

                    var iconURL = propertiesMapData[i].icon;
                    var size = new google.maps.Size(42, 57);
                    if (window.devicePixelRatio > 1.5) {
                        if (propertiesMapData[i].retinaIcon) {
                            iconURL = propertiesMapData[i].retinaIcon;
                            size = new google.maps.Size(83, 113);
                        }
                    }

                    if ( propertiesMapOptions.marker_type === 'circle' ) {
                        var markerIcon = {
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 25,
                            fillColor: propertiesMapOptions.marker_color,
                            strokeColor: propertiesMapOptions.marker_color,
                            fillOpacity: 0.5,
                            strokeWeight: 0.6
                        }
                    } else {
                        var markerIcon = {
                            url: iconURL,
                            size: size,
                            scaledSize: new google.maps.Size(42, 57),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(21, 56)
                        };
                    }

                    markers[i] = new google.maps.Marker({
                        position: new google.maps.LatLng(propertiesMapData[i].lat, propertiesMapData[i].lng),
                        map: propertiesMap,
                        id: propertiesMapData[i].id,
                        icon: markerIcon,
                        title: propertiesMapData[i].title.replace(/\&[\w\d\#]{2,5}\;/g, function (m) {
                            return map[m];
                        }),  // Decode PHP's html special characters encoding with Javascript
                        animation: google.maps.Animation.DROP,
                        visible: true
                    });

                    // extend bounds
                    mapBounds.extend(markers[i].getPosition());

                    // prepare info window contents
                    var boxText = document.createElement("div");
                    boxText.className = 'map-info-window';
                    var innerHTML = "";

                    // info window image place holder URL to improve performance
                    var infoBoxPlaceholderURL = "";
                    if ((typeof mapStuff !== "undefined") && mapStuff.infoBoxPlaceholder) {
                        infoBoxPlaceholderURL = mapStuff.infoBoxPlaceholder;
                    }

                    if (propertiesMapData[i].thumb) {
                        innerHTML += '<a class="thumb-link" href="' + propertiesMapData[i].url + '">' + '<img class="prop-thumb" src="' + infoBoxPlaceholderURL + '"  data-src="' + propertiesMapData[i].thumb + '" alt="' + propertiesMapData[i].title + '"/>' + '</a>';
                    } else {
                        innerHTML += '<a class="thumb-link" href="' + propertiesMapData[i].url + '">' + '<img class="prop-thumb" src="' + infoBoxPlaceholderURL + '" alt="' + propertiesMapData[i].title + '"/>' + '</a>';
                    }

                    innerHTML += '<div class="rh-gm-thumb-detail">';

                    innerHTML += '<h5 class="prop-title"><a class="title-link" href="' + propertiesMapData[i].url + '">' + propertiesMapData[i].title + '</a></h5>';
                    innerHTML += '<div>'
                    if (propertiesMapData[i].propertyType) {
                        innerHTML += '<span class="type">' + propertiesMapData[i].propertyType + '</span>';
                    }
                    if (propertiesMapData[i].price) {
                        innerHTML += '<p><span class="price">' + propertiesMapData[i].price + '</span></p>';
                    }
                    innerHTML += '</div>';

                    innerHTML += '<div class="arrow-down"></div>';
                    boxText.innerHTML = '<div class="rh-ultra-info-window">'+ innerHTML + '</div>';

                    // info window close icon URL
                    var closeIconURL = "";
                    if ((typeof mapStuff !== "undefined") && mapStuff.closeIcon) {
                        closeIconURL = mapStuff.closeIcon;
                    }

                    var pixelOffset = -70;
                    if (propertiesMapOptions.marker_type === 'circle') {
                        pixelOffset = -22;
                    }

                    // finalize info window
                    var infoWindowOptions = {
                        content: boxText,
                        disableAutoPan: true,
                        maxWidth: 450,
                        alignBottom: true,
                        pixelOffset: new google.maps.Size(-225, pixelOffset),
                        zIndex: null,
                        closeBoxMargin: "8px 8px -24px -16px",
                        closeBoxURL: closeIconURL,
                        infoBoxClearance: new google.maps.Size(1, 1),
                        isHidden: false,
                        pane: "floatPane",
                        enableEventPropagation: false
                    };
                    var currentInfoBox = new InfoBox(infoWindowOptions);

                    // attach info window to marker
                    attachInfoBoxToMarker(propertiesMap, markers[i], currentInfoBox);

                    // apply overlapping marker spiderfier to marker
                    overlappingMarkerSpiderfier.addMarker(markers[i]);
                }
            }

            // Apply overlapping marker spiderfier to marker
            propertiesMap.addListener( "idle", () => {
                if ( markers.length ) {
                    markers.forEach( function ( marker ) {
                        overlappingMarkerSpiderfier.addMarker( marker );
                    } );
                }
            } );

            // fit map to bound as per markers or radius circle.
            if ( circleOnMap ) {
                // Draw searched radius range circle.
                drawCircleOnMap( propertiesMap );
            } else {
                propertiesMap.fitBounds( mapBounds );
            }

            // cluster icon URL
            var clusterIconURL = "";
            if ((typeof mapStuff !== "undefined") && mapStuff.clusterIcon) {
                clusterIconURL = mapStuff.clusterIcon;
            }

            // Markers clusters
            var markerClustererOptions = {
                ignoreHidden: true,
                // gridSize: 60,
                maxZoom: 14,
                styles: [{
                    textColor: '#ffffff',
                    url: clusterIconURL,
                    height: 48,
                    width: 48
                }]
            };
            var markerClusterer = new MarkerClusterer(propertiesMap, markers, markerClustererOptions);

        } else {

            // Fallback Map in Case of No Properties
            var fallback_lat, fallback_lng;
            if (undefined !== propertiesMapOptions.fallback_location && propertiesMapOptions.fallback_location.lat && propertiesMapOptions.fallback_location.lng) {
                fallback_lat = propertiesMapOptions.fallback_location.lat;
                fallback_lng = propertiesMapOptions.fallback_location.lng;
            } else {
                // Default location of Florida in fallback map.
                fallback_lat = '27.664827';
                fallback_lng = '-81.515755';
            }

            var fallBackLocation = new google.maps.LatLng(fallback_lat, fallback_lng);
            var fallBackOptions = {
                center: fallBackLocation,
                zoom: 14,
                maxZoom: 16,
                scrollwheel: false
            };

            // Map Styles
            if (undefined !== propertiesMapOptions.styles) {
                fallBackOptions.styles = JSON.parse(propertiesMapOptions.styles);
            }

            var fallBackMap = new google.maps.Map(document.getElementById("listing-map"), fallBackOptions);

            if ( circleOnMap ) {
                // Draw searched radius range circle if Geo location is enabled.
                drawCircleOnMap( fallBackMap );
            }
        }

    }

    var updateZoomRepaint = function(zoom) {
      markerClusterer.setMaxZoom(zoom); markerClusterer.repaint();
    }

    window.realhomesInfoboxPopupTrigger = function () {

        $('.rh_popup_info_map').each(function (i) {
            $(this).on('mouseenter', function () {
              var property_ID = $(this).attr('data-rh-id').replace( /[^\d.]/g, '' );
              markers.forEach(function (marker){
                if (propertiesMap && marker && marker.id == property_ID) {
                  google.maps.event.trigger(marker, 'click');
                  updateZoomRepaint(1);
                }
              });
            });
        });

        $('.rh_popup_info_map').on('mouseleave', function () {
          updateZoomRepaint(14);
          closeOpenedWindows();
        });
        return false;
    };

    var RHisMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ? true : false;

    if (!RHisMobile) {
        realhomesInfoboxPopupTrigger();
    }
    }

    realhomes_update_google_map( propertiesMapData );

    // Clicking anywhere on the map will close opened infoboxes for better UX
    var OpenedInfoBox = document.getElementsByClassName('infoBox');
    var ListingMap    = document.getElementById('listing-map');

    document.addEventListener('click', function ( event ) {
        if ( typeof OpenedInfoBox[0] !== "undefined" ) {

            // Detect the clicked target
            var map_container     = ListingMap.contains( event.target );
            var infobox_container = OpenedInfoBox[0].contains( event.target );

            // Close infoboxes only if clicked outside the infobox's container
            if ( ! infobox_container && map_container ) {
                closeOpenedWindows();
            }
        }
    });

})(jQuery);