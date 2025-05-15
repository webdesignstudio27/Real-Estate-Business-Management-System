<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION["user_id"])) {
        echo "<script>alert('Please login to book a property.'); window.location='login.php';</script>";
        exit;
    }
}

$u_id = $_SESSION['user_id']; // Store the logged-in user's ID

$stmt = $db->prepare("SELECT * FROM properties WHERE u_id = ?");
$stmt->bind_param("i", $u_id); // "i" means integer
$stmt->execute();
$query_res = $stmt->get_result();
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="icon" href="#">
    <title>Home || Real Estate </title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: none;
            color: white;
        }

        /* Booking Button */
        .booking-btn {
            background-color: #007bff;

            /* Blue */
        }

        .booking-btn:hover {
            background-color: #0056b3;
            /* Darker Blue */
            color: darkblue
        }

        /* Contact Button */
        .contact-btn {
            background-color: #28a745;
            /* Green */
        }

        .contact-btn:hover {
            background-color: #1e7e34;
            color: #e68900
                /* Darker Green */
        }

        /* EMI Button */
        .emi-btn {
            background-color: #ff9800;
            /* Orange */
        }

        .emi-btn:hover {
            background-color: #e68900;
            color: red
                /* Darker Orange */
        }

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


        .custom-submenu {
            position: absolute;
            top: 100%;
            /* Show directly below the profile */
            right: 1;
            /* Align right if the menu is on the right side */
            background-color: transparent;
            border-radius: 8px;
            padding: 10px;
            display: none;
            z-index: 999;
            min-width: 180px;
            min-width: 200px;

        }

        .custom-submenu a {
            display: block;
            color: #000000;
            padding: 8px 10px;
            text-decoration: none;
            white-space: nowrap;
            gap: 10px;
            align-items: left;
        }

        .custom-submenu a:hover {
            background-color: rgba(140, 224, 136, 0.432);
            border-radius: 5px;
        }

        .nav-item:hover .custom-submenu {
            display: block;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                            <form id="search-form" action="search_seller.php" method="GET">
                                <input type="search" placeholder="Search here..." name="search" id="search-box" required>
                                <button type="submit" id="search-btn" class="fas fa-search" style="background: none; border: none;"></button>

                                <i class="fas fa-times" id="close"></i>
                            </form>
                        </li>
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
              <li class="nav-item position-relative">
                                    <a href="#" class="nav-link active" id="profileToggle">
                                        <img class="img-icon" src="images/login.png"> Profile
                                    </a>
                                    <div class="custom-submenu" id="profileSubMenu">
                                        <a href="update_seller.php" class="nav-link active">
                                            <img class="img-icon" src="images/edit_user.png"> Update Account
                                        </a>
                                        <a href="logout.php" class="nav-link active">
                                            <img class="img-icon" src="images/logout.png"> Logout
                                        </a>
                                    </div>
                                </li>';
                        }
                        ?>
                    </ul>




                </div>
            </div>
        </nav>

    </header>


    <section class="popular">
        <div class="container">
            <div class="title text-xs-center m-b-30">
                <h2>Properties List</h2>

            </div>
            <div class="row">

                <?php
                while ($r = $query_res->fetch_assoc()) {
                    $propertyId = $r['property_id'];
                    $images = array_map(function ($img) {
                        return trim($img, " '\"");
                    }, explode(',', $r['images']));
                    echo '  
            <div class="col-xs-12 col-sm-6 col-md-4 food-item">
                <div class="food-item-wrap">
                    <!-- Swiper Main -->
            <div class="swiper-container gallery-top-' . $propertyId . '">
                <div class="swiper-wrapper">';
                    foreach ($images as $img) {
                        echo '<div class="swiper-slide">
                                <img src="admin/property_img/property/' . trim($img) . '" style="width:100%; height:250px; object-fit:cover;" />
                              </div>';
                    }
                    echo '      </div>
                <div class="swiper-button-next swiper-button-next-' . $propertyId . '"></div>
                <div class="swiper-button-prev swiper-button-prev-' . $propertyId . '"></div>
            </div>

            <!-- Swiper Thumbnails -->
            <div class="swiper-container gallery-thumbs-' . $propertyId . '" style="margin-top: 10px;">
                <div class="swiper-wrapper">';
                    foreach ($images as $img) {
                        echo '<div class="swiper-slide" style="width: 80px; height: 60px;">
                                <img src="admin/property_img/property/' . trim($img) . '" style="width:100%; height:100%; object-fit:cover; border-radius: 5px;" />
                              </div>';
                    }
                    echo '      </div>
            </div>        
                    <div class="content">
                        <h5><a href="property.php?property_id=' . $r['property_id'] . '">' . htmlspecialchars($r['title']) . '</a></h5>
                        <div class="product-name"><strong>Type:</strong> ' . ucfirst(htmlspecialchars($r['property_type'])) . '</div>
                        <div class="product-name"><strong>Location:</strong> ' . htmlspecialchars($r['location']) . '</div>
                        <div class="product-name"><strong>Area:</strong> ' . htmlspecialchars($r['area']) . ' sq.ft</div>
                        <div class="product-name"><strong>Bedrooms:</strong> ' . htmlspecialchars($r['bedrooms']) . ' | <strong>Bathrooms:</strong> ' . htmlspecialchars($r['bathrooms']) . '</div>
                        <div class="product-name"><strong>Price:</strong> 
                            <span class="fa fa-inr f-s-40"> ' . number_format($r['price']) . '</span> 
                        </div>
                        <div class="product-name">
                            <p><a href="http://localhost/Estate/admin/property_img/property/' . htmlspecialchars($r['pdf']) . '" target="_blank">View PDF</a></p>
                        </div>
                        <div class="price-btn-block text-center" style="display: flex; justify-content: space-between; align-items: center; max-width: 300px;">
                    <span style="color: ' . ($r['status'] == 'available' ? 'green' : ($r['status'] == 'sold' ? 'red' : 'orange')) . ';">
                        ' . ucfirst(htmlspecialchars($r['status'])) . '
                    </span>
                     <div class="btn-right">';
                    if ($r['status'] == 'waitinglist') {
                        echo '<a href="update_property.php?property_id=' . $propertyId . '" class="btn btn-warning" style="color:rgb(228, 227, 233); font-size: 14px; text-decoration: none;">Edit Property</a>';
                    } elseif ($r['status'] == 'pending') {
                        echo '<a href="checkout.php?property_id=' . $propertyId . '" class="btn btn-success" style="color:rgb(243, 243, 243); font-size: 14px; text-decoration: none;">Make Payment</a>';
                    }
                    echo '</div>
                    
                    
                </div>
                    </div>
                </div>
            </div>';


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

                ?>



            </div>
        </div>
    </section>



    <?php include "include/footer.php" ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/jquerys.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>