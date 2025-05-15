<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
session_start();
error_reporting(E_ALL);

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

// Ensure property_id is passed
if (!isset($_GET['property_id'])) {
    die("Invalid request! Property ID is missing.");
}

$property_id = mysqli_real_escape_string($db, $_GET['property_id']);

// Get property price
$query = "SELECT price, post_type FROM properties WHERE property_id = '$property_id'";
$result = mysqli_query($db, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    die("Property ID not found or query failed.");
}

$row = mysqli_fetch_assoc($result);
$amount_paid = $row['price'];
$post_type = $row['post_type'];

if ($post_type == 'sale') {
    if ($amount_paid < 50000) {
        $paying_amount = 10000;
    } else if ($amount_paid >= 50000 && $amount_paid < 100000) {
        $paying_amount = 20000;
    } else if ($amount_paid >= 100000 && $amount_paid < 200000) {
        $paying_amount = 30000;
    } else if ($amount_paid >= 200000 && $amount_paid < 300000) {
        $paying_amount = 40000;
    } else if ($amount_paid >= 300000 && $amount_paid < 400000) {
        $paying_amount = 50000;
    } else {
        $paying_amount = 70000;
    }
} else if ($post_type == 'rent') {
    // For rent, apply less amount (e.g., half)
    if ($amount_paid < 10000) {
        $paying_amount = 5000;
    } else if ($amount_paid >= 10000 && $amount_paid < 20000) {
        $paying_amount = 10000;
    } else if ($amount_paid >= 20000 && $amount_paid < 30000) {
        $paying_amount = 15000;
    } else if ($amount_paid >= 40000 && $amount_paid < 50000) {
        $paying_amount = 20000;
    } else if ($amount_paid >= 60000 && $amount_paid < 70000) {
        $paying_amount = 25000;
    } else {
        $paying_amount = 35000;
    }
}

$tax = $paying_amount * 0.10; // 10% tax
$total = $paying_amount + $tax;

$_SESSION['item_total'] = $total; // Store in session for later

