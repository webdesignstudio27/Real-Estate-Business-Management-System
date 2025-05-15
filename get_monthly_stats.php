<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../connection/connect.php"); // Make sure this path is correct

if (!isset($_GET['type'])) {
    echo json_encode(["error" => "Missing 'type' parameter"]);
    exit;
}

$type = $_GET['type'];
$counts = [];
$months = [];

switch ($type) {
    case 'buyers':
        $query = "SELECT MONTH(date) AS month, COUNT(*) AS total FROM users WHERE type='buyer' GROUP BY MONTH(date)";
        break;
    case 'sellers':
        $query = "SELECT MONTH(date) AS month, COUNT(*) AS total FROM users WHERE type='seller' GROUP BY MONTH(date)";
        break;
    case 'bookings':
        $query = "SELECT MONTH(created_at) AS month, COUNT(*) AS total FROM bookings GROUP BY MONTH(created_at)";
        break;
    case 'transactions':
        $query = "SELECT MONTH(created_at) AS month, COUNT(*) AS total FROM transactions GROUP BY MONTH(created_at)";
        break;
    case 'properties':
        $query = "SELECT MONTH(created_at) AS month, COUNT(*) AS total FROM properties GROUP BY MONTH(created_at)";
        break;
    case 'rents':
        $query = "SELECT MONTH(created_at) AS month, COUNT(*) AS total FROM properties WHERE post_type='rent' GROUP BY MONTH(created_at)";
        break;
    case 'delivers':
        $query = "SELECT MONTH(created_at) AS month, COUNT(*) AS total FROM bookings WHERE status='confirmed' GROUP BY MONTH(created_at)";
        break;
    case 'cancels':
        $query = "SELECT MONTH(created_at) AS month, COUNT(*) AS total FROM bookings WHERE status='cancelled' GROUP BY MONTH(created_at)";
        break;
    case 'earnings':
        $query = "SELECT MONTH(created_at) AS month, SUM(amount) AS total FROM transactions WHERE payment_status='completed' GROUP BY MONTH(created_at)";
        break;
    case 'viewers':
        $query = "SELECT MONTH(date) AS month, COUNT(*) AS total FROM users GROUP BY MONTH(date)";
        break;
    default:
        echo json_encode(["error" => "Invalid 'type' parameter"]);
        exit;
}

$result = mysqli_query($db, $query);

if (!$result) {
    echo json_encode(["error" => "Query failed: " . mysqli_error($db)]);
    exit;
}

while ($row = mysqli_fetch_assoc($result)) {
    $months[] = date("F", mktime(0, 0, 0, $row['month'], 10)); // Convert month number to month name
    $counts[] = (int)$row['total'];
}

echo json_encode(["months" => $months, "counts" => $counts]);
exit;
