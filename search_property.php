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
    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style>
        #search-icon {
            right: 30rem;
            cursor: pointer;
            margin-left: 1rem;
            height: 3rem;
            line-height: 3rem;
            width: 3rem;
            text-align: center;
            font-size: 1rem;
            color: var(--black);
            border-radius: 50%;
            background: #eee;
            align-items: right;
        }

        #search-icon:hover {
            color: #fff;
            background: var(--green);
            transform: rotate(360deg);
        }


        header .icons #menu-bars {
            display: none;
        }

        #search-form {
            position: absolute;
            top: 40%;
            /* Align with the middle of the search icon */
            transform: translateY(-50%);
            /* Center vertically */
            right: 5rem;
            /* Adjust to show beside the search icon */
            height: 1.2cm;
            max-width: 280px;
            z-index: 1004;
            background: rgba(228, 225, 225, 0.8);
            display: none;
            /* Initially hidden */
            padding: 10px;
            transition: all 0.4s ease;
            border-radius: 50px;
            margin-top: -20%;
        }

        /* Show search form when active */
        #search-form.active {
            display: flex;
            align-items: center;
            top: 2.5rem;
            min-width: 270px;

        }

        /* Search box styling */
        #search-box {
            width: 100%;
            padding: 10px;
            font-size: 1.3rem;
            border: none;
            color: #000000 !important;
            background: rgb(148, 148, 148);
            border-bottom: 2px solid #052c6f;
            outline: none;
            transition: width 0.4s ease;
            height: 1cm;
            border-radius: 50px;

        }

        #search-box::placeholder {
            color: #030303;
            font-size: smaller;
        }

        #search-box:hover {
            background-color: rgb(68, 99, 123);
            font-size: 1rem;
        }

        #search {
            position: absolute;
            right: 37px;
            top: 15px;
            color: rgb(255, 255, 255);
            font-size: 1rem;
            cursor: pointer;
            display: inline-block;
            /* Ensure it behaves like a block-level element */
        }

        #close {
            position: absolute;
            right: 60px;
            top: 15px;
            color: rgb(255, 255, 255);
            font-size: 1rem;
            cursor: pointer;
            display: inline-block;
            /* Ensure it behaves like a block-level element */
        }

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
                            <i class="fas fa-search" id="search-icon"></i>
                            <form id="search-form" action="search_property.php" method="GET">
                                <input type="search" placeholder="Search here..." name="search" id="search-box" required>
                                <button type="submit" id="search-btn" class="fas fa-search" style="background: none; border: none;"></button>

                                <i class="fas fa-times" id="close"></i>
                            </form>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">
                                <img class="img-icon" src="images/Home.png"> Home
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="properties.php">
                                <img class="img-icon" src="images/property.png"> Properties
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="your_orders.php">
                                <img class="img-icon" src="images/booking.png">Bookings
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="list.php">
                                <img class="img-icon" src="images/favourite.png">Saved List
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
                    $sql = "SELECT * FROM properties 
                    WHERE status = 'available' AND (
                        title LIKE '%$search%' OR 
                        description LIKE '%$search%' OR 
                        location LIKE '%$search%' OR 
                        property_type LIKE '%$search%' OR 
                        post_type LIKE '%$search%' OR 
                        bedrooms LIKE '%$search%' OR 
                        bathrooms LIKE '%$search%' OR 
                        price LIKE '%$search%' OR 
                        area LIKE '%$search%'
                    )";
            

                    $result = $db->query($sql);

                    if ($result && $result->num_rows > 0) {
                        while ($r = $result->fetch_assoc()) {
                            $propertyId = $r['property_id'];
                            $images = array_map(function ($img) {
                                return trim($img, " '\"");
                            }, explode(',', $r['images']));


                            echo '<div class="col-xs-12 col-sm-6 col-md-4 food-item">
        <div class="food-item-wrap">
                                        <div class="swiper-container gallery-top-' . $propertyId . '">
                                            <div class="swiper-wrapper">';
                            foreach ($images as $img) {
                                echo '<div class="swiper-slide"><img src="admin/property_img/property/' . $img . '" height="250" style="object-fit:cover;"></div>';
                            }
                            echo '</div>
                                            <div class="swiper-button-next swiper-button-next-' . $propertyId . '"></div>
                                            <div class="swiper-button-prev swiper-button-prev-' . $propertyId . '"></div>
                                        </div>

                                        <div class="swiper-container gallery-thumbs-' . $propertyId . '" style="margin-top: 10px;">
                                            <div class="swiper-wrapper">';
                            foreach ($images as $img) {
                                echo '<div class="swiper-slide" style="width: 80px; height: 60px;"><img src="admin/property_img/property/' . $img . '" style="width:100%; height:100%; object-fit:cover;"></div>';
                            }
                            echo '</div>
                                        </div>

                                        <h5><a href="property.php?property_id=' . $propertyId . '">' . highlight($r['title'], $search) . '</a></h5>
                                        <div><strong>Type:</strong> ' . highlight(ucfirst($r['property_type']), $search) . '</div>
                                        <div><strong>Location:</strong> ' . highlight($r['location'], $search) . '</div>
                                        <div><strong>Area:</strong> ' . $r['area'] . ' sq.ft</div>
                                        <div><strong>Bedrooms:</strong> ' . $r['bedrooms'] . ' | <strong>Bathrooms:</strong> ' . $r['bathrooms'] . '</div>
                                        <div><strong>Status:</strong> <span style="color:' . ($r['status'] == 'available' ? 'green' : 'red') . ';">' . ucfirst($r['status']) . '</span></div>
                                        <div style="font-size: 20px; font-weight: bold;">₹ ' . highlight((number_format($r['price'])), $search ). '</div>
                                        <div style="margin-top: 10px; display: flex; gap: 10px;">
                                            <a href="viewdetails.php?property_id=' . $propertyId . '" class="btn booking-btn" style="flex:1;"><i class="fa fa-eye"></i> View</a>
                                            <a href="javascript:void(0);" class="btn save-btn" style="flex:1;" onclick="confirmSave(' . $propertyId . ')"><i class="fa fa-heart"></i> Save</a>
                                        </div>
                                    </div>
                                </div>';

                            // JS to init swiper
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
                        echo "<div class='col-md-12'><div class='alert alert-warning'>No results found for '<strong>$search</strong>'</div></div>";
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
    <script src="js/jquery.js"></script>
    <script src="js/jquerys.js"></script>

    <script src="js/scripts.js"></script>

</body>

</html>