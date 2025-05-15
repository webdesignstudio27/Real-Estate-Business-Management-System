<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if (isset($_GET['property_id'])) {
    $property_id = $_GET['property_id'];

    // Change status to 'waitinglist' instead of deleting the row
    $update = mysqli_query($db, "UPDATE properties SET status = 'waitinglist' WHERE property_id = '$property_id'");

    if ($update) {
        $message = "Property status changed to waiting list successfully!";
    } else {
        $message = "Failed to change property status.";
    }

    echo "<script>
            alert('$message');
            window.location.href = 'all_property.php';
          </script>";
    exit();
}
?>
