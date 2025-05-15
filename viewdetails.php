<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();

// Check if property_id is set in the URL
if (isset($_GET['property_id']) && is_numeric($_GET['property_id'])) {
    $property_id = $_GET['property_id'];

    // Use prepared statements to prevent SQL injection
    $query = "SELECT properties.*, users.phone 
              FROM properties 
              JOIN users ON properties.u_id = users.u_id 
              WHERE properties.property_id = ?";

    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $property_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the property exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<script>alert('Property not found!'); window.location='index.php';</script>";
        exit;
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid request!'); window.location='index.php';</script>";
    exit;
}
?>


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>Home || Real Estate </title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>


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

        .thumb-swiper .swiper-slide {
            height: 80px;
            opacity: 0.6;
            cursor: pointer;
        }

        .thumb-swiper .swiper-slide-thumb-active {
            opacity: 1;
            border: 2px solid #007bff;
        }

        .main-swiper img,
        .thumb-swiper img {
            width: 100%;
            height: auto;
            object-fit: cover;
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
    </style>

    <!-- EMI Sidebar Styles -->
    <style>
        .emi-sidebar {
            position: fixed;
            top: 0;
            right: -350px;
            width: 300px;
            height: 10%;
            background: #fff;
            box-shadow: -2px 0px 10px rgba(0, 0, 0, 0.2);
            transition: 0.5s;
            padding: 20px;
            z-index: 1000;
            margin-top: 6%;
        }

        .emi-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 18px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }

        .emi-close-btn {
            font-size: 30px;
            cursor: pointer;
            color:rgb(0, 0, 0);
        }
        .emi-close-btn:hover {
            color:rgb(238, 37, 37);
        }

        .emi-content label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        .emi-content input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .calculate-btn {
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            margin-top: 15px;
            border-radius: 5px;
        }

        .calculate-btn:hover {
            background-color: #0056b3;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

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
        <li class="nav-item position-relative">
            <a href="#" class="nav-link active" id="profileToggle">
                <img class="img-icon" src="images/login.png"> Profile
            </a>
            <div class="custom-submenu" id="profileSubMenu">
                <a href="update_account.php" class="nav-link active">
                    <img class="img-icon" src="images/edit_user.png"> Update Account
                </a>
                <a href="logout.php" class="nav-link active">
                    <img class="img-icon" src="images/logout.png"> Logout
                </a>
            </div>
        </li>
        ';
                        }
                        ?>
                    </ul>




                </div>
            </div>
        </nav>

    </header>

    <!-- Property Details Section -->
    <section class="popular">
        <div class="container">
            <div class="title text-xs-center m-b-30">
                <h2></h2>
                <p class="lead"></p>
            </div>
            <div class="row">
                <?php
                // Remove single quotes and explode by comma
                $rawImages = str_replace("'", "", $row['images']);
                $images = explode(",", $rawImages);
                ?>


                <div class="col-md-6">
                    <!-- Main Swiper -->
                    <div class="swiper main-swiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($images as $image): ?>
                                <div class="swiper-slide">
                                    <img src="admin/property_img/property/<?php echo trim($image); ?>" class="img-fluid" alt="Property Image">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Thumbnail Swiper -->
                    <div class="swiper thumb-swiper mt-2">
                        <div class="swiper-wrapper">
                            <?php foreach ($images as $image): ?>
                                <div class="swiper-slide">
                                    <img src="admin/property_img/property/<?php echo trim($image); ?>" class="img-fluid" alt="Thumbnail">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>


                <!-- Property Information -->
                <div class="col-md-6">

                    <h2><?php echo $row['title']; ?></h2>
                    <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
                    <p><strong>Price:</strong> ₹<?php echo number_format($row['price']); ?></p>
                    <p><strong>Type:</strong> <?php echo $row['property_type']; ?></p>
                    <p><strong>Area:</strong> <?php echo $row['area']; ?> sq.ft</p>
                    <p><strong>Bedrooms:</strong> <?php echo $row['bedrooms']; ?> |<strong>Bathrooms:</strong> <?php echo $row['bathrooms']; ?> </p>
                    <p><?php echo $row['description']; ?></p>

                    <a href="booking.php?property_id=<?php echo $row['property_id']; ?>" class="btn booking-btn">
                        <i class="fa fa-eye"></i>Booking
                    </a>

                    <a href="tel:<?php echo $row['phone']; ?>" class="btn contact-btn">
                        <i class="fa fa-phone"></i> Contact
                    </a>


                    <a href="javascript:void(0);" class="btn emi-btn" onclick="openEMI()">
                        <i class="fa fa-money"></i> EMI
                    </a>

                </div>
            </div>
        </div>

        <div id="emi-sidebar" class="emi-sidebar">
            <div class="emi-header">
                <h3>EMI Calculator</h3>
                <span class=" emi-close-btn" onclick="closeEMI()">&times;</span>
            </div>
            <div class="emi-content">
                <label>Loan Amount (₹):</label>
                <input type="number" id="loanAmount" value="<?php echo $row['price']; ?>" readonly>

                <label>Interest Rate (% per annum):</label>
                <input type="number" id="interestRate" placeholder="Enter rate" required>

                <label>Years:</label>
                <input type="number" id="tenure" placeholder="Enter years" required>

                <button class="btn calculate-btn" onclick="calculateEMI()">Calculate</button>

                <h4>Monthly EMI: ₹<span id="emi-result">0</span></h4>
            </div>
        </div>
    </section>

    <script>
        function openEMI() {
            document.getElementById("emi-sidebar").style.right = "0";
        }

        function closeEMI() {
            document.getElementById("emi-sidebar").style.right = "-350px";
        }

        function calculateEMI() {
            let P = parseFloat(document.getElementById("loanAmount").value);
            let R = parseFloat(document.getElementById("interestRate").value) / 100 / 12;
            let N = parseFloat(document.getElementById("tenure").value) * 12;

            if (P && R && N) {
                let emi = (P * R * Math.pow(1 + R, N)) / (Math.pow(1 + R, N) - 1);
                document.getElementById("emi-result").innerText = emi.toFixed(2);
            } else {
                alert("Please enter valid inputs.");
            }
        }
    </script>
    <script>
        var thumbSwiper = new Swiper(".thumb-swiper", {
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
        });

        var mainSwiper = new Swiper(".main-swiper", {
            spaceBetween: 10,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            thumbs: {
                swiper: thumbSwiper,
            },
        });
    </script>


    <?php include "chat.php" ?>
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
</body>

</html>