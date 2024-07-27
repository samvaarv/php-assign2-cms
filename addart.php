<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Add Art</title>
        <?php
            include('reusable/styles.php');
        ?>
    </head>
    <body>
        <?php
            include('reusable/nav.php');
            include('inc/functions.php');
        ?>
        <main>
            <section>
                <div class="section-header">
                    <div class="container h-100 align-items-center d-flex">
                        <h2 class="section-header-title text-white text-uppercase">Add New Art</h2>
                    </div>
                </div>
            </section>
            <section class="section-body">  
                <div class="container">  
                    <div class="row">
                        <div class="col">
                        <?php get_message(); ?>
                        </div>
                    </div>                  
                    <form method="POST" action="inc/add_artwork.php" class="row">
                        <div class="form-group col-12 col-md-6 mb-3">    
                            <label class="form-label" for="Source">Source:</label>
                            <input class="form-control" type="text" id="Source" name="Source" required>
                        </div>
                        <div class="form-group col-12 col-md-6 mb-3">
                            <label class="form-label" for="Title">Title:</label>
                            <input class="form-control" type="text" id="Title" name="Title" required>
                        </div>
                        <div class="form-group col-12 col-md-5 mb-3">
                            <label class="form-label" for="Artist">Artist Name:</label>
                            <input class="form-control" type="text" id="Artist" name="Artist" required>
                        </div>
                        <div class="form-group col-12 col-md-3 mb-3">
                            <label class="form-label" for="Medium">Medium:</label>
                            <input class="form-control" type="text" id="Medium" name="Medium" required>
                        </div>
                        <div class="form-group col-12 col-md-4 mb-3">
                            <label class="form-label" for="ArtForm">Art Form:</label>
                            <input class="form-control" type="text" id="ArtForm" name="ArtForm" required>
                        </div>
                        <div class="form-group col-12 col-md-12 mb-3">
                            <label class="form-label" for="Description">Description:</label>
                            <textarea class="form-control" id="Description" name="Description" rows="6"></textarea>
                        </div>
                        <div class="form-group col-12 col-md-6 mb-3">
                            <label class="form-label" for="ImageName">Image Name:</label>
                            <input class="form-control" type="text" id="ImageName" name="ImageName">
                        </div>
                        <div class="form-group col-12 col-md-6 mb-3">
                            <label class="form-label" for="ImageURL">Image URL:</label>
                            <input class="form-control" type="text" id="ImageURL" name="ImageURL">
                        </div>
                        <div class="form-group col-12 col-md-4 mb-3">
                            <label class="form-label" for="Status">Status:</label>
                            <select class="form-select"  id="Status" name="Status">
                                <option value="Existing">Existing</option>
                                <option value="Not Existing">Not Existing</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-4 mb-3">
                            <label class="form-label" for="YearInstalled">Year Installed:</label>
                            <select class="form-select" id="YearInstalled" name="YearInstalled" data-component="date">
                            <?php
                                for ($year = date('Y'); $year >= 1900; $year--) { // Reverse loop, change ++ to --
                                echo '<option value="'.$year.'">' . $year . '</option>';
                                }
                            ?>
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-4 mb-3">
                            <label class="form-label" for="ImageOrientation">Image Orientation:</label>
                            <select class="form-select"  id="ImageOrientation" name="ImageOrientation">
                                <option value="Landscape">Landscape</option>
                                <option value="Portrait">Portrait</option>
                            </select>
                        </div>
                        <h3 class="mt-4">Location Details</h3>
                        <div class="form-group col-12 col-md-6 mb-3">
                            <label class="form-label" for="Location">Address:</label>
                            <input class="form-control" type="text" id="Location" name="Location" required>
                            <ul id="suggestions" class="list-group mt-2" style="display: none;"></ul>
                        </div>
                        <input type="hidden" id="latitude" name="latitude">
                        <input type="hidden" id="longitude" name="longitude">
                        <div class="form-group col-12 col-md-2 mb-3">
                            <label class="form-label" for="Ward">Ward:</label>
                            <input class="form-control" type="number" id="Ward" name="Ward">
                        </div>
                        <div class="form-group col-12 col-md-4 mb-3">
                            <label class="form-label" for="WardFullName">Ward Full Name:</label>
                            <input class="form-control" type="text" id="WardFullName" name="WardFullName">
                        </div>
                        <div class="form-group col-12 mt-4">
                            <button class="btn btn-primary" type="submit">
                                <svg height="45.6" width="125.738"><rect height="45.6" width="125.738"></rect></svg>
                                <span class="btn-text">Submit</span>
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </body>
    <?php
        include('reusable/scripts.php');
    ?>
    <script>
        function initAutocomplete() {
            const input = document.getElementById('Location');
            const suggestions = document.getElementById('suggestions');
            const latitudeField = document.getElementById('latitude');
            const longitudeField = document.getElementById('longitude');
            const autocomplete = new google.maps.places.Autocomplete(input, {
                types: ['geocode']
            });

            autocomplete.addListener('place_changed', function() {
                const place = autocomplete.getPlace();
                if (place.geometry) {
                    input.value = place.formatted_address;
                    latitudeField.value = place.geometry.location.lat();
                    longitudeField.value = place.geometry.location.lng();
                    suggestions.style.display = 'none';
                }
            });

            input.addEventListener('input', function() {
                if (input.value.length > 0) {
                    suggestions.style.display = 'block';
                    suggestions.innerHTML = '';
                    const service = new google.maps.places.AutocompleteService();
                    service.getPlacePredictions({ input: input.value, types: ['geocode'] }, function(predictions, status) {
                        if (status === google.maps.places.PlacesServiceStatus.OK) {
                            predictions.forEach(function(prediction) {
                                const li = document.createElement('li');
                                li.className = 'list-group-item';
                                li.textContent = prediction.description;
                                li.onclick = function() {
                                    input.value = prediction.description;
                                    suggestions.style.display = 'none';
                                    autocomplete.getPlace();
                                };
                                suggestions.appendChild(li);
                            });
                        }
                    });
                } else {
                    suggestions.style.display = 'none';
                }
            });
        }

        window.onload = initAutocomplete;
    </script>
</html>