// Handle Form Submission
if (isset($_POST['submit'])) {
    $u_id = $_SESSION['user_id'];
    $payment_method = $_POST['mod'];
    $card_no = $_POST['card_no'] ?? null;
    $total = $_SESSION['item_total'];

    // Map payment method to ENUM values
    switch ($payment_method) {
        case 'paypal':
            $payment_method = "credit_card";
            break;
        case 'qrcode':
            $payment_method = "bank_transfer";
            break;
        case 'COD':
        default:
            $payment_method = "cash";
    }

    $payment_status = "completed";

    // Update property status
    $updateQuery = "UPDATE properties SET status='waitinglist' WHERE property_id = '$property_id'";
    $updateResult = mysqli_query($db, $updateQuery);

    // Insert transaction
    $insertQuery = "INSERT INTO transactions (u_id, property_id, amount, payment_status, payment_method, card_no)
    VALUES ('$u_id', '$property_id', '$total', '$payment_status', '$payment_method', '$card_no')";
    $insertResult = mysqli_query($db, $insertQuery);

    if ($updateResult && $insertResult) {
        echo "<script>alert('Property successfully posted! Status updated to waitinglist.');</script>";
        echo "<script>window.location.href='home.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error inserting transaction: " . mysqli_error($db) . "');</script>";
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
    <title>Home || Real Estate </title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.7/js/tether.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
</head>

<body>
    <div class="site-wrapper">
        <!-- Navbar here -->

        <div class="page-wrapper">
            <div class="top-links">
                <div class="container">
                    <ul class="row links">
                        <li class="col-xs-12 col-sm-4 link-item"><span>1</span><a href="createlist.php">Upload Your Property</a></li>

                        <li class="col-xs-12 col-sm-4 link-item"><span>2</span><a href="checkout.php">Post and Pay</a></li>

                    </ul>
                </div>
            </div>

            <div class="container">
                <span style="color:green;">
                    <?php if (isset($success)) echo $success; ?>
                </span>
            </div>

            <div class="container m-t-30">
                <form action="" method="post" onsubmit="return validateCreditCard()">
                    <div class="widget clearfix">
                        <div class="widget-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="cart-totals margin-b-20">
                                        <div class="cart-totals-title">
                                            <h4>Cart Summary</h4>
                                        </div>
                                        <?php

                                        ?>
                                        <div class="cart-totals-fields">
                                            <table class="table">
                                                <tr>
                                                    <td>Price of Property</td>
                                                    <td>₹<?php echo number_format($amount_paid, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Paying Amount</td>
                                                    <td>₹<?php echo number_format($paying_amount, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Tax (10%)</td>
                                                    <td>₹<?php echo number_format($tax, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Total</strong></td>
                                                    <td><strong>₹<?php echo number_format($total, 2); ?></strong></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="payment-option" id="payment-option">
                                        <ul class="list-unstyled">
                                            <li>
                                                <label class="custom-control custom-radio  m-b-20">
                                                    <input name="mod" id="radioStacked1" checked value="COD" type="radio" class="custom-control-input" onclick="togglePaymentFields()"> <span class="custom-control-indicator"></span> <span class="custom-control-description">Cash on Delivery</span>
                                                </label>
                                            </li>
                                            <li>
                                                <label class="custom-control custom-radio  m-b-10">
                                                    <input name="mod" type="radio" value="paypal" class="custom-control-input" onclick="togglePaymentFields()"> <span class="custom-control-indicator"></span> <span class="custom-control-description">Paypal <img src="images/paypal.jpg" alt="" width="90"></span>
                                                </label>
                                            </li>
                                            <li>
                                                <label class="custom-control custom-radio  m-b-10">
                                                    <input name="mod" type="radio" value="qrcode" class="custom-control-input" onclick="togglePaymentFields()"> <span class="custom-control-indicator"></span> <span class="custom-control-description">QR Code <img src="images/qr.png" alt="" height="25" width="25"></span>
                                                </label>
                                            </li>
                                        </ul>

                                        <!-- Credit Card Details (hidden by default) -->
                                        <div id="creditCardDetails" style="display: none;">
                                            <div class="form-group">
                                                <label for="creditCardNumber">Credit Card Number</label>
                                                <input type="text" name="card_no" id="creditCardNumber" class="form-control" placeholder="XXXX XXXX XXXX XXXX">
                                            </div>
                                            <div class="form-group">
                                                <label for="expiryDate">Expiry Date (MM/YY)</label>
                                                <input type="text" id="expiryDate" class="form-control" placeholder="MM/YY">
                                            </div>
                                            <div class="form-group">
                                                <label for="cvv">CVV</label>
                                                <input type="text" id="cvv" class="form-control" placeholder="Enter CVV">
                                            </div>
                                        </div>

                                        <!-- QR Code Section (hidden by default) -->
                                        <div id="qrCodeDetails" style="display: none; text-align: center; margin-top: 30px;">
                                            <h5>Scan the QR Code to make payment</h5>
                                            <canvas id="qrcode"></canvas> <!-- This canvas is where the QR code will be drawn -->
                                            <p>Total: <?php echo "₹" . number_format($total, 2); ?></p>
                                        </div>

                                        <p class="text-xs-center"> <input type="submit" value="Submit" name="submit" class="btn theme-btn m-t-15"></p>
                                        <div style="margin-top: 10px;">
                                            <a href="javascript:history.back()" style="color: #5c4ac7; font-size: 14px;">Back</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        function togglePaymentFields() {
            const paymentMethod = document.querySelector('input[name="mod"]:checked').value;
            const creditCardSection = document.getElementById('creditCardDetails');
            const qrSection = document.getElementById('qrCodeDetails');

            creditCardSection.style.display = 'none';
            qrSection.style.display = 'none';

            if (paymentMethod === 'paypal') {
                creditCardSection.style.display = 'block';
            } else if (paymentMethod === 'qrcode') {
                qrSection.style.display = 'block';
                const total = <?php echo $total; ?>;
                QRCode.toCanvas(document.getElementById('qrcode'), `Pay ₹${total} to Real Estate`, function(error) {
                    if (error) console.error(error);
                });
            }
        }

        function validateCreditCard() {
            const method = document.querySelector('input[name="mod"]:checked').value;
            if (method === 'paypal') {
                const cardNumber = document.getElementById("creditCardNumber").value.replace(/\s+/g, '');
                const expiry = document.getElementById("expiryDate").value;
                const cvv = document.getElementById("cvv").value;

                const cardPattern = /^[0-9]{16}$/;
                const expiryPattern = /^(0[1-9]|1[0-2])\/([0-9]{2})$/;
                const cvvPattern = /^[0-9]{3}$/;

                if (!cardNumber.match(cardPattern)) {
                    alert("Enter a valid 16-digit credit card number.");
                    return false;
                }

                if (!expiry.match(expiryPattern)) {
                    alert("Enter expiry in MM/YY format.");
                    return false;
                }

                if (!cvv.match(cvvPattern)) {
                    alert("Enter a valid 3-digit CVV.");
                    return false;
                }
            }
            return true;
        }

        // Trigger toggle on page load (if needed)
        document.addEventListener("DOMContentLoaded", togglePaymentFields);
    </script>
</body>

</html>