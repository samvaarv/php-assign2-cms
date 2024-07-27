<?php
    include('../functions.php');
    require('../reusable/conn.php');

    session_start();

    // Validate and sanitize input
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $artwork_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        if ($artwork_id !== false && $artwork_id > 0) {
            // Start a transaction
            $connect->begin_transaction();
            try {
                // Get ArtistID and LocationID from the artworks table
                $query = "SELECT ArtistID, LocationID FROM Artworks WHERE _id = ?";
                $stmt = $connect->prepare($query);
                $stmt->bind_param("i", $artwork_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                $artist_id = $row['ArtistID'];
                $location_id = $row['LocationID'];

                // Delete artwork record
                $delete_artwork_query = "DELETE FROM Artworks WHERE _id = ?";
                $stmt = $connect->prepare($delete_artwork_query);
                $stmt->bind_param("i", $artwork_id);
                $stmt->execute();

                // Delete artist record
                $delete_artist_query = "DELETE FROM Artists WHERE ArtistID = ?";
                $stmt = $connect->prepare($delete_artist_query);
                $stmt->bind_param("i", $artist_id);
                $stmt->execute();

                // Delete location record
                $delete_location_query = "DELETE FROM Locations WHERE LocationID = ?";
                $stmt = $connect->prepare($delete_location_query);
                $stmt->bind_param("i", $location_id);
                $stmt->execute();

                // Commit transaction
                $connect->commit();

                $_SESSION['message'] = "Artwork and related information deleted successfully.";
                $_SESSION['className'] = "alert-success";
                header("Location: ../index.php");
            } catch (Exception $e) {
                // Rollback transaction if an error occurs
                $connect->rollback();
                $_SESSION['message'] = "Error: " . $e->getMessage();
                $_SESSION['className'] = "alert-danger";
                header("Location: ../view_artwork.php?status=error");
            }
        } else {
            $_SESSION['message'] = "Invalid artwork ID.";
            $_SESSION['className'] = "alert-danger";
            header("Location: ../view_artwork.php?status=error");
        }
    } else {
        $_SESSION['message'] = "No artwork ID provided.";
        $_SESSION['className'] = "alert-danger";
        header("Location: ../view_artwork.php?status=error");
    }
?>
