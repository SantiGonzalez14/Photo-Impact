<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Photo Impact - Manage Bookings</title>

    <!-- STYLES -->
    <link rel="stylesheet" href="../style/header.css">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/manage-users.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <style>

        table.w3-table,
        table.w3-table-all {
            width: auto;
        }

    </style>

    <link rel="stylesheet" href="../style/footer.css">

</head>

<body class="manage-users-page">

    <!-- HEADER -->
    <div id="header-container"></div>

    <!-- TITLE -->
    <section id="manage-users-title">
        <h1>Manage Bookings</h1>
    </section>

    <!-- CONTENT -->
    <section id="content">

        <div id="container">

            <table class="w3-table-all w3-card-4 w3-responsive">

                <thead>

                    <tr>

                        <th>Booking ID</th>
                        <th>Quote ID</th>
                        <th>Client name</th>
                        <th>Booking date</th>
                        <th>Event date</th>
                        <th>Booking_status</th>
                        <th class="w3-center">Actions</th>

                    </tr>

                </thead>

                <tbody>

                <?php

                // FETCH BOOKINGS WITH CLIENT NAMES
                $sql = "
                    SELECT
                        b.booking_id,
                        b.quote_id,
                        CONCAT(u.fname, ' ', u.lname) AS client_name,
                        b.booking_date,
                        b.event_date,
                        b.booking_status
                    FROM bookings b
                    JOIN quotes q ON b.quote_id = q.quote_id
                    JOIN users u ON q.user_id = u.user_id
                    ORDER BY
                        CASE b.booking_status
                            WHEN 'scheduled' THEN 1
                            WHEN 'completed' THEN 2
                            WHEN 'cancelled' THEN 3
                        END,
                        b.event_date ASC;
                ";

                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {
                        $completeDisabled =
                            ($row["booking_status"] !== "scheduled")
                                ? "disabled"
                                : "";

                        $cancelDisabled =
                            ($row["booking_status"] !== "scheduled")
                                ? "disabled"
                                : "";

                        echo "

                        <tr>

                            <td>$row[booking_id]</td>
                            <td>$row[quote_id]</td>
                            <td>$row[client_name]</td>
                            <td>$row[booking_date]</td>
                            <td>$row[event_date]</td>
                            <td>$row[booking_status]</td>

                            <td>

                                <div class='actions-column'>

                                    <a
                                        class='button green $completeDisabled'
                                        href='complete-booking.php?booking_id=$row[booking_id]' //TODO: disable if already completed/cancelled and sorted by status
                                    >
                                        Completed
                                    </a>

                                    <a
                                        class='button red $cancelDisabled'
                                        href='cancel-booking.php?booking_id=$row[booking_id]'
                                    >
                                        Cancel
                                    </a>

                                    <a
                                        class='button'
                                        href='view-booking.php?booking_id=$row[booking_id]'
                                    >
                                        View
                                    </a>

                                </div>

                            </td>

                        </tr>

                        ";
                    }

                } else {

                    echo "

                    <tr>

                        <td colspan='11' class='w3-center'>
                            No bookings found.
                        </td>

                    </tr>

                    ";
                }

                ?>

                </tbody>

            </table>

        </div>

    </section>

    <!-- FOOTER -->
    <div id="footer-container"></div>

    <!-- SCRIPTS -->
    <script src="../js/loadHeader.js"></script>
    <script src="../js/loadFooter.js"></script>
    <script>
        // Confirmation before completing a booking
        document.querySelectorAll('.button.green').forEach(button => {
            button.addEventListener('click', function(event) {
                if (!confirm('Does the event associated with this booking actually took place? This action cannot be undone.')) {
                    event.preventDefault();
                }
            });
        });

        // Confirmation before cancelling a booking
        document.querySelectorAll('.button.red').forEach(button => {
            button.addEventListener('click', function(event) {
                if (!confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) {
                    event.preventDefault();
                }
            });
        });
    </script>

</body>

</html>