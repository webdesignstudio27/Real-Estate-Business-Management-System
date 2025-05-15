<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Check if property_id is set in the URL
if (!isset($_GET['property_id']) || empty($_GET['property_id'])) {
    echo "<script>alert('Invalid request!'); window.location='index.php';</script>";
    exit;
}

$property_id = intval($_GET['property_id']); // Ensure it's an integer

// Fetch property details from the database using prepared statement
$query = $db->prepare("SELECT * FROM properties WHERE property_id = ?");
$query->bind_param("i", $property_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "<script>alert('Property not found!'); window.location='index.php';</script>";
    exit;
}
$query->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $propertyId = intval($_POST['property_id']);
    $status = trim($_POST['status']);

    // Validate status
    $valid_statuses = ['available', 'sold', 'pending']; // Adjust as needed
    if (!in_array($status, $valid_statuses)) {
        $_SESSION['error_msg'] = "Invalid status value.";
        header("Location: view_property.php?property_id=$propertyId");
        exit();
    }

    // Update property status using prepared statement
    $stmt = $db->prepare("UPDATE properties SET status = ? WHERE property_id = ?");
    $stmt->bind_param("si", $status, $propertyId);

    if ($stmt->execute()) {
        $_SESSION['success_msg'] = "Property status updated successfully!";
    } else {
        $_SESSION['error_msg'] = "Failed to update property status.";
    }
    $stmt->close();

    // Redirect back to the same page to reflect changes
    header("Location: view_property.php?property_id=$propertyId");
    exit();
}
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
            border: 2px rgb(124, 122, 119);
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
                    <div style="margin-top: 10px;">
                        <a href="javascript:history.back()" style="color: #5c4ac7; font-size: 14px;">Back</a>
                    </div>
                    <div class="col-12">
                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white">View Properties</h4>
                                </div>
                                <div class="table-responsive m-t-40" style="overflow-x: auto; max-height: 500px;">
                                    <section class="popular">
                                        <div class="container">
                                            <div class="title text-xs-center m-b-30">
                                                <h2></h2>
                                                <p class="lead"></p>
                                            </div>
                                            <div class="row">
                                                <!-- Property Image -->
                                                <div class="col-md-6">
                                                    <?php
                                                    $propertyId = $row['id']; // or whatever unique ID the property has
                                                    $rawImages = str_replace("'", "", $row['images']);
                                                    $images = explode(",", $rawImages);
                                                    ?>

                                                    <div class="swiper mainSwiper-<?php echo $propertyId; ?>">
                                                        <div class="swiper-wrapper">
                                                            <?php foreach ($images as $image): ?>
                                                                <div class="swiper-slide">
                                                                    <img src="property_img/property/<?php echo $image; ?>" class="img-fluid" alt="Property Image" height="500" width="500">
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>

                                                        <!-- Navigation buttons OUTSIDE the wrapper -->
                                                        <div class="swiper-button-next swiper-button-next-<?php echo $propertyId; ?>"></div>
                                                        <div class="swiper-button-prev swiper-button-prev-<?php echo $propertyId; ?>"></div>
                                                    </div>


                                                    <!-- Thumbnail Swiper -->
                                                    <div class="swiper thumbSwiper mt-3">
                                                        <div class="swiper-wrapper">
                                                            <?php foreach ($images as $image): ?>
                                                                <div class="swiper-slide">
                                                                    <img src="property_img/property/<?php echo $image; ?>" class="img-fluid" alt="Thumbnail Image">
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Property Information -->
                                                <div class="col-md-6">

                                                    <h2><?php echo $row['title']; ?></h2>
                                                    <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
                                                    <p><strong>Price:</strong> $<?php echo number_format($row['price']); ?></p>
                                                    <p><strong>Type:</strong> <?php echo $row['property_type']; ?></p>
                                                    <p><strong>Area:</strong> <?php echo $row['area']; ?> sq.ft</p>
                                                    <p><strong>Bedrooms:</strong> <?php echo $row['bedrooms']; ?> |<strong>Bathrooms:</strong> <?php echo $row['bathrooms']; ?> </p>
                                                    <p><?php echo $row['description']; ?></p>
                                                    <p>
                                                        <a href="property_img/property/<?php echo $row['pdf']; ?>" target="_blank">
                                                            <img src="images/pdf.png" class="img-fluid" alt="View PDF">
                                                        </a>
                                                    </p>

                                                    <?php if ($row['status'] !== 'sold' && $row['status'] !== 'available') { ?>
                                                        <form method="post" action="">
                                                            <input type="hidden" name="property_id" value="<?php echo $row['property_id']; ?>">
                                                            <select name="status" class="form-control" required>
                                                                <option value="waitinglist" <?php echo ($row['status'] == 'waitinglist') ? 'selected' : ''; ?>>Waiting List</option>
                                                                <option value="available" <?php echo ($row['status'] == 'available') ? 'selected' : ''; ?>>Approve</option>
                                                            </select>
                                                            <button type="submit" name="update_status" class="btn btn-primary btn-sm mt-2">Accept</button>
                                                        </form>
                                                    <?php } else { ?>
                                                        <button class="btn btn-danger" disabled> Approved </button>
                                                        <button class="btn btn-info" onclick="window.location.href='add_property.php'">Back</button>
                                                    <?php } ?>




                                                </div>
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