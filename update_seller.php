<!DOCTYPE html>
<html lang="en">
<?php
session_start();
error_reporting(0);
include("connection/connect.php");

if (empty($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch user data to pre-fill the form
$sql = "SELECT * FROM users WHERE u_id = '$user_id'";
$result = mysqli_query($db, $sql);
$userData = mysqli_fetch_assoc($result);

// Handle form submission

if (isset($_POST['submit'])) {
    // Check if any field is empty
    if (
        empty($_POST['type']) ||
        empty($_POST['firstname']) ||
        empty($_POST['lastname']) ||
        empty($_POST['email']) ||
        empty($_POST['phone']) ||
        empty($_POST['password']) ||
        empty($_POST['cpassword']) ||
        empty($_POST['address'])
    ) {
        $message = "All fields must be required!";
    } else {
        // Validation for username (5 characters minimum, letters, numbers, _ or @)
        if (!preg_match('/^[a-zA-Z0-9_@]{5,}$/', $_POST['username'])) {
            $message = "Username must be at least 5 characters long and can contain letters, numbers, _ or @.";
        }

        // Validate Firstname (letters only, 3 to 20 characters)
        elseif (!preg_match('/^[a-zA-Z]{3,20}$/', $_POST['firstname'])) {
            $message = "First name must be between 3 to 20 characters and contain only letters.";
        }

        // Validate Lastname (letters only, 1 to 20 characters)
        elseif (!preg_match('/^[a-zA-Z]{1,20}$/', $_POST['lastname'])) {
            $message = "Last name must be between 1 to 20 characters and contain only letters.";
        }

        // Validate Email (must be a valid Gmail address)
        elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || !preg_match('/@gmail\.com$/', $_POST['email'])) {
            $message = "Please enter a valid Gmail address.";
        }

        // Validate Phone (must be 10 digits, not repetitive)
        elseif (
            !preg_match('/^\d{10}$/', $_POST['phone']) ||                          // Not 10 digits
            preg_match('/(\d)\1{5,}/', $_POST['phone']) ||                         // Any digit repeating > 5 times in a row
            preg_match('/^(\d)(\d)\1\2{3,}$/', $_POST['phone']) ||                 // Alternating pattern like 0101010101
            preg_match('/^(\d)\1{9}$/', $_POST['phone'])                           // All digits same (e.g., 9999999999)
        ) {
            $message = "Phone number must be 10 digits, not all same, not alternate patterns (e.g., 0101010101), and no digit should repeat more than 5 times consecutively.";
        }


        // Validate Password (at least 8 characters, with letters, numbers, and special characters)
        elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&]).{8,}$/', $_POST['password'])) {
            $message = "Password must be at least 8 characters long and contain a mix of letters, numbers, and special characters.";
        }

        // Confirm password check
        elseif ($_POST['password'] !== $_POST['cpassword']) {
            $message = "Password and Confirm Password do not match.";
        }

        // Validate Address (house number, place, pin code)
        elseif (!preg_match('/^\d+\s*,\s*[^,]+,\s*[^,]+,\s*[^-]+-\s*\d{6}$/', $_POST['address'])) {
            $message = "Address must be in the format: house number, street, place, district - 6-digit PIN (e.g., 123, MG Road, Bangalore, Karnataka - 560001)";
        } else {
            $username = $_POST['username'];
            $type = $_POST['type'];
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $password = $_POST['password'];
            $cpassword = $_POST['cpassword'];
            $address = $_POST['address'];
            // If no validation errors, proceed with database checks

            // Update the user info
            $updateQuery = "UPDATE users SET 
            username = '$username', 
            type = '$type', 
            f_name = '$firstname', 
            l_name = '$lastname', 
            email = '$email', 
            phone = '$phone', 
            password = '$password', 
            address = '$address'
            WHERE u_id = '$user_id'";

            if (mysqli_query($db, $updateQuery)) {
                echo "<script>alert('Account updated successfully!');</script>";
                header("refresh:0.5;url=home.php"); // redirect to profile or any page
            } else {
                echo "<script>alert('Error updating account.');</script>";
            }
        }
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
    <title>Registration</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
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
    </style>

</head>

