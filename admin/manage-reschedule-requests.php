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

function displayRescheduleRequestsWithStatus($conn, $status)
{
    $stmt = $conn->prepare(
        "SELECT
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

        WHERE r.request_status = ?

        ORDER BY r.created_at ASC"
    );

    $stmt->bind_param("s", $status);
    $stmt->execute();

    $result = $stmt->get_result();

    echo '
    <div class="table-container">
    <table class="w3-table-all w3-card-4 w3-responsive">

        <thead>

            <tr>

                <th>Request</th>
                <th>Booking ID</th>
                <th>Client</th>
                <th>Current Date</th>
                <th>Requested Date</th>
                <th>Status</th>
                <th>Reason</th>
                <th class="w3-center">Actions</th>

            </tr>

        </thead>

        <tbody>
    ';

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {

            echo "

            <tr>

                <td>{$row['request_id']}</td>

                <td>{$row['booking_id']}</td>

                <td>
                    {$row['fname']}
                    {$row['lname']}
                </td>

                <td>{$row['current_event_date']}</td>

                <td>{$row['requested_event_date']}</td>

                <td>{$row['request_status']}</td>

                <td>{$row['reschedule_reason']}</td>

                <td>

                    <div class='actions-column'>
            ";

            if ($status === "pending") {

                echo "

                    <a
                        class='button green'
                        href='approve-reschedule.php
                            ?request_id={$row['request_id']}
                            &requested_event_date={$row['requested_event_date']}'
                    >
                        Approve
                    </a>

                    <a
                        class='button red'
                        href='reject-reschedule.php
                            ?request_id={$row['request_id']}'
                    >
                        Reject
                    </a>
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

            <td colspan='8' class='w3-center'>
                No {$status} reschedule requests found.
            </td>

        </tr>

        ";
    }

    echo '

        </tbody>

    </table>
    </div>
    ';
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

            <div class="filter-links">

                <p>Filter by status:</p>

                <a href="?status=pending">Pending</a>
                <a href="?status=approved">Approved</a>
                <a href="?status=rejected">Rejected</a>
                <a href="?status=cancelled">Cancelled</a>

            </div>

            <?php
            $status = $_GET['status'] ?? 'pending';
            if (!empty($errorMessage)) {
                echo "<div class='error-box'>" . htmlspecialchars($errorMessage) . "</div>";
            }
            displayRescheduleRequestsWithStatus($conn, $status);
            ?>

            
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