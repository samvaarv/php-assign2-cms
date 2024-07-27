<?php
    include('../reusable/conn.php');
    include('functions.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $ArtworkID = $_POST['ArtworkID'];
        $Source = $_POST['Source'];
        $Title = $_POST['Title'];
        $Medium = $_POST['Medium'];
        $ArtForm = $_POST['ArtForm'];
        $Status = $_POST['Status'];
        $ImageName = $_POST['ImageName'];
        $ImageURL = $_POST['ImageURL'];
        $YearInstalled = $_POST['YearInstalled'];
        $Description = $_POST['Description'];
        $ImageOrientation = $_POST['ImageOrientation'];
        $Artist = $_POST['Artist'];
        $Location = $_POST['Location'];
        $Ward = $_POST['Ward'];
        $WardFullName = $_POST['WardFullName'];
        $Latitude = $_POST['latitude'];  // Add latitude
        $Longitude = $_POST['longitude']; // Add longitude

        // Start transaction
        $connect->begin_transaction();
        try {
            // Update Artist if exists, or insert if new
            $artist_query = "SELECT ArtistID FROM Artists WHERE Artist = ?";
            $stmt = $connect->prepare($artist_query);
            $stmt->bind_param("s", $Artist);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $artist_id = $row['ArtistID'];
            } else {
                $insert_artist_query = "INSERT INTO Artists (Artist) VALUES (?)";
                $stmt = $connect->prepare($insert_artist_query);
                $stmt->bind_param("s", $Artist);
                $stmt->execute();
                $artist_id = $connect->insert_id;
            }

            // Update Location if exists, or insert if new
            $location_query = "SELECT LocationID FROM Locations WHERE Location = ?";
            $stmt = $connect->prepare($location_query);
            $stmt->bind_param("s", $Location);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $location_id = $row['LocationID'];
                // Update location with new coordinates
                $update_location_query = "UPDATE Locations SET Ward = ?, WardFullName = ?, latitude = ?, longitude = ? WHERE LocationID = ?";
                $stmt = $connect->prepare($update_location_query);
                $stmt->bind_param("isddi", $Ward, $WardFullName, $Latitude, $Longitude, $location_id);
                $stmt->execute();
            } else {
                $insert_location_query = "INSERT INTO Locations (`Location`, Ward, WardFullName, latitude, longitude) VALUES (?, ?, ?, ?, ?)";
                $stmt = $connect->prepare($insert_location_query);
                $stmt->bind_param("sisdd", $Location, $Ward, $WardFullName, $Latitude, $Longitude);
                $stmt->execute();
                $location_id = $connect->insert_id;
            }

            // Update Artwork
            $update_query = "UPDATE Artworks SET Source = ?, Title = ?, Medium = ?, ArtForm = ?, `Status` = ?, ImageName = ?, ImageURL = ?, YearInstalled = ?, `Description` = ?, ImageOrientation = ?, ArtistID = ?, LocationID = ?
                            WHERE _id = ?";
            $stmt = $connect->prepare($update_query);
            $stmt->bind_param("sssssssiisiii", $Source, $Title, $Medium, $ArtForm, $Status, $ImageName, $ImageURL, $YearInstalled, $Description, $ImageOrientation, $artist_id, $location_id, $ArtworkID);
            $stmt->execute();

            $update_query = "UPDATE Artworks SET `Description` = ? WHERE _id = ?";
            $stmt = $connect->prepare($update_query);
            $stmt->bind_param("si", $Description, $ArtworkID);
            $stmt->execute();
            // Commit transaction
            $connect->commit();

            // Set session variable after successful update
            $_SESSION['update_success'] = true;

            // Set success message
            set_message("Artwork updated successfully", "alert-success");
            header('Location: ../view_artwork.php?id=' . $ArtworkID);
        } catch (Exception $e) {
            // Rollback transaction on error
            $connect->rollback();
            echo 'FAILED:' . $e->getMessage();
        }
    } else {
        echo 'Invalid request';
    }
?>
