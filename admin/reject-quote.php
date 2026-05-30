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

$stmt = $conn->prepare("UPDATE quotes SET quote_status = 'rejected' WHERE quote_id = ?");
$stmt->bind_param("i", $quote_id);
$stmt->execute();
$stmt->close();

header("Location: manage-quotes.php");
exit();
?>