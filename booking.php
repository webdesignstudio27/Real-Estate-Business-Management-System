<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();
if (empty($_SESSION['user_id'])) {
    header('location:login.php');
} else {

    function function_alert($db, $property_id)
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Enable exceptions for MySQLi
        $user_id = $_SESSION['user_id']; // Assuming the user is logged in

        mysqli_begin_transaction($db);

        try {
            // Update properties table
            $updatePropertyQuery = "UPDATE properties SET status = 'waitinglist' WHERE property_id = ?";
            $stmt1 = mysqli_prepare($db, $updatePropertyQuery);
            if (!$stmt1) {
                throw new Exception("Prepare failed: " . mysqli_error($db));
            }
            mysqli_stmt_bind_param($stmt1, "i", $property_id);
            if (!mysqli_stmt_execute($stmt1)) {
                throw new Exception("Execute failed: " . mysqli_stmt_error($stmt1));
            }
            mysqli_stmt_close($stmt1);

            // Update bookings table
            $updateBookingQuery = "UPDATE bookings SET status = 'processing', payment_status = 'paid' WHERE property_id = ? AND u_id = ?";
            $stmt2 = mysqli_prepare($db, $updateBookingQuery);
            if (!$stmt2) {
                throw new Exception("Prepare failed: " . mysqli_error($db));
            }
            mysqli_stmt_bind_param($stmt2, "ii", $property_id, $user_id);
            if (!mysqli_stmt_execute($stmt2)) {
                throw new Exception("Execute failed: " . mysqli_stmt_error($stmt2));
            }
            mysqli_stmt_close($stmt2);

            // Commit transaction
            mysqli_commit($db);

            // Success Message
            echo "<script>alert('Thank you. Your Booking has been placed!');</script>";
            echo "<script>window.location.replace('your_orders.php');</script>";
        } catch (Exception $e) {
            mysqli_rollback($db);

            // Restore property status
            $restorePropertyQuery = "UPDATE properties SET status = 'available' WHERE property_id = ?";
            $stmt3 = mysqli_prepare($db, $restorePropertyQuery);
            mysqli_stmt_bind_param($stmt3, "i", $property_id);
            mysqli_stmt_execute($stmt3);
            mysqli_stmt_close($stmt3);

            // Ensure payment_status is set to 'pending' on failure
            $restoreBookingQuery = "UPDATE bookings SET status = 'cancelled', payment_status = 'pending' WHERE property_id = ? AND u_id = ?";
            $stmt4 = mysqli_prepare($db, $restoreBookingQuery);
            mysqli_stmt_bind_param($stmt4, "ii", $property_id, $user_id);
            mysqli_stmt_execute($stmt4);
            mysqli_stmt_close($stmt4);

            // Error message
            echo "<script>alert('Error: Unable to process booking! Your booking has failed. Payment status is set to Pending.');</script>";
            echo "<script>window.location.replace('your_orders.php');</script>";
        }
    }





    // Check if property_id is set in the URL
    if (isset($_GET['property_id'])) {
        $property_id = $_GET['property_id'];

        // Fetch property details from the database
        $query = "SELECT * FROM properties WHERE property_id = '$property_id'";

        $result = mysqli_query($db, $query);

        // Check if the property exists
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        } else {
            echo "<script>alert('Property not found!'); window.location='index.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Invalid request!'); window.location='index.php';</script>";
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!isset($_SESSION["user_id"])) {
            echo "<script>alert('Please login to book a property.'); window.location='login.php';</script>";
            exit;
        }

        $u_id = $_SESSION["user_id"];
        $property_id = intval($_GET["property_id"]);
        $scheduled_date = mysqli_real_escape_string($db, $_POST["scheduled_date"]);
        $base_amount = floatval($_POST["amount_paid"]);
        $amount_paid = $base_amount + ($base_amount * 0.10); // Add 10% tax

        $payment_mode = mysqli_real_escape_string($db, $_POST["mod"]);
        $checkin_date = mysqli_real_escape_string($db, $_POST["checkin_date"]);
        $checkout_date = mysqli_real_escape_string($db, $_POST["checkout_date"]);

        $status = "pending";


        $query1 = "INSERT INTO bookings (u_id, property_id, status, scheduled_date, amount_paid, payment_mode, checkin_date, checkout_date) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($db, $query1);
        mysqli_stmt_bind_param($stmt, "iissdsss", $u_id, $property_id, $status, $scheduled_date, $amount_paid, $payment_mode, $checkin_date, $checkout_date);

        if (mysqli_stmt_execute($stmt)) {
            function_alert($db, $property_id); // Call function to update status and show alert
        } else {
            echo "<script>alert('Error booking property. Please try again!');</script>";
        }


        mysqli_stmt_close($stmt);
    }
}
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.4.4/build/qrcode.min.js"></script>
    <title>Home || Real Estate </title>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.4.4/build/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.3.0/build/qrcode.min.js"></script>


    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.7/js/tether.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.7/js/tether.min.js"></script>




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

        .booking-form {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 50px auto;
        }

        .booking-form h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            color: #555;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .booking-btn {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .booking-btn:hover {
            background: #0056b3;
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


