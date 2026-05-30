<?php
require_once '../includes/db.php';

session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../Page/login.php");
    exit();
}

if (!isset($_GET['quote_id'])) {
    die("Quote ID is required.");
}

$quote_id = (int)trim($_GET['quote_id']);

$stmt = $conn->prepare("UPDATE quotes SET quote_status = 'approved' WHERE quote_id = ?");
$stmt->bind_param("i", $quote_id);
$stmt->execute();
$stmt->close();

$stmt = $conn->prepare("INSERT INTO bookings (quote_id, booking_date, event_date, booking_status) VALUES (?, CURDATE(), (SELECT event_date FROM quotes WHERE quote_id = ?), 'scheduled')");
$stmt->bind_param("ii", $quote_id, $quote_id);
$stmt->execute();
$stmt->close();

header("Location: manage-quotes.php");
exit();
?>