<?php
include("connection/connect.php");
session_start();
error_reporting(0);

$message = "";
$success = "";
$question = "";
$phoneFound = false;

// Check if form is submitted
if (isset($_POST['submit'])) {
    $phone = $_POST['phone'];

    if (!empty($phone)) {
        $stmt = $db->prepare("SELECT * FROM users WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            // phone found, show question
            $_SESSION['reset_phone'] = $phone;
            $question = $row['question'];
            $phoneFound = true;
        } else {
            $message = "phone not found!";
        }
    } else {
        $message = "Please enter your phone.";
    }
}

// Handle answer check
if (isset($_POST['check_answer'])) {
    $answer = trim($_POST['answer']);
    $phone = $_SESSION['reset_phone'];

    $stmt = $db->prepare("SELECT * FROM users WHERE phone = ? AND answer = ?");
    $stmt->bind_param("ss", $phone, $answer);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $_SESSION['user_to_reset'] = $row['u_id']; // or store phone if you prefer
        header("Location: change_password.php");
        exit();
    } else {
        $message = "Incorrect answer to the security question.";
        $question = $_POST['hidden_question']; // repopulate the question
        $phoneFound = true;
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forget Password || Web Design Studio</title>

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
                        <h2>Find Your account</h2>
                        <span style="color:red;"><?php echo $message; ?></span>
                        <span style="color:green;"><?php echo $success; ?></span>
                        <form action="" method="post">
                            <?php if (!$phoneFound): ?>
                                <input type="text" placeholder="Enter Your phone" name="phone" required />
                                <input type="submit" id="buttn" name="submit" value="Verify Phone" />
                            <?php else: ?>
                                <label>Security Question</label>
                                <input type="text" name="question_display" value="<?php echo htmlspecialchars($question); ?>" readonly>
                                <input type="hidden" name="hidden_question" value="<?php echo htmlspecialchars($question); ?>">
                                <label>Answer</label>
                                <input type="text" name="answer" placeholder="Enter your answer" required>
                                <input type="submit" id="buttn" name="check_answer" value="Submit Answer">
                            <?php endif; ?>

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