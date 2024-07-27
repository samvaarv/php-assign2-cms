<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Art Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" 
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php
        include('reusable/nav.php');
    ?>
    <main>
        <section>
            <div class="section-header">
                <div class="container h-100 align-items-center d-flex">
                    <h2 class="section-header-title text-white text-uppercase">Artworks</h2>
                </div>
            </div>
        </section>
        <section class="section-body">   
            <div class="container">
                <?php
                    if (isset($_SESSION['message'])) {
                        echo '<div class="alert ' . $_SESSION['className'] . '">' . $_SESSION['message'] . '</div>';
                        unset($_SESSION['message']);
                        unset($_SESSION['className']);
                    }
                ?>

                <div class="row" data-masonry='{"percentPosition": true }'>
                <?php
                include 'reusable/conn.php';

                // Set the number of items per page
                $items_per_page = 20;

                // Get the current page number from URL, default is 1
                $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

                // Calculate the offset for the SQL query
                $offset = ($current_page - 1) * $items_per_page;

                // Modify the query to use LIMIT and OFFSET
                $query = "SELECT Artworks._id, Artworks.Title, Artworks.ImageURL, Artworks.YearInstalled, Artists.Artist
                        FROM Artworks
                        JOIN Artists ON Artworks.ArtistID = Artists.ArtistID
                        LIMIT $items_per_page OFFSET $offset";

                // Execute the query
                $result = mysqli_query($connect, $query);

                // Fetch total number of artworks for pagination
                $total_query = "SELECT COUNT(*) as total FROM Artworks";
                $total_result = mysqli_query($connect, $total_query);
                $total_row = mysqli_fetch_assoc($total_result);
                $total_artworks = $total_row['total'];
                $total_pages = ceil($total_artworks / $items_per_page);

                // Function to create pagination links
                function create_page_links($total_pages, $current_page) {
                    $links = '';
                    for ($i = 1; $i <= $total_pages; $i++) {
                        if ($i == $current_page) {
                            $links .= "<li class='page-item active'><a class='page-link' href='?page=$i'>$i</a></li>";
                        } else {
                            $links .= "<li class='page-item'><a class='page-link' href='?page=$i'>$i</a></li>";
                        }
                    }
                    return $links;
                }


                if ($result->num_rows > 0) {
                    foreach($result as $row){
                    echo '<div class="card-art col-12 col-md-6 col-xl-4">
                            <a href="view_artwork.php?id=' . $row["_id"] . '" class="card-art-image d-block">
                                 <img src="' . $row["ImageURL"] . '" class="w-100 h-100" alt="' . $row["YearInstalled"] . '" />
                            </a>
                            <div class="card-art-body">
                                <div class="card-art-info">
                                    <p class="card-art-artist">' . $row["Artist"] . '</p>
                                    <h3 class="card-art-title">' . $row["Title"] . '</h3>
                                </div>
                            </div>
                            <div class="card-art-footer">
                                <a href="view_artwork.php?id=' . $row["_id"] . '" class="card-footer-link"><span class="alt-text">View more</span></a>
                            </div>
                        </div>';
                    }
                } else {
                    echo "No artworks found.";
                }
                ?>        
                </div>
                 <!-- Pagination controls -->
                 <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination flex-wrap justify-content-center">
                            <li class="page-item <?php if($current_page == 1) echo 'disabled'; ?>">
                                <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php echo create_page_links($total_pages, $current_page); ?>
                            <li class="page-item <?php if($current_page == $total_pages) echo 'disabled'; ?>">
                                <a class="page-link" href="?page=<?php echo $current_page + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>         
        </section>
    </main>
    <?php
        include('reusable/scripts.php');
    ?>
</body>
</html>