<style>
    /* Simplified CSS for hiding elements */
    .gmnoprint a,
    .gmnoprint span,
    .gmnoprint button,
    .gmnoprint div {
        display: none !important;
    }

    .text-center {
        text-align: center
    }

    .infowindow-content {
        font-family: 'IBM Plex Sans Arabic';
        padding: 5px;
        border-radius: 5px;
        background: #fff;
        color: #333;
        max-width: 250px;
        /* text-align: center; */
    }

    .infowindow-title {
        font-size: 16px;
        font-weight: bold;
    }

    .infowindow-description {
        font-size: 14px;
    }
</style>

<div class="col-xl-12">
    <div class="card card-height-100 border-0 overflow-hidden">
        <div class="card-header">
            <h4 class="card-title mb-0 text-primary">الخريطة</h4>
        </div>
        <div class="card-body">
            <div id="loader" style="position: absolute; top: 50%; left: 50%; z-index: 2;">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
            <div id="map" style="height: 400px; width: 100%;"></div>
        </div>
    </div>
</div>
@push('after-scripts')
    <script>
        let myStyles = [{
                "elementType": "geometry",
                "stylers": [{
                    "color": "#242f3e"
                }]
            },
            {
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#746855"
                }]
            },
            {
                "elementType": "labels.text.stroke",
                "stylers": [{
                    "color": "#242f3e"
                }]
            },
            {
                "featureType": "administrative.locality",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#d59563"
                }]
            },
            {
                "featureType": "poi",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#d59563"
                }]
            },
            {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#263c3f"
                }]
            },
            {
                "featureType": "poi.park",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#6b9a76"
                }]
            },
            {
                "featureType": "road",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#38414e"
                }]
            },
            {
                "featureType": "road",
                "elementType": "geometry.stroke",
                "stylers": [{
                    "color": "#212a37"
                }]
            },
            {
                "featureType": "road",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#9ca5b3"
                }]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#746855"
                }]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.stroke",
                "stylers": [{
                    "color": "#1f2835"
                }]
            },
            {
                "featureType": "road.highway",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#f3d19c"
                }]
            },
            {
                "featureType": "transit",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#2f3948"
                }]
            },
            {
                "featureType": "transit.station",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#d59563"
                }]
            },
            {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#17263c"
                }]
            },
            {
                "featureType": "water",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#515c6d"
                }]
            },
            {
                "featureType": "water",
                "elementType": "labels.text.stroke",
                "stylers": [{
                    "color": "#17263c"
                }]
            }
        ]

        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: {
                    lat: 21.403344853760235,
                    lng: 39.7153238247211
                },
                mapTypeId: google.maps.MapTypeId.HYBRID, // Sets the map type to be displayed.
                // styles: myStyles,
                // scrollwheel: false,
                // zoomControl: false,
                // gestureHandling: 'none',
                // fullscreenControl: false,
                // disableDefaultUI: true
            });

            fetchLocationsAndAddMarkers(map);


            google.maps.event.addListenerOnce(map, 'tilesloaded', function() {
                document.getElementById('loader').style.display = 'none';
            });
        }
        let _iconShape = (key) => {
            var iconBasePath = "{{ asset('build/images/pins') }}/";
            switch (key) {
                case 'SubmittedSection':
                    return iconBasePath + 'forms.svg'
                case 'Ticket':
                    return iconBasePath + 'ticket.svg'
                case 'Support':
                    return iconBasePath + 'support.svg'
                case 'Fine':
                    return iconBasePath + 'fine.svg'
                default:
                    return iconBasePath + 'default.svg'
            }
        }

        function fetchLocationsAndAddMarkers(map) {
            let currentInfoWindow = null; // This will hold the currently opened info window

            $.ajax({
                url: '{{ url('api/track-locations') }}',
                // url: 'https://admin-dev.rmcc.sa/api/track-locations',
                method: 'GET',
                success: function(data) {
                    var locations = data.locations;
                    locations.forEach(function(location) {
                        var marker = new google.maps.Marker({
                            position: {
                                lat: parseFloat(location.latitude),
                                lng: parseFloat(location.longitude)
                            },
                            icon: _iconShape(location.location_type),
                            map: map
                        });

                        marker.addListener('click', function() {
                            if (currentInfoWindow) {
                                currentInfoWindow.close();
                            }
                            var googleMapsLink =
                                `https://www.google.com/maps/search/?api=1&query=${location.latitude},${location.longitude}`;

                            var infowindowContent = `
                                <div class="infowindow-content">
                                    <div style="text-align:center">
                                        <img src="${location.user_info.profile_photo}" alt="User Image" style="width:50px; height:50px; object-fit:cover; 
                    border-radius:50%; border: 1px solid #cab272;background-color:#cab272">
                                    </div>
                                    <div class="infowindow-title text-center " style="color:#cab272">${location.user_info.name}</div>
                                    <div class="infowindow-description">${location.action}</div>
                                    <div class="infowindow-description">نوع الهاتف: ${location.device}</div>
                                    <div class="infowindow-description">تاريخ العملية: ${location.object.created_at}</div>
                                    <a href="${googleMapsLink}" target="_blank" style="text-decoration:none;">المشاهدة في خرائط جوجل</a>
                                </div>
                            `;
                            var infowindow = new google.maps.InfoWindow({
                                content: infowindowContent
                            });
                            infowindow.open(map, marker);
                            currentInfoWindow = infowindow;
                        });
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error:', textStatus, errorThrown);
                }
            });
        }

        window.onload = function() {
            var script = document.createElement('script');
            script.src =
                'https://maps.googleapis.com/maps/api/js?key=AIzaSyBcCQLlfO8grWwpqZZenQasqV4jFw8dO2I&callback=initMap&language=ar';
            script.async = true;
            document.head.appendChild(script);
        };
    </script>
@endpush
