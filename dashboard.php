<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
if (empty($_SESSION["adm_id"])) {
    header('location:index.php');
} else {
?>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Admin Panel</title>
        <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
        <link href="css/helper.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            #statsModal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }

            #statsModal .modal-content {
                background-color: white;
                padding: 20px;
                border-radius: 10px;
                max-width: 800px;
                margin: 100px auto;
                position: relative;
            }

            #statsModal .close {
                position: absolute;
                top: 10px;
                right: 10px;
                font-size: 30px;
                cursor: pointer;
            }
        </style>

    </head>

    <body class="fix-header">

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
                    <div class="col-lg-12">
                        <div class="card card-outline-primary">
                            <div style="margin-top: 10px;">
                                <a href="javascript:history.back()" style="color: #5c4ac7; font-size: 14px;">Back</a>
                            </div>
                            <div class="card-header">
                                <h4 class="m-b-0 text-white">Admin Dashboard</h4>
                            </div>
                            <div class="row" id="dashboard-cards">

                                <div class="col-md-3">
                                    <div class="card p-30" data-type="buyers">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-users f-s-40"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php $sql = "select * from users WHERE type='buyer'";
                                                    $result = mysqli_query($db, $sql);
                                                    $rws = mysqli_num_rows($result);

                                                    echo $rws; ?></h2>
                                                <p class="m-b-0">Total Buyers</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card p-30" data-type="sellers">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-users f-s-40"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php $sql = "select * from users WHERE type='seller'";
                                                    $result = mysqli_query($db, $sql);
                                                    $rws = mysqli_num_rows($result);

                                                    echo $rws; ?></h2>
                                                <p class="m-b-0"> Total Sellers</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card p-30" data-type="bookings">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-shopping-cart f-s-40" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php $sql = "select * from bookings";
                                                    $result = mysqli_query($db, $sql);
                                                    $rws = mysqli_num_rows($result);

                                                    echo $rws; ?></h2>
                                                <p class="m-b-0">Total Booked Properties </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card p-30" data-type="transactions">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-money f-s-40" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php $sql = "select * from transactions";
                                                    $result = mysqli_query($db, $sql);
                                                    $rws = mysqli_num_rows($result);

                                                    echo $rws; ?></h2>
                                                <p class="m-b-0">Total Transactions </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="statsModal" class="modal" style="display:none;">
                                <div class="modal-content">
                                    <span class="close">&times;</span>
                                    <canvas id="statsChart"></canvas>
                                </div>
                            </div>

                            <div class="row" id="dashboard-cards">
                                <div class="col-md-4">
                                    <div class="card p-30" data-type="properties">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-th-large f-s-40" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php $sql = "select * from properties";
                                                    $result = mysqli_query($db, $sql);
                                                    $rws = mysqli_num_rows($result);

                                                    echo $rws; ?></h2>
                                                <p class="m-b-0">Total Property</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4" data-type="rents">
                                    <div class="card p-30">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-spinner f-s-40" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php $sql = "select * from properties WHERE post_type = 'rent' ";
                                                    $result = mysqli_query($db, $sql);
                                                    $rws = mysqli_num_rows($result);

                                                    echo $rws; ?></h2>
                                                <p class="m-b-0">Rented</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card p-30" data-type="delivers">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-check f-s-40" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php $sql = "select * from bookings WHERE status = 'confirmed' ";
                                                    $result = mysqli_query($db, $sql);
                                                    $rws = mysqli_num_rows($result);

                                                    echo $rws; ?></h2>
                                                <p class="m-b-0">Delivered Property</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="statsModal" class="modal" style="display:none;">
                                <div class="modal-content">
                                    <span class="close">&times;</span>
                                    <canvas id="statsChart"></canvas>
                                </div>
                            </div>

                            <div class="row" id="dashboard-cards">
                                <div class="col-md-4">
                                    <div class="card p-30" data-type="cancels">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-times f-s-40" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php $sql = "select * from bookings WHERE status = 'cancelled' ";
                                                    $result = mysqli_query($db, $sql);
                                                    $rws = mysqli_num_rows($result);

                                                    echo $rws; ?></h2>
                                                <p class="m-b-0">Cancelled Orders</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card p-30" data-type="earnings">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-inr f-s-40" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php
                                                    $result = mysqli_query($db, 'SELECT SUM(amount) AS value_sum FROM transactions WHERE payment_status = "Completed"');
                                                    $row = mysqli_fetch_assoc($result);
                                                    $sum = $row['value_sum'];
                                                    echo $sum;
                                                    ?></h2>
                                                <p class="m-b-0">Total Earnings</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                // Check if the 'visit_count' cookie exists
                                if (isset($_COOKIE['visit_count'])) {
                                    // If the cookie exists, increment the visit count
                                    $visit_count = $_COOKIE['visit_count'] + 1;
                                } else {
                                    // If the cookie doesn't exist, set the visit count to 1
                                    $visit_count = 1;
                                }

                                // Set the cookie with the updated visit count (expires in 30 days)
                                setcookie('visit_count', $visit_count, time() + (30 * 24 * 60 * 60), '/'); // 30 days expiration

                                // Fetch total viewers (you can update this logic based on your schema)
                                $result = mysqli_query($db, 'SELECT COUNT(*) AS total_viewers FROM users');
                                $row = mysqli_fetch_assoc($result);
                                $total_viewers = $row['total_viewers'];
                                ?>
                                <div class="col-md-4">
                                    <div class="card p-30" data-type="viewers">
                                        <div class="media">
                                            <div class="media-left meida media-middle">
                                                <span><i class="fa fa-eye f-s-40" aria-hidden="true"></i></span>
                                            </div>
                                            <div class="media-body media-text-right">
                                                <h2><?php echo $total_viewers; ?></h2>
                                                <p class="m-b-0">Total Viewers</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </div>
                            <div id="statsModal" class="modal" style="display:none;">
                                <div class="modal-content">
                                    <span class="close">&times;</span>
                                    <canvas id="statsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        document.querySelectorAll('#dashboard-cards [data-type]').forEach(function(card) {
                            card.addEventListener('click', function() {
                                const type = this.getAttribute('data-type'); // Get clicked card's type

                                // Fetch the monthly trend data for the selected type
                                fetch('get_monthly_stats.php?type=' + type)
                                    .then(response => response.json())
                                    .then(data => {
                                        // Display the bar chart with the fetched data
                                        showBarChart(data.months, data.counts, type);
                                    });
                            });
                        });

                        // Function to render the bar chart
                        function showBarChart(labels, data, type) {
                            document.getElementById('statsModal').style.display = 'block'; // Show modal

                            // Clear any existing chart
                            let ctx = document.getElementById('statsChart').getContext('2d');
                            if (window.barChart) {
                                window.barChart.destroy();
                            }

                            // Create a new bar chart
                            window.barChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels, // Months
                                    datasets: [{
                                        label: 'Monthly ' + type + ' Trends',
                                        data: data, // Count values
                                        backgroundColor: 'rgba(54, 162, 235, 0.6)', // Blue color for the bars
                                        borderColor: 'rgba(54, 162, 235, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            title: {
                                                display: true,
                                                text: 'Count'
                                            }
                                        },
                                        x: {
                                            title: {
                                                display: true,
                                                text: 'Month'
                                            }
                                        }
                                    }
                                }
                            });
                        }

                        // Close the modal when clicking the 'x'
                        document.querySelector('.close').onclick = function() {
                            document.getElementById('statsModal').style.display = 'none';
                        };
                    });
                </script>



                <?php include "include/footer.php" ?>

                <script src="js/lib/jquery/jquery.min.js"></script>
                <script src="js/lib/bootstrap/js/popper.min.js"></script>
                <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
                <script src="js/jquery.slimscroll.js"></script>
                <script src="js/sidebarmenu.js"></script>
                <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
                <script src="js/custom.min.js"></script>
                <script src="js/dash.js"></script>

    </body>

</html>
<?php
}
?>