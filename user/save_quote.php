<?php
require_once '../includes/db.php';
session_start();

if($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ./Page/quote.html");
    exit();

}

//UserId
//$userId = $_SESSION["userId"];
$userId = 1; // Placeholder for testing, replace with actual user ID from session

//DATA
$quote_value = $_POST["price"];

$delivery_type = $_POST["type_of_pictures"];

$number_of_pictures = $_POST["no_of_pics"];
if(!empty($_POST["extra_pics"])) {
    $number_of_pictures = $_POST["extra_pics"];
}

$type_of_event = $_POST["type_of_event"];

$event_date = $_POST["event_date"];

$event_location = $_POST["event_location"];

$special_requests = $_POST["special_requests"] ?? "";

$quote_status = "pending";

$created_at = date("Y-m-d H:i:s");

$stmt = $conn->prepare(
    "INSERT into quotes (user_id, quote_value, delivery_type, number_of_pictures, type_of_event, 
    event_date, event_location, special_requests, quote_status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

$stmt->bind_param(
    "idsisssss",
    $userId,
    $quote_value,
    $delivery_type,
    $number_of_pictures,
    $type_of_event,
    $event_date,
    $event_location,
    $special_requests,
    $quote_status
);

$success = $stmt->execute();

if($success) {
    echo "
    <script>

        alert(
            'Your quote has been saved. We will contact you soon.'
        );

        window.location.href = '../Page/quote.html';

    </script>
    ";

} else {
    echo "
    <script>

        alert(
            'Error saving quote: " . $stmt->error . "'
        );

        window.location.href = '../Page/quote.html';

    </script>
    ";
}

?>