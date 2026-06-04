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

function displayQuotesTableWithStatus($conn, $status) {

    try{
        $stmt = $conn->prepare(
            "SELECT
                quote_id,
                user_id,
                type_of_event,
                delivery_type,
                number_of_pictures,
                quote_value,
                event_date,
                event_location,
                quote_status,
                DATE(created_at) AS created_date
             FROM quotes
             WHERE is_archived = 0
             AND quote_status = ?
             ORDER BY event_date ASC"
        );
    
        $stmt->bind_param("s", $status);
        $stmt->execute();
    
        $result = $stmt->get_result();
    } catch (Exception $e) {
        die("Error fetching quotes: " . $e->getMessage());
    }

    echo '
    <table class="w3-table-all w3-card-4 w3-responsive">

        <thead>

            <tr>
                <th>Quote ID</th>
                <th>User ID</th>
                <th>Event</th>
                <th>Delivery</th>
                <th>Pictures / Hours</th>
                <th>Price</th>
                <th>Event Date</th>
                <th>Location</th>
                <th>Status</th>
                <th>Created</th>
                <th class="w3-center">Actions</th>
            </tr>

        </thead>

        <tbody>
    ';

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {

            echo "

            <tr>

                <td>{$row['quote_id']}</td>
                <td>{$row['user_id']}</td>
                <td>{$row['type_of_event']}</td>
                <td>{$row['delivery_type']}</td>
                <td>{$row['number_of_pictures']}</td>
                <td>$" . number_format($row['quote_value'], 2) . "</td>
                <td>{$row['event_date']}</td>
                <td>{$row['event_location']}</td>
                <td>{$row['quote_status']}</td>
                <td>{$row['created_date']}</td>

                <td>

                    <div class='actions-column'>";

            if ($status === "pending") {

                echo "

                        <a
                            class='button green'
                            href='approve-quote.php?quote_id={$row['quote_id']}'
                        >
                            Approve
                        </a>

                        <a
                            class='button red'
                            href='reject-quote.php?quote_id={$row['quote_id']}'
                        >
                            Reject
                        </a>
                ";
            }

            echo "

                        <a
                            class='button'
                            href='view-quote.php?quote_id={$row['quote_id']}'
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
                No {$status} quotes found.
            </td>
        </tr>
        ";
    }

    echo '
        </tbody>
    </table>
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

    <title>Photo Impact - Manage Quotes</title>

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
        <h1>Manage Quotes</h1>
    </section>

    <!-- CONTENT -->
    <section id="content">

        <div id="container">
            <div class="filter-links">

                <p>Filter by status:</p>

                <a href="?status=pending">Pending</a>
                <a href="?status=approved">Approved</a>
                <a href="?status=rejected">Rejected</a>

            </div>

            <?php
            $status = $_GET['status'] ?? 'pending';
            displayQuotesTableWithStatus($conn, $status);
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
                if (!confirm('Do you want to approve this quote?')) {
                    event.preventDefault();
                }
            });
        });

        document.querySelectorAll('.button.red').forEach(button => {
            button.addEventListener('click', function(event) {
                if (!confirm('Are you sure you want to reject this quote?')) {
                    event.preventDefault();
                }
            });
        });
    </script>

</body>

</html>