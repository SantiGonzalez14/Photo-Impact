<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../includes/db.php';

// User protection
if (!isset($_SESSION["user_id"])) {
    header("Location: ../Page/login.php");
    exit();
}

$userId = $_SESSION["user_id"];
$bookingId = $_POST["booking_id"];

// Fetch new date and reason for the booking
$currentDate = $_POST["current_date_input"];
$newDate = $_POST["requested_event_date"];
$reason = $_POST["reason"] ?? "No reason provided";

try {
    // Check if a pending reschedule request already exists for this booking
    $stmt = $conn->prepare(
        "SELECT request_id
         FROM booking_reschedule_requests
         WHERE booking_id = ?
         AND request_status = 'pending'"
    );
    
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION["reschedule_error"] =
        "A reschedule request is already pending for this booking. Please wait for the admin to review your request before submitting another one.";

        $_SESSION["reschedule_form"] = [
            "booking_id" => $bookingId,
            "current_date" => $currentDate,
            "requested_event_date" => $newDate,
            "reason" => $reason
        ];

        $_SESSION["reschedule_booking_id"] = $bookingId;

        header("Location: my-bookings.php");
        exit();
    }

} catch (Exception $e) {
    die("Error checking existing reschedule requests: " . $e->getMessage());
}

try {
    // Check if the new requested date conflicts with existing bookings that are scheduled for the same date
    $stmt = $conn->prepare(
    "SELECT booking_id
     FROM bookings
     WHERE event_date = ? AND booking_status = 'scheduled'"
    );

    $stmt->bind_param("s", $newDate);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION["reschedule_error"] =
        "The selected date is already booked. Please choose another date.";

        $_SESSION["reschedule_form"] = [
            "booking_id" => $bookingId,
            "current_date" => $currentDate,
            "requested_event_date" => $newDate,
            "reason" => $reason
        ];

        $_SESSION["reschedule_booking_id"] = $bookingId;

        header("Location: my-bookings.php");
        exit();
    }
} catch (Exception $e) {
    die("Error preparing reschedule request: " . $e->getMessage());
}

try {
    // Insert new reschedule request
    $stmt = $conn->prepare(
        "INSERT INTO booking_reschedule_requests
        (
            booking_id,
            current_event_date,
            requested_event_date,
            reschedule_reason
        )
        VALUES (?, ?, ?, ?)"
    );
    
    $stmt->bind_param(
        "isss",
        $bookingId,
        $currentDate,
        $newDate,
        $reason
    );
    
    $stmt->execute();

} catch (Exception $e) {
    die("Error submitting reschedule request: " . $e->getMessage());
} finally {
    $stmt->close();
    $conn->close();
    header("Location: my-bookings.php");
    exit();
}

?>