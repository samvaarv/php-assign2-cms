<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" 
integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" 
integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" 
integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCMuFKT_UgYojet9ZifI9OO8nOar2_IsXs&libraries=places&callback=initMap" async defer></script>

<script>
    $(document).ready(function() {
    // show the alert
    setTimeout(function() {
        $(".alert").alert('close');
    }, 5000);
});
</script>
<script>
    function initMap() {
        console.log('initMap called'); // Debugging line
        const artworkLat = <?php echo $row['latitude']; ?>; // Artwork latitude
        const artworkLng = <?php echo $row['longitude']; ?>; // Artwork longitude
        
        // Create a map centered on the artwork location
        const map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: artworkLat, lng: artworkLng },
            zoom: 14
        });

        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer();
        directionsRenderer.setMap(map);
        directionsRenderer.setPanel(document.getElementById('directions-panel'));

        const travelModeSelect = document.getElementById('travel-mode');

        function calculateAndDisplayRoute() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    const userLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                    const destination = new google.maps.LatLng(artworkLat, artworkLng);

                    const request = {
                        origin: userLocation,
                        destination: destination,
                        travelMode: travelModeSelect.value
                    };

                    // Clear the previous directions before rendering new ones
                    directionsRenderer.setDirections({ routes: [] });

                    directionsService.route(request, (result, status) => {
                        if (status === 'OK') {
                            directionsRenderer.setDirections(result);
                        } else {
                            console.error('Directions request failed due to ' + status);
                        }
                    });

                    // Add a marker for the artwork location
                    new google.maps.Marker({
                        position: destination,
                        map: map,
                        title: 'Artwork Location'
                    });
                }, () => {
                    console.error('Error getting user location.');
                });
            } else {
                console.error('Geolocation is not supported by this browser.');
            }
        }

        // Calculate route initially
        calculateAndDisplayRoute();

        // Update route when travel mode changes
        travelModeSelect.addEventListener('change', calculateAndDisplayRoute);
    }

    window.onload = initMap;
</script>