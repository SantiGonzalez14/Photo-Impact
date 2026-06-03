<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../includes/db.php';

// User protection
if (!isset($_SESSION["user_id"])) {
    header("Location: ../Page/login.php");
    exit();
}

$userId = $_SESSION["user_id"];
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Photo Impact - My Quotes</title>

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

        .button.disabled {
            opacity: 0.5;
            pointer-events: none;
            cursor: not-allowed;
        }

    </style>

    <link rel="stylesheet" href="../style/footer.css">

</head>

<body class="manage-users-page">

    <!-- HEADER -->
    <div id="header-container"></div>

    <!-- TITLE -->
    <section id="manage-users-title">
        <h1>My Quotes</h1>
    </section>

    <!-- CONTENT -->
    <section id="content">

        <div id="container">

            <table class="w3-table-all w3-card-4 w3-responsive">

                <thead>

                    <tr>

                        <th>Quote ID</th>
                        <th>Event</th>
                        <th>Delivery</th>
                        <th>Pictures / Hours</th>
                        <th>Price</th>
                        <th>Event Date</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="w3-center">Actions</th>

                    </tr>

                </thead>

                <tbody>

                <?php

                $stmt = $conn->prepare(
                    "SELECT
                        q.quote_id,
                        q.type_of_event,
                        q.delivery_type,
                        q.number_of_pictures,
                        q.quote_value,
                        q.event_date,
                        q.quote_status,
                        DATE(q.created_at) AS created_date,
                        b.booking_id
                    FROM quotes q
                    LEFT JOIN bookings b
                        ON q.quote_id = b.quote_id
                    WHERE q.user_id = ?
                      AND q.is_archived = 0
                    ORDER BY q.created_at DESC"
                );

                $stmt->bind_param("i", $userId);
                $stmt->execute();

                $result = $stmt->get_result();

                if (mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {

                        $hasBooking = !empty($row["booking_id"]);

                        echo "

                        <tr>

                            <td>$row[quote_id]</td>

                            <td>$row[type_of_event]</td>

                            <td>$row[delivery_type]</td>

                            <td>$row[number_of_pictures]</td>

                            <td>$$row[quote_value]</td>

                            <td>$row[event_date]</td>

                            <td>$row[quote_status]</td>

                            <td>$row[created_date]</td>

                            <td>

                                <div class='actions-column'>";

                        if (!$hasBooking) {

                            echo "

                                    <a
                                        class='button green'
                                        href='edit-quote.php?quote_id=$row[quote_id]'
                                    >
                                        Edit
                                    </a>

                            ";

                        } else {

                            echo "

                                    <span
                                        class='button disabled'
                                        title='This quote is already associated with a booking.'
                                    >
                                        Contact Admin
                                    </span>

                            ";
                        }

                        echo "

                                </div>

                            </td>

                        </tr>

                        ";
                    }

                } else {

                    echo "

                    <tr>

                        <td colspan='9' class='w3-center'>
                            You have no quotes yet.
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

</body>

</html>