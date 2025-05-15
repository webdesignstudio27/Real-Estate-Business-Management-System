<?php
include("connection/connect.php"); //connection to db
error_reporting(0);
session_start();

mysqli_query($db, "DELETE FROM bookings WHERE booking_id = '" . $_GET['booking_id'] . "' ");



header("location:your_orders.php"); 

?>
  