<body>
    <div style=" background-image: url('images/img/pimg.jpg');">
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
        </li>
        ';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <div class="page-wrapper">
            <div class="container">
                <ul>
                </ul>
            </div>
            <section class="contact-page inner-page">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="widget">
                                <div class="widget-body">
                                    <form action="" method="post">
                                        <?php
                                        // Display the error message if validation fails
                                        if (isset($message)) {
                                            echo "<p style='color: red;'>$message</p>";
                                        }
                                        ?>
                                        <div class="row">
                                            <div class="form-group col-sm-12">
                                                <label>Select Account type</label>
                                                <select class="form-control" name="type">
                                                    <option value="" disabled>Select Account Type</option>
                                                    <option value="buyer" <?= $userData['type'] == 'buyer' ? 'selected' : '' ?>>Buyer</option>
                                                    <option value="seller" <?= $userData['type'] == 'seller' ? 'selected' : '' ?>>Seller</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-12">
                                                <label>User-Name</label>
                                                <input class="form-control" type="text" name="username" value="<?= htmlspecialchars($userData['username']) ?>">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label>First Name</label>
                                                <input class="form-control" type="text" name="firstname" value="<?= htmlspecialchars($userData['f_name']) ?>">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label>Last Name</label>
                                                <input class="form-control" type="text" name="lastname" value="<?= htmlspecialchars($userData['l_name']) ?>">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label>Email Address</label>
                                                <input type="text" class="form-control" name="email" value="<?= htmlspecialchars($userData['email']) ?>">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label>Phone number</label>
                                                <input class="form-control" type="text" name="phone" value="<?= htmlspecialchars($userData['phone']) ?>">
                                            </div>

                                            <div class="form-group col-sm-6">
                                                <label>Password</label>
                                                <div style="position: relative;">
                                                    <div style="position: relative;">
                                                        <input type="password" id="password" class="form-control" name="password" value="<?= htmlspecialchars($userData['password']) ?>">
                                                        <i class="fas fa-eye" id="togglePassword" style="position:absolute; top: 12px; right: 15px; cursor:pointer;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label>Confirm password</label>
                                                <div style="position: relative;">
                                                    <input type="password" id="cpassword" class="form-control" name="cpassword" value="<?= htmlspecialchars($userData['password']) ?>">
                                                    <i class="fas fa-eye" id="toggleCPassword" style="position:absolute; top: 12px; right: 15px; cursor:pointer;"></i>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12">
                                                <label>Address</label>
                                                <textarea class="form-control" name="address" rows="3"><?= htmlspecialchars($userData['address']) ?></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <p> <input type="submit" value="Update" name="submit" class="btn theme-btn"> </p>
                                                <div style="margin-top: 10px;">
                                                    <a href="javascript:history.back()" style="color: #5c4ac7; font-size: 14px;">Back</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <?php include "include/footer.php" ?>
        </div>

        <!-- AJAX Script -->
        <script src="js/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
                // Username Validation with AJAX
                $('#username').keyup(function() {
                    var username = $(this).val();
                    if (username.length > 0) {
                        $.ajax({
                            url: 'check_availability.php',
                            method: 'POST',
                            data: {
                                username: username
                            },
                            success: function(response) {
                                $('#username-status').html(response);
                            }
                        });
                    } else {
                        $('#username-status').html('');
                    }
                });

                // Email Validation with AJAX
                $('#email').keyup(function() {
                    var email = $(this).val();
                    if (email.length > 0) {
                        $.ajax({
                            url: 'check_availability.php',
                            method: 'POST',
                            data: {
                                email: email
                            },
                            success: function(response) {
                                $('#email-status').html(response);
                            }
                        });
                    } else {
                        $('#email-status').html('');
                    }
                });

                // Phone Validation with AJAX
                $('#phone').keyup(function() {
                    var phone = $(this).val();
                    if (phone.length > 0) {
                        $.ajax({
                            url: 'check_availability.php',
                            method: 'POST',
                            data: {
                                phone: phone
                            },
                            success: function(response) {
                                $('#phone-status').html(response);
                            }
                        });
                    } else {
                        $('#phone-status').html('');
                    }
                });
            });
        </script>
        <script>
            // Toggle Password
            document.getElementById("togglePassword").addEventListener("click", function() {
                const pwd = document.getElementById("password");
                const type = pwd.getAttribute("type") === "password" ? "text" : "password";
                pwd.setAttribute("type", type);
                this.classList.toggle("fa-eye-slash");
            });

            // Toggle Confirm Password
            document.getElementById("toggleCPassword").addEventListener("click", function() {
                const cpwd = document.getElementById("cpassword");
                const type = cpwd.getAttribute("type") === "password" ? "text" : "password";
                cpwd.setAttribute("type", type);
                this.classList.toggle("fa-eye-slash");
            });
        </script>
    </div>

    <script src="js/jquerys.js"></script>
</body>

</html>