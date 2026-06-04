<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$admin_email = "admin@photoimpact.com";
session_start();
require_once '../includes/db.php';

// User protection
if (!isset($_SESSION["user_id"])) {
    header("Location: ../Page/login.php");
    exit();
}

$userId = $_SESSION["user_id"];

//Display any error message related to rescheduling
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

    <title>Photo Impact - My Bookings</title>

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
        <h1>My Bookings</h1>
    </section>

    <!-- CONTENT -->
    <section id="content">

        <div id="container">
            <table class="w3-table-all w3-card-4 w3-responsive">

                <thead>

                    <tr>

                        <th>Booking ID</th>
                        <th>Quote ID</th>
                        <th>Booking date</th>
                        <th>Event Date</th>
                        <th>Event</th>
                        <th>Delivery</th>
                        <th>Pictures / Hours</th>
                        <th>Price</th>
                        <th>Status</th>
                        
                        <th class="w3-center">Actions</th>

                    </tr>

                </thead>

                <tbody>

                <?php

                $stmt = $conn->prepare(
                    "SELECT
                        b.booking_id,
                        b.quote_id,
                        b.booking_date,
                        b.event_date,
                        b.booking_status,
                        q.type_of_event,
                        q.delivery_type,
                        q.number_of_pictures,
                        q.quote_value,
                        EXISTS(
                            SELECT 1
                            FROM booking_reschedule_requests r
                            WHERE r.booking_id = b.booking_id
                            AND r.request_status = 'pending'
                        ) AS has_pending_request
                    FROM bookings b
                    JOIN quotes q ON b.quote_id = q.quote_id
                    WHERE q.user_id = ?"
                );

                $stmt->bind_param("i", $userId);
                $stmt->execute();

                $result = $stmt->get_result();

                if (mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {

                        $isScheduled = ($row["booking_status"] === "scheduled");
                        $actionsDisabled = !$isScheduled ? 'disabled' : '';


                        $isPendingApproval = $row["has_pending_request"];

                        $rescheduleText =
                            $isPendingApproval
                                ? "Pending Reschedule Request"
                                : "Reschedule";

                        $rescheduleStyle =
                            $isPendingApproval
                                ? "style='pointer-events:none;opacity:0.5;'"
                                : "";

                        echo "

                        <tr>

                            <td>$row[booking_id]</td>
                            <td>$row[quote_id]</td>
                            <td>$row[booking_date]</td>
                            <td>$row[event_date]</td>
                            <td>$row[type_of_event]</td>
                            <td>$row[delivery_type]</td>
                            <td>$row[number_of_pictures]</td>
                            <td>$$row[quote_value]</td>
                            <td>$row[booking_status]</td>

                            <td>

                                <div class='actions-column'>";

                        if ($isScheduled) {

                            echo "
                                    <a
                                        class='button green'
                                        href='#'
                                        onclick='showRescheduleForm(
                                            $row[booking_id],
                                            \"$row[event_date]\"
                                        ); return false;'
                                        $rescheduleStyle
                                    >
                                        $rescheduleText
                                    </a>

                                    <a
                                        class='button red'
                                        href='cancel-booking.php?booking_id=$row[booking_id]'
                                    >
                                        Cancel booking
                                    </a>

                            ";

                        } else {

                            echo "
                                    <a
                                        style='text-decoration: none;'
                                        href='mailto: $admin_email?subject=Question about Booking ID $row[booking_id]'
                                    >
                                        Contact Admin
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

                        <td colspan='9' class='w3-center'>
                            You have no bookings yet.
                        </td>

                    </tr>

                    ";
                }

                ?>

                </tbody>

            </table>

            <?php
                $formData = $_SESSION["reschedule_form"] ?? [];
                unset($_SESSION["reschedule_form"]);
            ?>

            <!-- Reschedule form (hidden by default) -->
            <div
                id="reschedule-section"
                class="w3-card-4"
                style="
                    display:none;
                    margin-top:20px;
                    padding:20px;
                    width:80%;"
                >

                <h3>Request Reschedule</h3>

                <form
                    action="request-reschedule.php"
                    method="POST"
                >

                    <input
                        type="hidden"
                        name="booking_id"
                        id="booking-id"
                        value="<?php echo htmlspecialchars($formData["booking_id"] ?? ""); ?>"
                    >
                    <input
                        type="hidden"
                        name="current_date_input"
                        id="current-date-input"
                    >
                    <p>
                        <strong>Current Event Date:</strong>
                        <span id="current-date" name="current_date"></span>
                    </p>

                    <label for="requested-date">
                        New Event Date
                    </label>

                    <input
                        type="date"
                        id="requested-date"
                        name="requested_event_date"
                        min="<?php echo date('Y-m-d'); ?>"
                        value="<?php echo htmlspecialchars(
                            $formData["requested_event_date"] ?? ""
                        ); ?>"
                        required
                    >

                    <br><br>

                    <label for="reason">
                        Reason for Rescheduling
                    </label>

                    <textarea
                        id="reason"
                        name="reason"
                        rows="4"
                        style="width:100%;"
                    ><?php
                    echo htmlspecialchars(
                        $formData["reason"] ?? ""
                    );
                    ?></textarea>

                    <br><br>

                    <?php if (!empty($errorMessage)): ?>

                    <div class="error-box">
                        <?php echo htmlspecialchars($errorMessage); ?>
                    </div>

                    <?php endif; ?>

                    <button
                        type="submit"
                        class="button green"
                    >
                        Submit Request
                    </button>

                    <button
                        type="button"
                        class="button red"
                        onclick="hideRescheduleForm()"
                    >
                        Cancel
                    </button>

                </form>

            </div>

        </div>

    </section>

    <!-- FOOTER -->
    <div id="footer-container"></div>

    <!-- SCRIPTS -->
    <script src="../js/loadHeader.js"></script>
    <script src="../js/loadFooter.js"></script>

    <script>
        function showRescheduleForm(bookingId, currentDate) {

            document.getElementById(
                "reschedule-section").style.display = "block";

            document.getElementById("booking-id").value = bookingId;

            document.getElementById("current-date").textContent = currentDate;
            document.getElementById("current-date-input").value = currentDate;

            document.getElementById("reschedule-section").scrollIntoView({
                behavior: "smooth"
            });
        }

        function hideRescheduleForm() {

            document.getElementById("reschedule-section").style.display = "none";
        }

        </script>

        <script>
            const failedBookingId =
                <?php echo json_encode($formData["booking_id"]); ?>;

            if (failedBookingId) {

                document.addEventListener(
                    "DOMContentLoaded",
                    function () {

                        document.getElementById(
                            "reschedule-section"
                        ).style.display = "block";

                        document.getElementById(
                            "booking-id"
                        ).value = failedBookingId;

                        document.getElementById(
                            "reschedule-section"
                        ).scrollIntoView({
                            behavior: "smooth"
                        });

                    }
                );
            }

        </script>
</body>

</html>