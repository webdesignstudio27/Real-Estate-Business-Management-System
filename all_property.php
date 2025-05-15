<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

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
                                    <h4 class="m-b-0 text-white">All Menu</h4>
                                </div>
                                <div class="table-responsive m-t-40">
                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Image</th>
                                                <th>Property</th>
                                                <th>Description</th>
                                                <th>Property_type</th>
                                                <th>Location</th>
                                                <th>Bedrooms</th>
                                                <th>Bathrooms</th>
                                                <th>Price</th>
                                                <th>Document</th>
                                                <th>Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM properties WHERE status !='waitinglist'order by property_id desc";
                                            $query = mysqli_query($db, $sql);
                                            if (!mysqli_num_rows($query) > 0) {
                                                echo '<td colspan="11"><center>No Menu</center></td>';
                                            } else {
                                                while ($rows = mysqli_fetch_array($query)) {
                                                    $allImages = explode(",", str_replace("'", "", $rows['images']));
                                                    $firstImage = $allImages[0]; // Get the first image
                                                    echo '<tr>   
                                                            <td>
                                                                <img src="property_img/property/' . $firstImage . '" class="img-fluid" alt="Property Image" height="100" width="100">
                                                            </td>
                                                            <td>' . $rows['title'] . '</td>
                                                            <td>' . $rows['description'] . '</td>
                                                            <td>' . $rows['property_type'] . '</td>
                                                            <td>' . $rows['location'] . '</td>
                                                            <td>' . $rows['bedrooms'] . '</td>
                                                            <td>' . $rows['bathrooms'] . '</td>
                                                            <td>$' . $rows['price'] . '</td>
                                                            <td>';
                                                    // Check if the 'pdf' column is not NULL or empty before displaying the icon
                                                    if (!empty($rows['pdf'])) {
                                                        echo '<a href="property_img/property/' . $rows['pdf'] . '" target="_blank">
                                                                <img src="images/pdf.png" class="img-fluid" alt="View PDF">
                                                              </a>';
                                                    }
                                                    echo '</td>
                                                          <td>
                                                              <a href="delete_property.php?property_id=' . $rows['property_id'] . '" class="btn btn-danger btn-flat btn-addon btn-xs m-b-10">
                                                                  <i class="fa fa-trash-o" style="font-size:16px"></i>
                                                              </a> 
                                                              <a href="view_details.php?property_id=' . $rows['property_id'] . '" class="btn btn-info btn-flat btn-addon btn-sm m-b-10 m-l-5">
                                                                  <i class="fa fa-eye"></i>
                                                              </a>
                                                          </td>
                                                        </tr>';
                                                }
                                            }

                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    </div>

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