<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if (isset($_GET['u_id'])) {
    $delete = mysqli_query($db, "DELETE FROM users WHERE u_id = '".$_GET['u_id']."'");

    if ($delete) {
        $message = "User deleted successfully!";
    } else {
        $message = "Failed to delete user.";
    }

    echo "<script>
            alert('$message');
            window.location.href = 'all_users.php';
          </script>";
    exit();
}
?>
