<?php
// Enable all error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("connection/connect.php");
session_start(); // Add session start if not already done

// Escape and trim the input
$search = isset($_GET['search']) ? trim($db->real_escape_string($_GET['search'])) : '';

// Function to highlight search keywords
function highlight($text, $search)
{
    return preg_replace("/(" . preg_quote($search, "/") . ")/i", '<mark>$1</mark>', $text);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Home || Real Estate</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style>
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-weight: bold;
            border-radius: 8px;
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .booking-btn {
            background-color: #007bff;
        }

        .booking-btn:hover {
            background-color: #0056b3;
            color: darkblue;
        }

        .save-btn {
            background-color: rgb(255, 168, 143);
        }

        .save-btn:hover {
            background-color: rgb(219, 17, 10);
            color: black;
        }

        .swiper-container {
            width: 100%;
            position: relative;
        }

        .swiper-slide img {
            width: 100%;
            border-radius: 8px;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: white;
        }

        .result-box {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        mark {
            background-color: yellow;
            font-weight: bold;
        }

        .img-icon {
            width: 20px;
            margin-right: 5px;
        }
    </style>
</head>

<body class="home">

    <header id="header" class="header-scroll top-header headrom">
        <nav class="navbar navbar-dark">
            <div class="container">
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/Estate_logo.png" alt="" width="18%"> </a>
                <div class="collapse navbar-toggleable-md  float-lg-right" id="mainNavbarCollapse">
                    <ul class="nav navbar-nav">
                    <li class="nav-item">
                            <a class="nav-link active" href="home.php">
                                <img class="img-icon" src="images/Home.png"> Home
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="propertieslist.php">
                                <img class="img-icon" src="images/property.png"> Properties
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link active" href="createlist.php">
                                <img class="img-icon" src="images/list.png">Create List
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="your_bookings.php">
                                <img class="img-icon" src="images/list.png">Bookings
                            </a>
                        </li>

                        <?php
                        if (empty($_SESSION["user_id"])) { // If user is not logged in
                            echo '<li class="nav-item">
                <a href="login.php" class="nav-link active">
                    <img class="img-icon" src="images/login.png"> Login
                </a>
              </li>
              <li class="nav-item">
                <a href="registration.php" class="nav-link active">
                    <img class="img-icon" src="images/register.png"> Register
                </a>
              </li>';
                        } else {
                            echo '
              <li class="nav-item">
                <a href="logout.php" class="nav-link active">
                    <img class="img-icon" src="images/logout.png"> Logout
                </a>
              </li>';
                        }
                        ?>
                    </ul>




                </div>
            </div>
        </nav>

    </header>

    <!-- Search Results -->
    <section class="popular">
        <div class="container">
            <div class="title text-xs-center m-b-30">
                <h2>Search Results</h2>
            </div>

            <div class="row">
                <?php
                if (!empty($search)) {
                    $sql = "SELECT * FROM properties WHERE 
                        title LIKE '%$search%' OR 
                        description LIKE '%$search%' OR 
                        location LIKE '%$search%' OR 
                        property_type LIKE '%$search%' OR 
                        post_type LIKE '%$search%' OR 
                        status LIKE '%$search%' OR 
                        bedrooms LIKE '%$search%' OR 
                        bathrooms LIKE '%$search%' OR 
                        price LIKE '%$search%' OR 
                        area LIKE '%$search%'";

                    $result = $db->query($sql);

                    if ($result && $result->num_rows > 0) {
                        while ($r = $result->fetch_assoc()) {
                            $propertyId = $r['property_id'];
                            $images = array_map('trim', explode(',', $r['images']));

                            echo '<div class="col-xs-12 col-sm-6 col-md-4 food-item"><div class="food-item-wrap">
                                <div class="swiper-container gallery-top-' . $propertyId . '"><div class="swiper-wrapper">';
                            foreach ($images as $img) {
                                echo '<div class="swiper-slide"><img src="admin/property_img/property/' . $img . '" style="width:100%; height:250px; object-fit:cover;"></div>';
                            }
                            echo '</div><div class="swiper-button-next swiper-button-next-' . $propertyId . '"></div>
                                <div class="swiper-button-prev swiper-button-prev-' . $propertyId . '"></div></div>';

                            echo '<div class="swiper-container gallery-thumbs-' . $propertyId . '" style="margin-top: 10px;"><div class="swiper-wrapper">';
                            foreach ($images as $img) {
                                echo '<div class="swiper-slide" style="width: 80px; height: 60px;"><img src="admin/property_img/property/' . $img . '" style="width:100%; height:100%; object-fit:cover; border-radius: 5px;"></div>';
                            }
                            echo '</div></div>';

                            echo '<div class="content">
                                <h5><a href="property.php?property_id=' . $r['property_id'] . '">' . highlight(htmlspecialchars($r['title']), $search) . '</a></h5>
                                <div class="product-name"><strong>Type:</strong> ' . htmlspecialchars($r['property_type']) . '</div>
                                <div class="product-name"><strong>Location:</strong> ' . htmlspecialchars($r['location']) . '</div>
                                <div class="product-name"><strong>Area:</strong> ' . htmlspecialchars($r['area']) . ' sq.ft</div>
                                <div class="product-name"><strong>Bedrooms:</strong> ' . htmlspecialchars($r['bedrooms']) . ' | <strong>Bathrooms:</strong> ' . htmlspecialchars($r['bathrooms']) . '</div>
                                <div class="product-name"><strong>Price:</strong> <span class="fa fa-inr f-s-40"> ' . number_format($r['price']) . '</span></div>
                                <div class="product-name"><a href="admin/property_img/property/' . htmlspecialchars($r['pdf']) . '" target="_blank">View PDF</a></div>
                                <div class="price-btn-block text-center">
                                    <span style="color:' . ($r['status'] == 'available' ? 'green' : ($r['status'] == 'sold' ? 'red' : 'orange')) . ';">' . ucfirst($r['status']) . '</span>
                                </div>
                            </div></div></div>';

                            // Swiper JS initialization
                            echo '<script>
                                var galleryThumbs' . $propertyId . ' = new Swiper(".gallery-thumbs-' . $propertyId . '", {
                                    spaceBetween: 10,
                                    slidesPerView: 3,
                                    watchSlidesVisibility: true,
                                    watchSlidesProgress: true,
                                });
                                var galleryTop' . $propertyId . ' = new Swiper(".gallery-top-' . $propertyId . '", {
                                    spaceBetween: 10,
                                    navigation: {
                                        nextEl: ".swiper-button-next-' . $propertyId . '",
                                        prevEl: ".swiper-button-prev-' . $propertyId . '",
                                    },
                                    thumbs: {
                                        swiper: galleryThumbs' . $propertyId . '
                                    }
                                });
                            </script>';
                        }
                    } else {
                        echo "<div class='col-md-12'><div class='alert alert-warning'>No results found for '<strong>" . htmlspecialchars($search) . "</strong>'</div></div>";
                    }
                } else {
                    echo "<div class='col-md-12'><div class='alert alert-info'>Please enter a search term.</div></div>";
                }
                ?>
            </div>
        </div>
    </section>

    <?php include "chat.php"; ?>
    <?php include "include/footer.php"; ?>

    <!-- Scripts -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>