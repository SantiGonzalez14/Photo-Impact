<?php
require_once '../includes/db.php';

session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../Page/login.php");
    exit();
}

if (!isset($_GET['booking_id'])) {
    die("Booking ID is required.");
}

$booking_id = (int)trim($_GET['booking_id']);

$stmt = $conn->prepare("UPDATE bookings SET booking_status = 'completed' WHERE booking_id = ?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$stmt->close();


header("Location: manage-bookings.php");
exit();
?>