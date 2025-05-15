<?php
include("../connection/connect.php");
session_start();

// Check if admin is logged in
if (empty($_SESSION["adm_id"])) {
    header('location:index.php');
    exit();
}

// Handle the reply submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = $_SESSION["adm_id"];
    $sender_id = $_POST['uid'];
    $reply_message = mysqli_real_escape_string($db, $_POST['reply']);

    // Get current time as HH:MM:SS
    $current_time = date("H:i:s");

    // Append reply and time (each on a new line)
    $sql = "UPDATE messages 
            SET 
                receive_msg = CONCAT_WS('\n', IFNULL(receive_msg, ''), '$reply_message'),
                receive_at = CONCAT_WS('\n', IFNULL(receive_at, ''), '$current_time')
            WHERE sender_id = '$sender_id'
            ORDER BY send_at DESC 
            LIMIT 1";

    // Execute and respond
    if (mysqli_query($db, $sql)) {
        echo "Reply sent successfully.";
    } else {
        echo "Error updating record: " . mysqli_error($db);
    }
} else {
    echo "Invalid request.";
}
?>
