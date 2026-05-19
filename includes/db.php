<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "photo_impact";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "<script>console.log('Connected successfully');</script>";