<!-- User map modal -->
<div class="modal fade" id="userMap" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header bg-primary p-3">
                <h5 class="modal-title text-white" id="mapLabel">{{ trans('translation.map') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
                    id="close-modal"></button>
            </div>
            <div data-simplebar class="modal-body" style="height: 620px">
                <div id="modalContent">
                    <div class="row">
                        <div class="col-lg-2 text-center">

                            <div class=" mt-md-0">
                                <img class="img-thumbnail rounded-circle avatar-lg" alt="200x200" id="userImage"
                                     data-holder-rendered="true">
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="row">
                                @foreach ($user_data_info = ['userTrackName', 'userTrackPhone', 'userTrackEmail', 'userTrackBirthday'] as $user)
                                    @component('components.data-row', ['id' => $user, 'div_col' => 'col-lg-6','label_col'=>'col-lg-6'])
                                    @endcomponent
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row p-2">
                        <div class="border"></div>
                    </div>
                    <div id="locationDetailsSection">
                        <div class="row">
                            <h6 class="card-title my-3">
                                {{trans('translation.userTrackOperationsDetails')}}
                            </h6>

                            <div data-simplebar class="col-lg-6 rounded-4" style="height: 400px">
                                <div id="cardsContainer"></div>
                            </div>
                            <div class="col-lg-6">
                                <div id="leafletmap" style="height: 400px;  overflow: hidden;width: 100%">
                                    <div id="leaflet-map-group-control" style="height: 100%; border-radius: .2rem;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="border-dashed border-top mx-2 p-2"></div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-subtle-danger" data-bs-dismiss="modal"><i
                            class="bi bi-x-lg align-baseline me-1"></i> {{ trans('translation.close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
    <script src="{{ URL::asset('build/libs/prismjs/prism.js') }}"></script>
    <script src="{{ URL::asset('build/libs/leaflet/leaflet.js') }}"></script>

    <script>
        // let usersJson = @json($usersJson);
        let userData = @json($user_data_info);
        $(document).ready(function() {
            $('#userMap').on('shown.bs.modal', function(e) {
                initializeMap(e);
                // $('#userMap').empty();
            });
        });

        function initializeMap(e) {
            try {
                let button = $(e.relatedTarget);
                getUserTrackLocation(button.attr('data-user-id')); // = filterUserSelected(button.attr('data-user-id')); 
                
            } catch (error) {
                console.error("Error initializing map:", error);
            }
        }

        function getUserTrackLocation(userId){
            // let user;
            $.ajax({
                type: "GET",
                url: "{{ url('/') }}" + '/users/track-location/' + userId ,
                dataType: "json",
                success: function(response, jqXHR, xhr) {
                    // console.log(response);
                    // console.log(response.user);
                    // console.log(response.user.name);

                    let user = response.user;
                    userData.map((item) => {
                        document.getElementById(item).innerHTML = user[item.replace('userTrack', '').toLowerCase()];
                    })
                    document.getElementById('userImage').src = user.profile_photo; // Assuming 'imageUrl' is the property name in your user object
                    if (window.leafletMap) {
                        window.leafletMap.remove();
                    }

                    setupMapLocations(user);

                },
                error: function(response, jqXHR, xhr) {
                    console.log(response);
                    Toast.fire({
                        icon: "error",
                        title: "{{ trans('translation.something went wrong') }}"
                    });
                },
            });

        }

        function setupMapLocations(user) {
            if (user.track_locations.length > 0) {
                setupCards(user);
                setupMap(user.track_locations);
            } else {
                setupEmptyMap();
                displayNoResultsMessage();
            }
        }

        function displayNoResultsMessage() {
            $('#cardsContainer').empty(); // Clearing the cards container
            $('#cardsContainer').html(
                '<p class="text-center text-primary-emphasis">{{trans('')}}</p>');
        }

        function setupCards(user) {
            $('#cardsContainer').empty();
            user.track_locations.forEach(function(location, index) {
                let cardHtml = createCardHtml(location, index);
                $('#cardsContainer').append(cardHtml);
            });
            $('#locationDetailsSection').show();
            setupCardClickEvents(user.track_locations);
        }

        function setupCardClickEvents(track_locations) {
            $('.zoomButton').click(function() {
                let locationId = $(this).data('location-id');
                let selectedLocation = track_locations[locationId];
                if (window.leafletMap && selectedLocation) {
                    window.leafletMap.setView([selectedLocation.latitude, selectedLocation.longitude], 15);
                }
                toggleButtonStyle(this);
            });
        }

        function toggleButtonStyle(clickedButton) {
            $('.zoomButton').removeClass('btn-info').addClass('btn-primary');
            $(clickedButton).removeClass('btn-primary').addClass('btn-info');
        }

        function setupMap(track_locations) {
            let firstMarker = track_locations[0];
            window.leafletMap = L.map('leaflet-map-group-control').setView([firstMarker.latitude, firstMarker.longitude],
                15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attributionControl: false
            }).addTo(window.leafletMap);

            let markerCoords = track_locations.map(location => addMarkerAndGetCoords(location));
            drawPolylines(markerCoords);
        }

        function addMarkerAndGetCoords(markerData) {
            let marker = L.marker([markerData.latitude, markerData.longitude]).addTo(window.leafletMap);
            marker.bindPopup(markerData.action);
            return [markerData.latitude, markerData.longitude];
        }

        function drawPolylines(markerCoords) {
            let color = '#b6a067';
            for (let i = 0; i < markerCoords.length - 1; i++) {
                L.polyline([markerCoords[i], markerCoords[i + 1]], {
                    color: color
                }).addTo(window.leafletMap);
            }
        }

        function setupEmptyMap() {
            window.leafletMap = L.map('leaflet-map-group-control').setView([21.3891, 39.8579], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attributionControl: false
            }).addTo(window.leafletMap);
        }

        function createCardHtml(location, index) {
            let formattedDate = formatLocationDate(location.created_at);
            return `
                <div class="card border-start border-primary border-3">
                    <div class="card-body">
                        <a href="#!" class="btn btn-primary btn-icon btn-sm float-end zoomButton" data-location-id="${index}">
                            <i class="bi bi-arrows-fullscreen"></i>
                        </a>
                        <h6 class="text-truncate"> ${location.action}</h6>
                        <p class="text-muted mb-0">
                            <i class="bi bi-clock align-baseline me-1"></i> ${formattedDate}
                            <a href="https://www.google.com/maps?q=${location.latitude},${location.longitude}" target="_blank">
                                <i class="bi bi-pin-map align-baseline mx-1"></i> ${location.latitude},${location.longitude}
                            </a>
                            <i class="mdi mdi-devices align-baseline mx-1"></i> ${location.device}
                        </p>
                    </div>
                </div>`;
        }

        function formatLocationDate(dateString) {
            let date = new Date(dateString);
            return date.toLocaleDateString('ar', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                calendar: 'islamic',
                numberingSystem: 'arab'
            });
        }

        function filterUserSelected(user_id) {
            return usersJson.filter(item => item.id == user_id)[0];
        }
    </script>
@endpush
