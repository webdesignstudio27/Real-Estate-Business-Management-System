<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Ensure booking_id and u_id are set
if (!isset($_GET['booking_id']) || empty($_GET['booking_id'])) {
    echo "<script>alert('Invalid request!'); window.location='index.php';</script>";
    exit;
}

$booking_id = intval($_GET['booking_id']); // Ensure it's an integer

// Fetch booking details, property details, buyer and seller details
$query = $db->prepare("
    SELECT 
        u.username AS buyer_username, u.f_name AS buyer_f_name, u.l_name AS buyer_l_name, u.email AS buyer_email, u.phone AS buyer_phone,
        p.title AS property_title, p.description AS property_description, p.price AS property_price, p.status AS property_status,
        b.amount_paid, b.status AS booking_status, b.scheduled_date, b.checkin_date, b.checkout_date,
        s.username AS seller_username, s.f_name AS seller_f_name, s.l_name AS seller_l_name, s.email AS seller_email, s.phone AS seller_phone
    FROM bookings b
    INNER JOIN users u ON b.u_id = u.u_id
    INNER JOIN properties p ON b.property_id = p.property_id
    INNER JOIN users s ON p.u_id = s.u_id  -- Assuming the property seller is also a user
    WHERE b.booking_id = ?
");
$query->bind_param("i", $booking_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $booking_details = $result->fetch_assoc();
} else {
    echo "<script>alert('No booking found!'); window.location='index.php';</script>";
    exit;
}
$query->close();
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>All Menu</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

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
            border: 2px solidrgb(120, 120, 121);
        }
    </style>
</head>

<body class="fix-header fix-sidebar">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>

    <div id="main-wrapper">
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <div class="navbar-header">
                    <a class="navbar-brand" href="dashboard.php">
                        <span><img src="images\Estate_logo.png" height="70px" width="70px" alt="homepage" class="dark-logo" /></span>
                    </a>
                </div>
                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto mt-md-0">
                    </ul>
                    <ul class="navbar-nav my-lg-0">
                        <li class="nav-item dropdown">
                            <div class="dropdown-menu dropdown-menu-right mailbox animated zoomIn">
                                <ul>
                                    <li>
                                        <div class="drop-title">Notifications</div>
                                    </li>

                                    <li>
                                        <a class="nav-link text-center" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="images/bookingSystem/suvedha.png" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <ul class="dropdown-user">
                                    <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="left-sidebar">

            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li class="nav-label">Home</li>
                        <li> <a href="dashboard.php"><i class="fa fa-tachometer"></i><span>Dashboard</span></a>
                        </li>
                        <li class="nav-label">Log</li>
                        <li> <a href="all_users.php"> <span><i class="fa fa-user f-s-20 "></i></span><span>Users</span></a></li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-archive f-s-20 color-warning"></i><span class="hide-menu">Properties</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_property.php">All Property</a></li>
                                <li><a href="add_property.php">Add Property</a></li>

                            </ul>
                        </li>
                        <li> <a href="transactions.php"><i class="fa fa-money" aria-hidden="true"></i><span>Transactions</span></a></li>


                        <li> <a href="all_orders.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span>Booked Property</span></a></li>
                        <li> <a href="suport.php"><i class="fa fa-comment" aria-hidden="true"></i><span>Messages</span></a></li>

                    </ul>
                </nav>

            </div>

        </div>
        <div class="page-wrapper">
            <div style="padding-top: 10px;">
                <marquee onMouseOver="this.stop()" onMouseOut="this.start()"> <a href="https://www.instagram.com/webdesign_studio_">Web Design Studio</a></marquee>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white">View Booking Details</h4>
                                </div>
                                <div class="table-responsive m-t-40" style="overflow-x: auto; max-height: 500px;">
                                    <section class="popular">
                                        <div class="container">
                                            <div class="row">
                                                <!-- Buyer Details -->
                                                <div class="col-md-6">
                                                    <h3>Buyer Information</h3>
                                                    <p><strong>Name:</strong> <?php echo $booking_details['buyer_f_name'] . ' ' . $booking_details['buyer_l_name']; ?></p>
                                                    <p><strong>Username:</strong> <?php echo $booking_details['buyer_username']; ?></p>
                                                    <p><strong>Email:</strong> <?php echo $booking_details['buyer_email']; ?></p>
                                                    <p><strong>Phone:</strong> <?php echo $booking_details['buyer_phone']; ?></p>
                                                </div>

                                                <!-- Seller Details -->
                                                <div class="col-md-6">
                                                    <h3>Seller Information</h3>
                                                    <p><strong>Name:</strong> <?php echo $booking_details['seller_f_name'] . ' ' . $booking_details['seller_l_name']; ?></p>
                                                    <p><strong>Username:</strong> <?php echo $booking_details['seller_username']; ?></p>
                                                    <p><strong>Email:</strong> <?php echo $booking_details['seller_email']; ?></p>
                                                    <p><strong>Phone:</strong> <?php echo $booking_details['seller_phone']; ?></p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- Property Details -->
                                                <div class="col-md-6">
                                                    <h3>Property Information</h3>
                                                    <p><strong>Title:</strong> <?php echo $booking_details['property_title']; ?></p>
                                                    <p><strong>Description:</strong> <?php echo $booking_details['property_description']; ?></p>
                                                    <p><strong>Price:</strong> ₹<?php echo number_format($booking_details['property_price']); ?></p>
                                                    <p><strong>Status:</strong> <?php echo ucfirst($booking_details['property_status']); ?></p>
                                                </div>

                                                <!-- Booking Details -->
                                                <div class="col-md-6">
                                                    <h3>Booking Information</h3>
                                                    <p><strong>Amount Paid:</strong> ₹<?php echo number_format($booking_details['amount_paid'], 2); ?></p>
                                                    <p><strong>Booking Status:</strong> <?php echo ucfirst($booking_details['booking_status']); ?></p>
                                                    <p><strong>Scheduled Date:</strong> <?php echo date('Y-m-d H:i', strtotime($booking_details['scheduled_date'])); ?></p>
                                                    <p><strong>Check-in Date:</strong> <?php echo date('Y-m-d', strtotime($booking_details['checkin_date'])); ?></p>
                                                    <p><strong>Check-out Date:</strong> <?php echo date('Y-m-d', strtotime($booking_details['checkout_date'])); ?></p>
                                                </div>
                                            </div>

                                            <!-- Back Button -->
                                            <div class="text-center">
                                                <button class="btn btn-info" onclick="window.location.href='all_orders.php'">Back to Bookings</button>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize main and thumbnail swiper
            const mainSwiper = new Swiper(".mainSwiper-<?php echo $propertyId; ?>", {
                spaceBetween: 10,
                navigation: {
                    nextEl: ".swiper-button-next-<?php echo $propertyId; ?>",
                    prevEl: ".swiper-button-prev-<?php echo $propertyId; ?>",
                },
                thumbs: {
                    swiper: new Swiper(".thumbSwiper", {
                        spaceBetween: 10,
                        slidesPerView: 4,
                        freeMode: true,
                        watchSlidesProgress: true,
                    }),
                },
            });
        });
    </script>



    <?php include "include/footer.php" ?>

    </div>
    </div>

    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/lib/datatables/datatables.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="js/lib/datatables/datatables-init.js"></script>
</body>

</html>