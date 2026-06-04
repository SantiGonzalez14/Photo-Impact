<?php
require_once '../includes/db.php';
session_start();

//Admin protection
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../Page/login.php");
    exit();
}

if (!isset($_GET['request_id'])) {
    die("Request ID is required.");
}

$request_id = (int)trim($_GET['request_id']);

try {
    // Update the reschedule request status to 'approved'
    $stmt = $conn->prepare("UPDATE booking_reschedule_requests SET request_status = 'rejected' WHERE request_id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $stmt->close();
} catch (Exception $e) {
    die("Error rejecting reschedule request: " . $e->getMessage());
}

header("Location: manage-reschedule-requests.php");
exit();
?>