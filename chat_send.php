<?php
session_start();
date_default_timezone_set('Asia/Kolkata'); 
include("connection/connect.php");

if (!isset($_SESSION['user_id']) || empty($_POST['message'])) {
    echo json_encode(['status' => 'error']);
    exit;
}

$message = trim($_POST['message']);
$u_id = $_SESSION['user_id'];
$current_time = date("H:i:s");

// Split message into individual lines
$new_lines = array_filter(array_map('trim', explode("\n", $message)));
$new_times = array_fill(0, count($new_lines), $current_time); // Same time for each

// Step 1: Get latest message row for this sender
$stmt = $pdo->prepare("SELECT message_id, send_msg, send_at FROM messages WHERE sender_id = ? ORDER BY message_id DESC LIMIT 1");
$stmt->execute([$u_id]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);

// Step 2: Check if within 12 hours
$within_12_hours = false;
if ($existing && !empty($existing['send_at'])) {
    $times = explode("\n", $existing['send_at']);
    $last_time = end($times);
    $last_time_full = date("Y-m-d") . ' ' . $last_time;

    if (strtotime($last_time_full) >= strtotime('-12 hours')) {
        $within_12_hours = true;
    }
}

// Step 3: Append or Insert
if ($existing && $within_12_hours) {
    $updated_msg = $existing['send_msg'] . "\n" . implode("\n", $new_lines);
    $updated_time = $existing['send_at'] . "\n" . implode("\n", $new_times);

    $updateStmt = $pdo->prepare("UPDATE messages SET send_msg = ?, send_at = ? WHERE message_id = ?");
    $updateStmt->execute([$updated_msg, $updated_time, $existing['message_id']]);
} else {
    $insertStmt = $pdo->prepare("INSERT INTO messages (sender_id, send_msg, send_at) VALUES (?, ?, ?)");
    $insertStmt->execute([
        $u_id,
        implode("\n", $new_lines),
        implode("\n", $new_times)
    ]);
}

echo json_encode(['status' => 'success']);
?>
