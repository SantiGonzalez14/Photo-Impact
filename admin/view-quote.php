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
    <title>Photo Impact - View quote</title>

    <link rel="stylesheet" href="../style/header.css">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/view-quote.css">
    <link rel="stylesheet" href="../style/footer.css">

</head>
<body class="view-quote-page">
    
    <div id="header-container"></div>

    <section id="view-quote-title">
        <h1>Quote details</h1>
    </section>
    <div class="container">
        <div class="user-info">
            <h2>User Information</h2>
            <p><strong>Name:</strong> John Doe</p>
            <p><strong>Email:</strong>
                <a href="mailto:support@example.com">Contact Support</a>
            <p><strong>Phone:</strong> (123) 456-7890</p>
        </div>

        <div class="quote-info">
            <h2>Quote Details</h2>
            <p><strong>Event Type:</strong> Wedding</p>
            <p><strong>Delivery Type:</strong> Digital</p>
            <p><strong>Number of Pictures:</strong> 100</p>
            <p><strong>Total Price:</strong> $2000</p>
        </div>
    
    </div>

    <div id="footer-container"></div>

    <script src="../js/loadHeader.js"></script>
    <script src="../js/loadFooter.js"></script>
</body>
</html>