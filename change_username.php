<?php
include("connection/connect.php");
session_start();
error_reporting(0);

// Ensure user is coming from reset flow
if (!isset($_SESSION['user_to_reset'])) {
    header("Location: forgot_username.php");
    exit();
}

$message = "";
$success = "";

// Handle username change
if (isset($_POST['submit'])) {
    $new_username = $_POST['username'];
    $confirm_username = $_POST['cusername'];

    
    if (!preg_match('/^[a-zA-Z0-9_@]{5,}$/', $_POST['username'])) {
        $message = "Username must be at least 5 characters long and can contain letters, numbers, _ or @.";
    } elseif ($new_username !== $confirm_username) {
        $message = "usernames do not match.";
    } else {
        // Everything is valid, update username
        $stmt = $db->prepare("UPDATE users SET username = ? WHERE u_id = ?");
        $stmt->bind_param("si", $new_username, $_SESSION['user_to_reset']);
        $stmt->execute();

        $success = "username changed successfully! <a href='login.php'>Click here to login</a>";

        // Optionally destroy session after username reset
        session_destroy();
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forget username || Web Design Studio</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">

    <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>

    <link rel="stylesheet" href="css/login.css">

    <style type="text/css">
        #buttn {
            color: #fff;
            background-color: #5c4ac7;
        }
    </style>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">




    <link rel="stylesheet" href="css/login.css">

    <style type="text/css">
        #buttn {
            color: #fff;
            background-color: #5c4ac7;
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
                                <img class="img-icon" src="images/Property.png"> Properties
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
              <li class="nav-item">
                <a href="logout.php" class="nav-link active">
                    <img class="img-icon" src="images/logout.png"> Logout
                </a>
              </li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div style=" background-image: url('images/img/bg.jpg');">

        <div class="pen-title">
            < </div>

                <div class="module form-module">
                    <div class="toggle">

                    </div>
                    <div class="form">
                        <h2>Change Your username</h2>
                        <span style="color:red;"><?php echo $message; ?></span>
                        <span style="color:green;"><?php echo $success; ?></span>
                        <form method="post">
                            <input type="username" name="username" placeholder="New username" required>
                            <input type="username" name="cusername" placeholder="Confirm username" required>
                            <input type="submit" name="submit" id="buttn" value="Change username">
                            <div style="margin-top: 10px;">
                                <a href="login.php" style="color: #5c4ac7; font-size: 14px;">Back</a>
                            </div>
                        </form>
                    </div>

                    <div class="cta">Not registered?<a href="registration.php" style="color:#5c4ac7;"> Create an account</a></div>
                </div>
                <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

                <div class="container-fluid pt-3">
                    <p></p>
                </div>
                <?php include "include/footer.php" ?>


</body>

</html>