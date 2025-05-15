<?php
include("connection/connect.php"); //connection to db
error_reporting(0);
session_start();


mysqli_query($db, "UPDATE bookings AS b, properties AS p  
SET b.status = 'cancelled', p.status = 'available'  
WHERE b.booking_id = '" . $_GET['booking_id'] . "' AND b.property_id = p.property_id");


header("location:your_orders.php"); 

?>
  