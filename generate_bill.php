<?php
include("connection/connect.php");
session_start();

if (empty($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

if (isset($_GET['booking_id'])) {
    $booking_id = mysqli_real_escape_string($db, $_GET['booking_id']);

    // Fetch booking details
    $booking_query = mysqli_query($db, "SELECT * FROM bookings WHERE booking_id = '$booking_id' AND u_id = '" . $_SESSION['user_id'] . "'");
    $booking = mysqli_fetch_array($booking_query);

    if (!$booking) {
        echo "<h3>Booking not found!</h3>";
        exit();
    }

    // Fetch user details
    $user_query = mysqli_query($db, "SELECT * FROM users WHERE u_id = '" . $_SESSION['user_id'] . "'");
    $user = mysqli_fetch_array($user_query);

    // Fetch property details
    $property_query = mysqli_query($db, "SELECT * FROM properties WHERE property_id = '" . $booking['property_id'] . "'");
    $property = mysqli_fetch_array($property_query);
} else {
    echo "<h3>Booking ID is missing!</h3>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Booking Invoice</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
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

        .invoice-container {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-header h2 {
            font-size: 26px;
            font-weight: bold;
        }

        .invoice-details {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-table th,
        .invoice-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .invoice-table th {
            background: #000;
            color: white;
        }

        .total-price {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        .btn-print {
            background-color: #62A5FE;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 20px auto;
            text-align: center;
        }

        .btn-print:hover {
            background-color: #0056b3;
        }


        @media print {
            .btn-print {
                display: none;
            }
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
        <div class="invoice-container">
            <div class="invoice-header">
                <h2>Booking Invoice</h2>
                <p><strong>Booking ID:</strong> <?php echo $booking['booking_id']; ?></p>
                <p><strong>Date:</strong> <?php echo $booking['created_at']; ?></p>
            </div>

            <div class="invoice-details">
                <p><strong>User Name:</strong> <?php echo $user['username']; ?></p>
                <p><strong>Address:</strong> <?php echo $user['address']; ?></p>
                <p><strong>Contact:</strong> <?php echo $user['phone']; ?></p>
            </div>

            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Property Name</th>
                        <th>Location</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $property['title']; ?></td>
                        <td><?php echo $property['location']; ?></td>
                        <td>₹<?php echo number_format($property['price'], 2); ?></td>
                    </tr>
                </tbody>
            </table>

            <?php
            // Calculate tax based on the property price
            $property_price = $property['price'];
            $tax_percentage = 5; // Default tax
            if ($property_price > 5000) {
                $tax_percentage = 10;
            } elseif ($property_price > 2000) {
                $tax_percentage = 8;
            }

            $tax_amount = ($property_price * $tax_percentage) / 100;
            $final_price = $property_price + $tax_amount;
            ?>

            <div class="total-price">
                <p><strong>Total Price:</strong> ₹<?php echo number_format($property_price, 2); ?></p>
                <p><strong>Tax (<?php echo $tax_percentage; ?>%):</strong> ₹<?php echo number_format($tax_amount, 2); ?></p>
                <p><strong>Final Total:</strong> ₹<?php echo number_format($final_price, 2); ?></p>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; max-width: 300px;">
                <button class="btn-print" onclick="window.print()">Print Invoice</button>
                <a href="your_orders.php" style="color: #5c4ac7; font-size: 14px; text-decoration: none;">Back</a>
            </div>


        </div>


        <?php include "include/footer.php" ?>
    </div>

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