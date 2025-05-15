<?php
session_start();
include("connection/connect.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$u_id = $_SESSION['user_id'];

$query = "SELECT 
            message_id,
            send_msg,
            send_at, 
            receive_msg,
            receive_at
          FROM messages 
          WHERE sender_id = ? 
          ORDER BY send_at  DESC";

$stmt = $pdo->prepare($query);
$stmt->execute([$u_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$messages = [];

foreach ($rows as $row) {
    $msg_lines = explode("\n", $row['send_msg']);
    $time_lines = explode("\n", $row['send_at']); // ✅ correct

    $msg_lines1 = explode("\n", $row['receive_msg']);
    $time_lines1 = explode(",", $row['receive_at']);

    foreach ($msg_lines as $index => $msg) {
      $messages[] = [
          'message_id' => $row['message_id'],
          'send_msg' => trim($msg),
          'send_at' => $time_lines[$index] ?? null
      ];
  }
  
  foreach ($msg_lines1 as $index => $msg) {
      $messages[] = [
          'message_id' => $row['message_id'],
          'receive_msg' => trim($msg),
          'receive_at' => trim($time_lines1[$index] ?? '')
      ];
  }
  
}




echo json_encode($messages);
?>
