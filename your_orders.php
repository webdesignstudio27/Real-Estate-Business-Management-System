<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();

if (empty($_SESSION['user_id'])) {
    header('location:login.php');
} else {
?>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="#">
        <title>My Orders</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/font-awesome.min.css" rel="stylesheet">
        <link href="css/animsition.min.css" rel="stylesheet">
        <link href="css/animate.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <style type="text/css" rel="stylesheet">
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

            .indent-small {
                margin-left: 5px;
            }

            .form-group.internal {
                margin-bottom: 0;
            }

            .dialog-panel {
                margin: 10px;
            }

            .datepicker-dropdown {
                z-index: 200 !important;
            }

            .panel-body {
                background: #e5e5e5;
                /* Old browsers */
                background: -moz-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
                /* FF3.6+ */
                background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, #e5e5e5), color-stop(100%, #ffffff));
                /* Chrome,Safari4+ */
                background: -webkit-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
                /* Chrome10+,Safari5.1+ */
                background: -o-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
                /* Opera 12+ */
                background: -ms-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
                /* IE10+ */
                background: radial-gradient(ellipse at center, #e5e5e5 0%, #ffffff 100%);
                /* W3C */
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#e5e5e5', endColorstr='#ffffff', GradientType=1);
                font: 600 15px "Open Sans", Arial, sans-serif;
            }

            label.control-label {
                font-weight: 600;
                color: #777;
            }

            /* 
table { 
	width: 750px; 
	border-collapse: collapse; 
	margin: auto;
	
	}

/* Zebra striping */
            /* tr:nth-of-type(odd) { 
	background: #eee; 
	}

th { 
	background: #404040; 
	color: white; 
	font-weight: bold; 
	
	}

td, th { 
	padding: 10px; 
	border: 1px solid #ccc; 
	text-align: left; 
	font-size: 14px;
	
	} */
            @media only screen and (max-width: 760px),
            (min-device-width: 768px) and (max-device-width: 1024px) {

                /* table { 
	  	width: 100%; 
	}

	
	table, thead, tbody, th, td, tr { 
		display: block; 
	} */


                /* thead tr { 
		position: absolute;
		top: -9999px;
		left: -9999px;
	}
	
	tr { border: 1px solid #ccc; } */

                /* td { 
		
		border: none;
		border-bottom: 1px solid #eee; 
		position: relative;
		padding-left: 50%; 
	}

	td:before { 
		
		position: absolute;
	
		top: 6px;
		left: 6px;
		width: 45%; 
		padding-right: 10px; 
		white-space: nowrap;
		
		content: attr(data-column);

		color: #000;
		font-weight: bold;
	} */

            }
        </style>

    </head>

    <body>


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
                                </li>';
                            }
                            ?>
                        </ul>




                    </div>
                </div>
            </nav>

        </header>


        <div class="page-wrapper">
            <div class="inner-page-hero bg-image" data-image-src="images/img/pimg.jpg">
                <div class="container"> </div>
            </div>
            <div class="result-show">
                <div class="container">
                    <div class="row">
                    </div>
                </div>
            </div>

            <section class="restaurants-page">
                <section class="popular">
                    <div class="container">
                        <div class="row">
                            <h1>Your Booked Property</h1>
                            <div class="col-xs-12">
                            </div>
                            <div class="col-xs-12">
                                <div class="bg-gray">
                                    <div class="row">
                                    <div style="margin-top: 10px;">
                                                <a href="javascript:history.back()" style="color: #5c4ac7; font-size: 14px;">Back</a>
                                            </div>
                                        <table class="table table-bordered table-hover">
                                            <thead style="background: #404040; color:white;">
                                                <tr>
                                                    <th>Property</th>
                                                    <th>Price</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                    <th>Generate Bill</th> <!-- New column -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query_res = mysqli_query(
                                                    $db,
                                                    "SELECT bookings.booking_id, bookings.status AS booking_status, bookings.created_at, 
            bookings.amount_paid, properties.title 
         FROM bookings 
         INNER JOIN properties ON bookings.property_id = properties.property_id
         WHERE bookings.u_id = '" . $_SESSION['user_id'] . "'"
                                                );

                                                if (!mysqli_num_rows($query_res) > 0) {
                                                    echo '<td colspan="6"><center>No bookings found.</center></td>';
                                                } else {
                                                    while ($row = mysqli_fetch_array($query_res)) {
                                                ?>
                                                        <tr>
                                                            <td><?php echo $row['title']; ?></td>
                                                            <td>₹<?php echo $row['amount_paid']; ?></td>
                                                            <td>
                                                                <?php
                                                                $status = $row['booking_status'];
                                                                if ($status == "" || $status == "processing") {
                                                                    echo '<button class="btn btn-info">Processing</button>';
                                                                } elseif ($status == "confirmed") {
                                                                    echo '<button class="btn btn-success">Sold</button>';
                                                                } elseif ($status == "cancelled") {
                                                                    echo '<button class="btn btn-danger">Cancelled</button>';
                                                                }
                                                                ?>
                                                            </td>
                                                            <td><?php echo $row['created_at']; ?></td>
                                                            <td>
                                                                <?php
                                                                // Check if the booking status is "cancelled"
                                                                if ($status == "cancelled") {
                                                                    // Display "Delete" button if the status is cancelled
                                                                    echo '<a href="delete_booking.php?booking_id=' . $row['booking_id'] . '" class="btn btn-danger btn-xs">
                            <i class="fa fa-trash-o"></i> Delete
                        </a>';
                                                                } else {
                                                                    // Otherwise, show the cancel button
                                                                    echo '<a href="delete_orders.php?booking_id=' . $row['booking_id'] . '" onclick="return confirm(\'Are you sure you want to cancel your order?\');" class="btn btn-info btn-xs">
                            <i class="fa fa-trash-o"></i> Cancel
                        </a>';
                                                                }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <a href="generate_bill.php?booking_id=<?php echo $row['booking_id']; ?>" class="btn btn-primary btn-xs">
                                                                    <i class="fa fa-file-text-o"></i> Generate Bill
                                                                </a>
                                                            </td>
                                                        </tr>
                                                <?php }
                                                } ?>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>
            </section>
        </div>



        <?php include "chat.php" ?>
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
<?php
}
?>