</head>

<body class="home">
    <header id="header" class="header-scroll top-header headrom">
        <nav class="navbar navbar-dark">
            <div class="container">
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/Estate_logo.png" alt="" width="18%"> </a>
                <div class="collapse navbar-toggleable-md float-lg-right" id="mainNavbarCollapse">
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

    <section class="popular">
        <div class="container">
            <div class="title text-xs-center m-b-30">
                <div class="page-wrapper">
                    <div class="top-links">
                        <div class="container">
                            <ul class="row links">
                                <li class="col-xs-12 col-sm-4 link-item"><span>1</span><a href="properties.php">Choose Property</a></li>
                                <li class="col-xs-12 col-sm-4 link-item"><span>2</span><a href="#">Pick Your favorite Property</a></li>
                                <li class="col-xs-12 col-sm-4 link-item active"><span>3</span><a href="booking.php">Booking and Pay</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="container">
                        <span style="color:green;">
                            <?php if (isset($success)) echo $success; ?>
                        </span>
                    </div>
                    <div class="row">
                        <div class="booking-form">
                            <h2>Book a Property</h2>
                            <form action="" method="post" onsubmit="return validateCreditCard()">
                                <div class="form-group">
                                    <label for="property_id">Property Name:</label>
                                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($row['title']); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="scheduled_date">Scheduled Date:</label>
                                    <input type="datetime-local" name="scheduled_date" required>
                                </div>

                                <div class="form-group">
                                    <label for="checkin_date">Check-in Date:</label>
                                    <input type="date" name="checkin_date" required>
                                </div>

                                <div class="form-group">
                                    <label for="checkout_date">Check-out Date:</label>
                                    <input type="date" name="checkout_date" required>
                                </div>

                                <div class="form-group">
                                    <label for="price">Amount</label>
                                    <input type="text" name="amount_paid" class="form-control" value="<?php echo ($row['price']); ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="price">Total Amount with Tax (10%)</label>
                                    <input type="text" name="total_amount_tax" class="form-control"
                                        value="<?php echo number_format($row['price'] * 1.10, 2); ?>" readonly>
                                </div>



                                <div class="payment-option">
                                    <ul class="list-unstyled">
                                        <li>
                                            <label class="custom-control custom-radio m-b-20">
                                                <input name="mod" id="radioCOD" value="COD" type="radio" class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Cash on Delivery</span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="custom-control custom-radio m-b-10">
                                                <input name="mod" id="radioCreditCard" value="creditcard" type="radio" class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Credit Card</span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="custom-control custom-radio m-b-10">
                                                <input name="mod" id="radioQRCode" value="qrcode" type="radio" class="custom-control-input" disabled>
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Paypal <img src="images/paypal.jpg" alt="" width="90"><img src="images/qr.png" alt="" height="25" width="25"></span>
                                            </label>
                                        </li>
                                    </ul>

                                    <!-- COD Section -->
                                    <div id="COD" style="display: none;">
                                        <p>Cash on Delivery selected. No further details required.</p>
                                    </div>

                                    <!-- Credit Card Details -->
                                    <div id="creditCardDetails" style="display: none;">
                                        <div class="form-group">
                                            <label for="creditCardNumber">Credit Card Number</label>
                                            <input type="text" id="creditCardNumber" class="form-control" placeholder="Enter your credit card number(XXXX XXXX XXXX XXXX)">
                                        </div>
                                        <div class="form-group">
                                            <label for="expiryDate">Expiry Date (MM/YY)</label>
                                            <input type="text" id="expiryDate" class="form-control" placeholder="MM/YY">
                                        </div>
                                        <div class="form-group">
                                            <label for="cvv">CVV</label>
                                            <input type="text" id="cvv" class="form-control" placeholder="Enter CVV(XXX)">
                                        </div>
                                    </div>

                                    <!-- QR Code Section -->
                                    <div id="qrCodeDetails" style="display: none; text-align: center; margin-top: 30px;">
                                        <h5>Scan the QR Code to make payment</h5>
                                        <canvas id="qrcode"></canvas> <!-- This canvas is where the QR code will be drawn -->
                                        <p>Total: ₹<?php echo number_format($row['price'] * 1.10, 2); ?></p>
                                    </div>

                                    <p class="text-xs-center">
                                        <input type="submit" value="Booking" name="submit" class="btn theme-btn m-t-15" onclick="return validateCreditCard();">
                                    </p>
                                    <div style="margin-top: 10px;">
                                        <a href="javascript:history.back()" style="color: #5c4ac7; font-size: 14px;">Back</a>
                                    </div>
                                </div>





                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.4.4/build/qrcode.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('input[name="mod"]').forEach(function(radio) {
                radio.addEventListener("change", togglePaymentFields);
            });
        });

        function togglePaymentFields() {
            const paymentMethod = document.querySelector('input[name="mod"]:checked').value;

            // Hide all payment fields initially
            document.getElementById('COD').style.display = 'none';
            document.getElementById('creditCardDetails').style.display = 'none';
            document.getElementById('qrCodeDetails').style.display = 'none';

            // Show selected payment method fields
            if (paymentMethod === 'qrcode') {
                document.getElementById('qrCodeDetails').style.display = 'block';

                // Pass the PHP value for amount_paid correctly to JavaScript
                const amountPaid = <?php echo json_encode($row['price'] * 1.10); ?>; // Pass PHP value as JS variable

                // Create the payment info string
                const paymentInfo = `Payment of ₹${amountPaid} to properties`;

                // Check if qrContainer exists in the DOM
                const qrContainer = document.getElementById("qrcode");
                if (qrContainer) {
                    // Clear any previous QR code
                    qrContainer.innerHTML = "";

                    // Ensure paymentInfo is a string
                    if (typeof paymentInfo === 'string' && paymentInfo.length > 0) {
                        // Generate the QR code with custom colors and render it on the canvas
                        const qrCode = new QRCode(qrContainer, {
                            text: paymentInfo,
                            width: 256, // Set the width to a larger size for visibility
                            height: 256, // Set the height to a larger size
                            correctLevel: QRCode.CorrectLevel.L, // Low error correction
                            colorDark: "#0000FF", // Custom dark color (QR code color)
                            colorLight: "#FFFFFF", // Custom light color (background color)
                            render: "canvas" // Ensures the QR code is rendered on a canvas
                        });
                    } else {
                        console.error("Invalid paymentInfo:", paymentInfo);
                    }
                } else {
                    console.error("QR code container not found!");
                }
            } else if (paymentMethod === 'creditcard') {
                document.getElementById('creditCardDetails').style.display = 'block';
            } else if (paymentMethod === 'COD') {
                document.getElementById('COD').style.display = 'block';
            }
        }
    </script>

    <?php include "include/footer.php" ?>


    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/jquerys.js"></script>


</body>

</html>