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

$errorMessage = $_SESSION["reschedule_error"] ?? "";
unset($_SESSION["reschedule_error"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Photo Impact - Manage Reschedule Resquests</title>

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

        .error-box {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #c62828;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

    </style>

    <link rel="stylesheet" href="../style/footer.css">

</head>

<body class="manage-users-page">

    <!-- HEADER -->
    <div id="header-container"></div>

    <!-- TITLE -->
    <section id="manage-users-title">
        <h1>Manage Reschedule Requests</h1>
    </section>

    <!-- CONTENT -->
    <section id="content">

        <div id="container">

            <?php if (!empty($errorMessage)): ?>

                    <div class="error-box">
                        <?php echo htmlspecialchars($errorMessage); ?>
                    </div>

            <?php endif; ?>

            <table class="w3-table-all w3-card-4 w3-responsive">

                <thead>

                    <tr>

                        <th>Request</th>
                        <th>Booking ID</th>
                        <th>Client</th>
                        <th>Current date</th>
                        <th>Requested date</th>
                        <th>Request status</th>
                        <th>Reason</th>
                        <th class="w3-center">Actions</th>

                    </tr>

                </thead>

                <tbody>

                <?php

                // FETCH RESCHEDULE REQUESTS
                $sql = "
                    SELECT
                        r.request_id,
                        r.booking_id,
                        r.current_event_date,
                        r.requested_event_date,
                        r.reschedule_reason,
                        r.request_status,

                        u.fname,
                        u.lname

                    FROM booking_reschedule_requests r

                    JOIN bookings b
                        ON r.booking_id = b.booking_id

                    JOIN quotes q
                        ON b.quote_id = q.quote_id

                    JOIN users u
                        ON q.user_id = u.user_id

                    WHERE r.request_status = 'pending'

                    ORDER BY r.created_at ASC
                ";

                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {

                        // Disable approve/reject buttons if quote is not pending
                        $approveDisabled =
                            ($row["request_status"] !== "pending")
                                ? "disabled"
                                : "";

                        $cancelDisabled =
                            ($row["request_status"] !== "pending")
                                ? "disabled"
                                : "";
                        echo "

                        <tr>

                            <td>$row[request_id]</td>
                            <td>$row[booking_id]</td>
                            <td>$row[fname] $row[lname]</td>
                            <td>$row[current_event_date]</td>
                            <td>$row[requested_event_date]</td>
                            <td>$row[request_status]</td>
                            <td>$row[reschedule_reason]</td>


                            <td>

                                <div class='actions-column'>

                                    <a
                                        class='button green $approveDisabled'
                                        href='approve-reschedule.php?request_id=$row[request_id]&requested_event_date=$row[requested_event_date]'
                                    >
                                        Approve
                                    </a>

                                    <a
                                        class='button red $cancelDisabled'
                                        href='reject-reschedule.php?request_id=$row[request_id]'
                                    >
                                        Reject
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
                            No reschedule requests found.
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
        // Confirmation before approving a quote
        document.querySelectorAll('.button.green').forEach(button => {
            button.addEventListener('click', function(event) {
                if (!confirm('Do you want to approve this reschedule?')) {
                    event.preventDefault();
                }
            });
        });

        document.querySelectorAll('.button.red').forEach(button => {
            button.addEventListener('click', function(event) {
                if (!confirm('Are you sure you want to reject this reschedule?')) {
                    event.preventDefault();
                }
            });
        });
    </script>

</body>

</html>