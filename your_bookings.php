<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();

// Redirect if user is not logged in
if (empty($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $propertyId = $_POST['property_id'];
    $status = $_POST['status'];

    // Use prepared statements to prevent SQL injection
    $stmt = $db->prepare("UPDATE properties SET status = ? WHERE property_id = ?");
    $stmt->bind_param("si", $status, $propertyId);

    if ($stmt->execute()) {
        // If property is marked as 'sold', update bookings status to 'confirmed'
        if ($status === 'sold') {
            $stmt2 = $db->prepare("UPDATE bookings SET status = 'confirmed' WHERE property_id = ?");
            $stmt2->bind_param("i", $propertyId);
            $stmt2->execute();
            $stmt2->close();
        }

        $_SESSION['success_msg'] = "Property status updated successfully!";
    } else {
        $_SESSION['error_msg'] = "Failed to update property status.";
    }
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page to see changes
    exit();
}


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

        <section class="popular">
            <div class="container">
                <div class="row">
                    <h2>Manage Your Bookings</h2>

                    <?php
                    // Display success or error message
                    if (isset($_SESSION['success_msg'])) {
                        echo '<div class="alert alert-success">' . $_SESSION['success_msg'] . '</div>';
                        unset($_SESSION['success_msg']);
                    }
                    if (isset($_SESSION['error_msg'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['error_msg'] . '</div>';
                        unset($_SESSION['error_msg']);
                    }
                    ?>
                    <div class="col-xs-12">



                        <div class="bg-gray">
                            <div style="margin-top: 10px;">
                                <a href="javascript:history.back()" style="color: #5c4ac7; font-size: 14px;">Back</a>
                            </div>
                            <div class="row">

                                <table class="table table-bordered table-hover">
                                    <thead style="background: #404040; color:white;">

                                        <tr>
                                            <th>Property</th>
                                            <th>Buyer Name</th>
                                            <th>Address</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Price</th>
                                            <th>Payment Status</th>
                                            <th>Request</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query_res = $db->query("
                SELECT users.username, users.email, users.phone, users.address, 
                       properties.property_id, properties.title, properties.status AS property_status, 
                       bookings.booking_id,bookings.payment_status,bookings.status AS booking_status, 
                       bookings.created_at, bookings.amount_paid
                FROM properties
                LEFT JOIN bookings ON properties.property_id = bookings.property_id
                INNER JOIN users ON bookings.u_id = users.u_id
                WHERE properties.u_id = '" . $_SESSION['user_id'] . "'
                AND properties.status IN ('waitinglist', 'sold')
            ");

                                        if ($query_res->num_rows > 0) {
                                            while ($row = $query_res->fetch_assoc()) {
                                        ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['amount_paid']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                                                    <td>
                                                        <?php if ($row['property_status'] !== 'sold') { ?>
                                                            <form method="post" action="">
                                                                <input type="hidden" name="property_id" value="<?php echo $row['property_id']; ?>">
                                                                <select name="status" class="form-control" required>
                                                                    <option value="waitinglist" <?php echo ($row['property_status'] == 'waitinglist') ? 'selected' : ''; ?>>Waiting List</option>
                                                                    <option value="sold" <?php echo ($row['property_status'] == 'sold') ? 'selected' : ''; ?>>Sold</option>
                                                                </select>
                                                                <button type="submit" name="update_status" class="btn btn-primary btn-sm mt-2">Accept</button>
                                                            </form>
                                                        <?php } else { ?>
                                                            <button class="btn btn-danger" disabled>Sold</button>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo '<tr><td colspan="8"><center>No bookings found.</center></td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>



                    </div>



                </div>
            </div>


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
</body>

</html>