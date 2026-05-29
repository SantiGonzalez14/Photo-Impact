<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../db.php";

$sql = "SELECT * FROM quotes";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Quotes</title>

    <link rel="stylesheet" href="../style/header.css">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/footer.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #50546f;
            text-align: center;
        }

        .quotes-box {
            background: white;
            width: 90%;
            margin: 50px auto;
            padding: 30px;
            border-radius: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
        }

        th {
            background-color: #1f1f3d;
            color: white;
        }
    </style>
</head>

<body>

<div id="header-container"></div>

<div class="quotes-box">

    <h1>Public Quotes</h1>

    <table>
        <tr>
            <th>Event</th>
            <th>Pictures</th>
            <th>Price</th>
            <th>Date</th>
            <th>Location</th>
            <th>Special Requests</th>
        </tr>

        <?php
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <tr>
                <td><?php echo $row["type_of_event"]; ?></td>
                <td><?php echo $row["no_of_pics"]; ?></td>
                <td>$<?php echo $row["price"]; ?></td>
                <td><?php echo $row["event_date"]; ?></td>
                <td><?php echo $row["event_location"]; ?></td>
                <td><?php echo $row["special_requests"]; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>

</div>

<div id="footer-container"></div>

<script src="../js/loadHeader.js"></script>
<script src="../js/loadFooter.js"></script>

</body>
</html>