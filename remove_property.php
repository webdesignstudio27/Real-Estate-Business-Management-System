<?php
session_start();
include("connection/connect.php"); // Ensure this file contains a valid database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login to remove properties.'); window.location.href='login.php';</script>";
    exit;
}

// Validate and sanitize property_id
if (!isset($_GET['property_id']) || empty($_GET['property_id'])) {
    echo "<script>alert('Invalid property ID.'); window.history.back();</script>";
    exit;
}

$u_id = $_SESSION['user_id']; // Logged-in user's ID
$property_id = intval($_GET['property_id']); // Convert to integer for security

// Check if the database connection is successful
if (!$db) {
    die("<script>alert('Database connection failed: " . mysqli_connect_error() . "');</script>");
}

// Check if the property exists in savelists before deleting
$checkQuery = "SELECT * FROM savelists WHERE u_id = ? AND property_id = ?";
$stmt = $db->prepare($checkQuery);
$stmt->bind_param("ii", $u_id, $property_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) { // If property exists, delete it
    $query = "DELETE FROM savelists WHERE u_id = ? AND property_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $u_id, $property_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Property removed successfully!'); window.history.back();</script>";
        exit;
    } else {
        echo "<script>alert('Failed to remove property.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Property not found in saved list.'); window.history.back();</script>";
}

$stmt->close();
$db->close();
?>
