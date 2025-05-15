<?php
include("../connection/connect.php"); // adjust path if needed
session_start();

if (!isset($_GET['uid'])) {
    echo "<div>Invalid user ID.</div>";
    exit;
}

$uid = $_GET['uid'];

$sql = "SELECT * FROM messages WHERE sender_id = '$uid' LIMIT 1";
$query = mysqli_query($db, $sql);
$row = mysqli_fetch_assoc($query);

if (!$row) {
    echo "<div>No messages yet.</div>";
    exit;
}

// Split the stored messages and timestamps
$user_msgs = explode("\n", trim($row['send_msg'], "'"));
$user_times = explode("\n", trim($row['send_at'], "'"));

$admin_msgs = explode("\n", trim($row['receive_msg'], "'"));
$admin_times = explode("\n", trim($row['receive_at'], "'"));

$max_len = max(count($user_msgs), count($admin_msgs));

for ($i = 0; $i < $max_len; $i++) {
    // User message (right)
    if (isset($user_msgs[$i])) {
        echo '<div style="text-align: left; margin: 10px 0;">
                <span style="background-color: #007bff; color: white; padding: 8px 12px; border-radius: 10px; display: inline-block; max-width: 70%;">
                    ' . htmlspecialchars($user_msgs[$i]) . '<br><small>' . ($user_times[$i] ?? '') . '</small>
                </span>
              </div>';
    }

    // Admin reply (left)
    if (isset($admin_msgs[$i])) {
        echo '<div style="text-align: right; margin: 10px 0;">
                <span style="background-color: #f1f1f1; color: black; padding: 8px 12px; border-radius: 10px; display: inline-block; max-width: 70%;">
                    ' . htmlspecialchars($admin_msgs[$i]) . '<br><small>' . ($admin_times[$i] ?? '') . '</small>
                </span>
              </div>';
    }
}
?>
