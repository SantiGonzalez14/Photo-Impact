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
$requested_event_date = trim($_GET['requested_event_date']);

try {
    // Check if the new requested date conflicts with existing bookings that are scheduled for the same date
    $stmt = $conn->prepare(
    "SELECT booking_id
     FROM bookings
     WHERE event_date = ? AND booking_status = 'scheduled'"
    );

    $stmt->bind_param("s", $requested_event_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION["reschedule_error"] =
        "Request #$request_id: This reschedule request cannot be approved because the requested date conflicts with an existing booking. Reject the request and ask the client to choose a different date.";

        header("Location: manage-reschedule-requests.php");
        exit();
    }
} catch (Exception $e) {
    die("Error preparing reschedule request: " . $e->getMessage());
}

try {
    // Update the reschedule request status to 'approved'
    $stmt = $conn->prepare("UPDATE booking_reschedule_requests SET request_status = 'approved' WHERE request_id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $stmt->close();
} catch (Exception $e) {
    die("Error updating reschedule request: " . $e->getMessage());
}

try{
    // Update the booking with the new event date
    $stmt = $conn->prepare(
        "UPDATE bookings b
         JOIN booking_reschedule_requests r ON b.booking_id = r.booking_id
         SET b.event_date = r.requested_event_date
         WHERE r.request_id = ?"
    );
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $stmt->close();
} catch (Exception $e) {
    die("Error updating booking date: " . $e->getMessage());
}

header("Location: manage-reschedule-requests.php");
exit();
?>