<?php
include("connection/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $propertyId = $_POST['property_id'];
    $status = $_POST['status'];

    // Update query
    $updateQuery = "UPDATE properties SET status = '$status' WHERE property_id = '$propertyId'";

    if (mysqli_query($db, $updateQuery)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
