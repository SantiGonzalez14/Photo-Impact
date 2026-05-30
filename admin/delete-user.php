<?php
require_once '../includes/db.php';

session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../Page/login.php");
    exit();
}

if (!isset($_GET['user_id'])) {
    die("User ID is required.");
}

$user_id = (int)trim($_GET['user_id']);

$stmt = $conn->prepare("UPDATE users SET is_hidden = 1 WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

header("Location: manage-users.php");
exit();
?>