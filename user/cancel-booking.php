<?php
require_once '../includes/db.php';

session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "user") {
    header("Location: ../Page/login.php");
    exit();
}

if (!isset($_GET['booking_id'])) {
    die("Booking ID is required.");
}

$booking_id = (int)trim($_GET['booking_id']);
try{
    $stmt = $conn->prepare("UPDATE bookings SET booking_status = 'cancelled' WHERE booking_id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->close();
} catch (Exception $e) {
    die("Error cancelling booking: " . $e->getMessage());
}
try{
    $stmt = $conn->prepare("UPDATE booking_reschedule_requests SET request_status = 'cancelled' WHERE booking_id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->close();
} catch (Exception $e) {
    die("Error cancelling related reschedule requests: " . $e->getMessage());
}

header("Location: my-bookings.php");
exit();
?>