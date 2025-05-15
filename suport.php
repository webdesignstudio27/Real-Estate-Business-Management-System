<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if (empty($_SESSION["adm_id"])) {
    header('location:index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>All Messages</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .chat-container {
            display: flex;
            height: 500px;
            border: 1px solid #ccc;
        }

        .user-list {
            width: 250px;
            background-color: #f1f1f1;
            overflow-y: auto;
            border-right: 1px solid #ccc;
            padding: 10px;
        }

        .user-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .username-link {
            text-decoration: none;
            color: #333;
        }

        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            /* Prevent overflow issues */
        }

        .chat-box {
            display: flex;
            flex-direction: column;
            flex: 1;
            width: 100%;
            background: #fff;
        }

        .chat-header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            font-weight: bold;
        }

        .chat-messages {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            background-color: #fafafa;
        }

        .chat-input {
            display: flex;
            border-top: 1px solid #ccc;
            height: 50px;
        }

        .chat-input input {
            flex: 1;
            padding: 0 10px;
            border: none;
            outline: none;
            font-size: 16px;
            height: 100%;
            /* make input match .chat-input height */
            box-sizing: border-box;
            /* important for height calculations */
        }

        .chat-input button {
            width: 100px;
            height: 100%;
            /* make button match .chat-input height */
            border: none;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
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
                    <div class="col-12">
                        <div class="col-lg-12">
                            <div class="card card-outline-primary">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white">Customer Support - View All Messages</h4>
                                </div>

                                <div class="table-responsive m-t-40">
                                    <!-- Move this chat UI outside the table -->
                                    <div class="chat-container">
                                        <div class="user-list">
                                            <?php
                                            $sql = "SELECT sender_id, MAX(send_at) as latest_message 
                FROM messages 
                GROUP BY sender_id 
                ORDER BY latest_message DESC";
                                            $query = mysqli_query($db, $sql);

                                            if (mysqli_num_rows($query) == 0) {
                                                echo '<div class="no-users">No Messages</div>';
                                            } else {
                                                while ($row = mysqli_fetch_array($query)) {
                                                    $uid = $row['sender_id'];
                                                    $userQuery = mysqli_query($db, "SELECT username FROM users WHERE u_id = '$uid'");
                                                    $userData = mysqli_fetch_array($userQuery);
                                                    $username = $userData['username'] ?? 'Unknown';

                                                    echo '<div class="user-item"><a href="#" class="username-link" data-uid="' . $uid . '">' . htmlspecialchars($username) . '</a></div>';
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div class="chat-main" id="chat-main">
                                            <div class="chat-box" id="chat-box">
                                                <div class="chat-header">Select a user to start chat</div>
                                                <div class="chat-messages" id="chat-messages">
                                                    <!-- Messages will load here -->
                                                </div>
                                                <div class="chat-input">
                                                    <input type="text" id="message-input" placeholder="Type your message..." />
                                                    <button id="send-btn">Send</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let selectedUid = null;

        // Load chat messages when a user link is clicked
        document.querySelectorAll('.username-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                selectedUid = this.dataset.uid;

                // Load messages
                fetch('get_messages.php?uid=' + selectedUid)

                    .then(res => res.text())
                    .then(data => {
                        document.getElementById('chat-messages').innerHTML = data;
                        document.querySelector('.chat-header').textContent = 'Chat with User ID: ' + selectedUid;
                    });
            });
        });

        // Send reply via reply.php
        document.getElementById('send-btn').addEventListener('click', () => {
            const message = document.getElementById('message-input').value.trim();
            if (!selectedUid || !message) return;

            fetch('reply.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'uid=' + selectedUid + '&reply=' + encodeURIComponent(message)
                })
                .then(res => res.text())
                .then(() => {
                    document.getElementById('message-input').value = '';
                    // Reload updated messages
                    return fetch('get_messages.php?uid=' + selectedUid);
                })
                .then(res => res.text())
                .then(data => {
                    document.getElementById('chat-messages').innerHTML = data;
                });
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

</body>

</html>