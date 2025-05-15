<!DOCTYPE html>
<html lang="en">
<?php
include('connection/connect.php');

error_reporting(0);
session_start();

?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
     <!-- Latest Font Awesome CDN -->
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
        .swiper-container {
            position: relative;
            width: 100%;
        }

        .swiper-slide img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: white;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            position: absolute;
        }

        .swiper-button-next {
            right: 10px;
        }

        .swiper-button-prev {
            left: 10px;
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
    <section class="hero bg-image" data-image-src="images/img/header.webp">
        <div class="hero-inner">
            <div class="container text-center hero-text font-white">
                <h1> Welcome to Nilam Website...</h1>

                <div class="banner-form">
                    <form class="form-inline">

                    </form>
                </div>
                <div class="steps">
                    <div class="step-item step1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 21V8l9-6 9 6v13H3z"></path>
                            <path d="M9 21V12h6v9"></path>
                        </svg>
                        <h4><span style="color:white;">1. </span>Choose Property</h4>
                    </div>

                    <div class="step-item step2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path>
                            <path d="M17 12c0 2.8-2.2 5-5 5s-5-2.2-5-5V8c0-2.8 2.2-5 5-5s5 2.2 5 5v4z"></path>
                            <path d="M2 12c0 6.1 4.9 11 11 11s11-4.9 11-11"></path>
                        </svg>
                        <h4><span style="color:white;">2. </span>Take Consultancy</h4>
                    </div>

                    <div class="step-item step3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 12a5 5 0 1 0-4 4l6 6h3v-3h3v-3h-3v-3z"></path>

                        </svg>
                        <h4><span style="color:white;">3. </span>Take Our Nilam</h4>
                    </div>
                </div>


            </div>
        </div>

    </section>
    <section class="popular">
        <div class="container">
            <div class="title text-xs-center m-b-30">
                <h2>Popular Real Estate</h2>
                <p class="lead">Easiest way to view the property among these top 6 options</p>
            </div>
            <div class="row">

                <?php
                include("db_connection.php");
                $query_res = mysqli_query($db, "SELECT * FROM properties WHERE status!='waitinglist'  LIMIT 6");

                $swiperScripts = ''; // Collect JavaScript to initialize Swiper

                while ($r = mysqli_fetch_array($query_res)) {
                    $images = array_map(function ($img) {
                        return trim($img, " '\"");
                    }, explode(',', $r['images']));


                    $propertyId = $r['property_id'];
                ?>

                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="food-item">
                            <div class="food-item-wrap">

                                <!-- Main Swiper -->
                                <div class="swiper-container gallery-top-<?php echo $propertyId; ?>">
                                    <div class="swiper-wrapper">
                                        <?php foreach ($images as $img): ?>
                                            <div class="swiper-slide">
                                                <img src="admin/property_img/property/<?php echo trim($img); ?>"
                                                    class="img-responsive">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="swiper-button-next swiper-button-next-<?php echo $propertyId; ?>"></div>
                                    <div class="swiper-button-prev swiper-button-prev-<?php echo $propertyId; ?>"></div>
                                </div>

                                <!-- Thumbnail Swiper -->
                                <div class="swiper-container gallery-thumbs-<?php echo $propertyId; ?>" style="margin-top:10px;">
                                    <div class="swiper-wrapper">
                                        <?php foreach ($images as $img): ?>
                                            <div class="swiper-slide">
                                                <img src="admin/property_img/property/<?php echo trim($img); ?>" class="img-thumbnail" style="height:60px;width:100px;object-fit:cover;">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div class="content">
                                    <h5><a href="property.php?property_id=<?php echo $propertyId; ?>"><?php echo $r['title']; ?></a></h5>
                                    <div class="product-name"><strong>Type:</strong> <?php echo ucfirst($r['property_type']); ?></div>
                                    <div class="product-name"><strong>Location:</strong> <?php echo $r['location']; ?></div>
                                    <div class="product-name"><strong>Area:</strong> <?php echo $r['area']; ?> sq.ft</div>
                                    <div class="product-name"><strong>Bedrooms:</strong> <?php echo $r['bedrooms']; ?> | <strong>Bathrooms:</strong> <?php echo $r['bathrooms']; ?></div>
                                    <div class="product-name"><strong>Status:</strong>
                                        <span style="color:<?php echo ($r['status'] == 'available' ? 'green' : ($r['status'] == 'sold' ? 'red' : 'orange')); ?>;">
                                            <?php echo ucfirst($r['status']); ?>
                                        </span>
                                    </div>
                                    <div class="price-btn-block text-center">
                                        <span class="fa fa-inr f-s-40"> <?php echo number_format($r['price']); ?></span>
                                    </div>
                                    <div class="button-container" style="display: flex; gap: 10px;">
                                        <a href="viewdetails.php?property_id=<?php echo $propertyId; ?>" class="btn booking-btn">
                                            <i class="fa fa-eye"></i> View Details
                                        </a>
                                        <a href="javascript:void(0);" class="btn save-btn" onclick="confirmSave(<?php echo $propertyId; ?>)">
                                            <i class="fa fa-heart"></i> Save List
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php
                    // Add JS initialization per swiper
                    $swiperScripts .= "
    var thumbs{$propertyId} = new Swiper('.gallery-thumbs-{$propertyId}', {
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
    });
    var main{$propertyId} = new Swiper('.gallery-top-{$propertyId}', {
        spaceBetween: 10,
        navigation: {
            nextEl: '.swiper-button-next-{$propertyId}',
            prevEl: '.swiper-button-prev-{$propertyId}',
        },
        thumbs: {
            swiper: thumbs{$propertyId},
        },
    });
";
                } // end while
                ?>

            </div> <!-- end .row -->
        </div> <!-- end .container -->
    </section>









    <?php include "include/footer.php" ?>


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