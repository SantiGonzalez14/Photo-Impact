<?php
session_start();
require_once '../includes/db.php';

//Admin protection
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../Page/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Impact - View booking</title>

    <link rel="stylesheet" href="../style/header.css">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/view-data.css">
    <link rel="stylesheet" href="../style/footer.css">

</head>
<body class="view-data-page">
    
    <div id="header-container"></div>

    <section id="view-data-title">
        <h1>Booking details</h1>
    </section>
    <div class="container">

    <?php

    $booking_id = $_GET["booking_id"] ?? null;
    
    if ($booking_id) {
        $stmt = $conn->prepare(
            "SELECT b.*, u.fname, u.lname, u.email, u.phone_number, q.type_of_event, q.event_location, q.delivery_type, q.number_of_pictures, q.quote_value
                FROM bookings b
                JOIN quotes q ON q.quote_id = b.quote_id
                JOIN users u ON q.user_id = u.user_id
                WHERE b.booking_id = ?"
        );

        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<div class=user-info>";
            echo "<h2>User Information</h2>";

            // Display booking and user information here
            while ($row = mysqli_fetch_assoc($result)) {

                $user_email = $row["email"];

                echo "<p><strong>Name:</strong> " . htmlspecialchars($row["fname"] . " " . $row["lname"]) . "</p>";
                echo "<p><strong>Email:</strong> <a href='mailto:" . htmlspecialchars($user_email) . "'>$user_email</a></p>";
                echo "<p><strong>Phone:</strong> " . htmlspecialchars($row["phone_number"]) . "</p>";
                echo "</div>";

                echo "<div class='data-info'>";
                    echo "<h2>Booking information</h2>";
                    echo "<p><strong>Booking ID:</strong> " . htmlspecialchars($row["booking_id"]) . "</p>";
                    echo "<p><strong>Quote ID:</strong> " . htmlspecialchars($row["quote_id"]) . "</p>";
                    echo "<p><strong>Event Type:</strong> " . htmlspecialchars($row["type_of_event"]) . "</p>";
                    echo "<p><strong>Event location:</strong> " . htmlspecialchars($row["event_location"]) . "</p>";
                    echo "<p><strong>Event date:</strong> " . htmlspecialchars($row["event_date"]) . "</p>";
                    echo "<p><strong>Delivery Type:</strong> " . htmlspecialchars($row["delivery_type"]) . "</p>";
                    echo "<p><strong>Number of Pictures:</strong> " . htmlspecialchars($row["number_of_pictures"]) . "</p>";
                    echo "<p><strong>Total Price:</strong> $" . htmlspecialchars($row["quote_value"]) . "</p>";
                    echo "<p><strong>Booking status:</strong> " . htmlspecialchars($row["booking_status"]) . "</p>";
                echo "</div>";

            }
        } else {
            echo "<p>Booking not found.</p>";
            exit();
        }
    } else {
        echo "<p>No booking ID provided.</p>";
        exit();
    }
    ?>
    
    </div>

    <div id="footer-container"></div>

    <script src="../js/loadHeader.js"></script>
    <script src="../js/loadFooter.js"></script>
</body>
</html>