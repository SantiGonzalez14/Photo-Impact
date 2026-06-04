<?php

require_once '../includes/db.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../Page/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$quote_id = $type_of_event = $delivery_type = $number_of_pictures = $event_date = $event_location = $special_requests = "";
$quote_value = 0.00;
$eventErr = $dateErr = $locationErr = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    if (!isset($_GET["quote_id"])) {
        die("Quote ID is required.");
    }

    $quote_id = (int) $_GET["quote_id"];

    $stmt = $conn->prepare(
        "SELECT
            q.quote_id,
            q.quote_value,
            q.type_of_event,
            q.delivery_type,
            q.number_of_pictures,
            q.event_date,
            q.event_location,
            q.special_requests,
            b.booking_id
        FROM quotes q
        LEFT JOIN bookings b
            ON q.quote_id = b.quote_id
        WHERE q.quote_id = ?
          AND q.user_id = ?"
    );

    $stmt->bind_param("ii", $quote_id, $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $quote = $result->fetch_assoc();

    if (!$quote) {
        die("Quote not found.");
    }

    // Cannot edit if booking already exists
    if (!empty($quote["booking_id"])) {
        die("This quote already has a booking. Please contact an administrator.");
    }

    $quote_id = $quote["quote_id"];
    $quote_value = $quote["quote_value"];
    $type_of_event = $quote["type_of_event"];
    $delivery_type = $quote["delivery_type"];
    $number_of_pictures = $quote["number_of_pictures"];
    $event_date = $quote["event_date"];
    $event_location = $quote["event_location"];
    $special_requests = $quote["special_requests"];
    
    // If coming from calculate-edited-quote.php, use the form data from the session to pre-fill the form with the user's edits
    if (isset($_SESSION["edited_quote"])) {

    $edited = $_SESSION["edited_quote"];

    $type_of_event = $edited["type_of_event"];
    $delivery_type = $edited["type_of_pictures"];
    $event_date = $edited["event_date"];
    $event_location = $edited["event_location"];
    $special_requests = $edited["special_requests"];

    if (!empty($edited["extra_pics"])) {
        $number_of_pictures = $edited["extra_pics"];
    } else {
        $number_of_pictures = $edited["no_of_pics"];
    }
    unset($_SESSION["edited_quote"]); // Clear the edited quote data from the session after using it
}

} else {

    $quote_id = (int) $_POST["quote_id"];

    $quote_value = $_SESSION["new_quote_price"] ?? 0; // Get the new price calculated in calculate-edited-quote.php, default to 0 if not set
    $type_of_event = trim($_POST["type_of_event"]);
    $delivery_type = trim($_POST["type_of_pictures"]);
    $number_of_pictures = (int) $_POST["no_of_pics"];
    $event_date = trim($_POST["event_date"]);
    $event_location = trim($_POST["event_location"]);
    $special_requests = trim($_POST["special_requests"]);

    if (empty($type_of_event)) {
        $eventErr = "* Event type is required";
    }

    if (empty($event_date)) {
        $dateErr = "* Event date is required";
    }

    if (empty($event_location)) {
        $locationErr = "* Event location is required";
    }

    if (
        empty($eventErr) &&
        empty($dateErr) &&
        empty($locationErr)
    ) {

        // Verify quote belongs to user and is not booked
        $stmt = $conn->prepare(
            "SELECT q.quote_id
             FROM quotes q
             LEFT JOIN bookings b
                ON q.quote_id = b.quote_id
             WHERE q.quote_id = ?
               AND q.user_id = ?
               AND b.booking_id IS NULL"
        );

        $stmt->bind_param("ii", $quote_id, $user_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            die("This quote cannot be edited.");
        }

        $stmt = $conn->prepare(
            "UPDATE quotes
             SET
                quote_value = ?,
                type_of_event = ?,
                delivery_type = ?,
                number_of_pictures = ?,
                event_date = ?,
                event_location = ?,
                special_requests = ?
             WHERE quote_id = ?"
        );

        $stmt->bind_param(
            "sssisssi",
            $quote_value,
            $type_of_event,
            $delivery_type,
            $number_of_pictures,
            $event_date,
            $event_location,
            $special_requests,
            $quote_id
        );

        $success = $stmt->execute();

        if (!$success) {
            die("Error updating quote: " . $stmt->error);
        }

        unset($_SESSION["edited_quote"]);
        unset($_SESSION["new_quote_price"]);
        header("Location: ./my-quotes.php");
        exit();
    }
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

    <title>Edit Quote</title>

    <link rel="stylesheet" href="../style/header.css">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/edit-users.css">
    <link rel="stylesheet" href="../style/footer.css">

</head>

<body class="edit-users-page">

    <div id="header-container"></div>

    <section id="edit-users-title">
        <h2>Edit Quote</h2>
    </section>

    <section id="content">

        <p><span class="error">* required field</span></p>

        <div id="form-container">

        <p style="font-size:1.2em; font-weight:bold;">
            Current Quote Value: $<?= number_format($quote_value, 2) ?>
        </p>
            <form method="POST">

                <input
                    type="hidden"
                    name="quote_id"
                    value="<?php echo $quote_id; ?>"
                >
                <?php if(isset($_SESSION["new_quote_price"])): ?>

                <div class="quote-preview">

                    <h3>
                        New Quote Value:
                        $<?= number_format(
                            $_SESSION["new_quote_price"],
                            2
                        ); ?>
                    </h3>

                </div>
                
            <?php endif; ?>

                <label>Quote ID:</label>

                <input
                    type="text"
                    readonly
                    value="<?php echo $quote_id; ?>"
                >

                <br><br>

                <label>Event Type:</label>

                <select name="type_of_event">

                    <option value="Wedding"
                        <?php if($type_of_event=="Wedding") echo "selected"; ?>>
                        Wedding
                    </option>

                    <option value="Quinceañera"
                        <?php if($type_of_event=="Quinceañera") echo "selected"; ?>>
                        Quinceañera
                    </option>

                    <option value="Private Event"
                        <?php if($type_of_event=="Private Event") echo "selected"; ?>>
                        Private Event
                    </option>

                    <option value="Photoshoot"
                        <?php if($type_of_event=="Photoshoot") echo "selected"; ?>>
                        Photoshoot
                    </option>

                </select>

                <span class="error"><?php echo $eventErr; ?></span>

                <br><br>

                <label>Delivery Type:</label>

                <label for="physical-pictures">
                Physical Album
                </label>

                <input
                    type="radio"
                    id="physical-pictures"
                    name="type_of_pictures"
                    value="physical"
                    <?php if($delivery_type=="physical") echo "checked"; ?>
                >

                <label for="digital-pictures">
                    Digital Pictures
                </label>

                <input
                    type="radio"
                    id="digital-pictures"
                    name="type_of_pictures"
                    value="digital"
                    <?php if($delivery_type=="digital") echo "checked"; ?>
                >

                <br><br>

                <label id="lbl-no-of-pics">

                    <?= $delivery_type === "physical"
                        ? "Number of pictures"
                        : "Hours to be covered"
                    ?>

                </label>

                <select name="no_of_pics" id="no-of-pics">

                    <option value="50">50</option>
                    <option value="70">70</option>
                    <option value="100">100</option>
                    <option value="100_plus">+100</option>

                </select>
                <!-- Extra pictures -->
                <div
                    id="extra-pics-section"
                    style="display:none;"
                >
                    <input
                        type="number"
                        id="extra-pics"
                        name="extra_pics"
                        min="101"
                        value="<?php echo $number_of_pictures > 100 ? $number_of_pictures : ''; ?>"
                    >
                </div>
                

                <br><br>

                <label>Event Date:</label>

                <input
                    type="date"
                    name="event_date"
                    value="<?php echo $event_date; ?>"
                >

                <span class="error"><?php echo $dateErr; ?></span>

                <br><br>

                <label>Event Location:</label>

                <input
                    type="text"
                    name="event_location"
                    value="<?php echo htmlspecialchars($event_location); ?>"
                >

                <span class="error"><?php echo $locationErr; ?></span>

                <br><br>

                <label>Special Requests:</label>

                <textarea
                    name="special_requests"
                    rows="5"
                ><?php echo htmlspecialchars($special_requests); ?></textarea>

                <br><br>

                <button
                    type="submit"
                    formaction="calculate-edited-quote.php"
                    class="submit-button"
                >
                    Calculate New Price
                </button>

                <input
                    type="submit"
                    class="submit-button"
                    value="Confirm Changes"
                >

            </form>
        </div>

    </section>

    <div id="footer-container"></div>
    <script>
        const currentDeliveryType =
            "<?= $delivery_type ?>";

        const currentPictures =
            "<?= $number_of_pictures ?>";

    </script>

    <script>
        const noOfPicsSelect = document.getElementById("no-of-pics");
        const extraPicsSection = document.getElementById("extra-pics-section");
        const lblNoOfPics = document.getElementById("lbl-no-of-pics");

        const digitalPictures =
            document.getElementById("digital-pictures");

        const physicalPictures =
            document.getElementById("physical-pictures");

        function loadPhysicalOptions(selected = null) {

            noOfPicsSelect.innerHTML = `
                <option value="50">50</option>
                <option value="70">70</option>
                <option value="100">100</option>
                <option value="100_plus">+100</option>
            `;

            if (selected) {

                if (parseInt(selected) > 100) {

                    noOfPicsSelect.value = "100_plus";

                    extraPicsSection.style.display = "block";

                } else {

                    noOfPicsSelect.value = selected;
                }
            }
        }

        function loadDigitalOptions(selected = null) {

            noOfPicsSelect.innerHTML = `
                <option value="2">2 hours</option>
                <option value="4">3-4 hours</option>
                <option value="6">5-6 hours</option>
            `;

            if (selected) {
                noOfPicsSelect.value = selected;
            }

            extraPicsSection.style.display = "none";
        }

        if (currentDeliveryType === "physical") {

            loadPhysicalOptions(currentPictures);

        } else {

            loadDigitalOptions(currentPictures);
        }

        noOfPicsSelect.addEventListener("change", function() {

            extraPicsSection.style.display =
                this.value === "100_plus"
                    ? "block"
                    : "none";
        });

        digitalPictures.addEventListener("change", function() {

            lblNoOfPics.textContent =
                "Hours to be covered";

            loadDigitalOptions();
        });

        physicalPictures.addEventListener("change", function() {

            lblNoOfPics.textContent =
                "Number of pictures";

            loadPhysicalOptions();
        });
        </script>
    <script src="../js/loadHeader.js"></script>
    <script src="../js/loadFooter.js"></script>

</body>

</html>