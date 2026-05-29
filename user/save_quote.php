<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../includes/db.php';

session_start();

if($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ./Page/quote.html");
    exit();
}

//UserId
$userId = $_SESSION["user_id"];

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
try {
    $stmt->execute();
    echo "
    <script>

        alert(
            'Your quote has been saved. We will contact you soon.'
        );

        window.location.href = '../user/make-a-quote.php';

    </script>
    ";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    echo "
    <script>

        alert(
            'Error saving quote: " . $stmt->error . "'
        );

        window.location.href = '../user/make-a-quote.php';

    </script>
    ";
    exit();
}
/*$success = $stmt->execute();

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
}*/

?>