<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $type_of_event = $_POST["type_of_event"];
    $type_of_pictures = $_POST["type_of_pictures"];
    $no_of_pics = $_POST["no_of_pics"];
    $extra_pics = $_POST["extra_pics"];
    $price = $_POST["price"];
    $event_date = $_POST["event_date"];
    $event_location = $_POST["event_location"];
    $special_requests = $_POST["special_requests"];

    $sql = "INSERT INTO quotes
            (
                type_of_event,
                type_of_pictures,
                no_of_pics,
                extra_pics,
                price,
                event_date,
                event_location,
                special_requests
            )

            VALUES
            (
                '$type_of_event',
                '$type_of_pictures',
                '$no_of_pics',
                '$extra_pics',
                '$price',
                '$event_date',
                '$event_location',
                '$special_requests'
            )";

    if (mysqli_query($conn, $sql)) {

        echo "<h2>Quote Saved Successfully!</h2>";

        echo "<a href='make-a-quote.php'>Make Another Quote</a>";

    } else {

        echo "Error: " . mysqli_error($conn);

    }

} else {

    header("Location: make-a-quote.php");

    exit();
}
?>