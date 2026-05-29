<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: make-a-quote.php");
    exit();
}

// FORM DATA
$eventType = $_POST["type_of_event"];
$pictureType = $_POST["type_of_pictures"];
$numPics = $_POST["no_of_pics"];
$eventDate = $_POST["event_date"];
$location = $_POST["event_location"];
$specialRequests = $_POST["special_requests"] ?? "";

if ($eventType === "quince") {
    $eventType = "Quinceaños";
} elseif ($eventType === "wedding") {
    $eventType = "Wedding";
} elseif ($eventType === "photoshoot") {
    $eventType = "Photoshoot";
} elseif ($eventType === "corporate") {
    $eventType = "Corporate Event";
}

$price = 0;
$extraPics = "";

// PHYSICAL ALBUM
if ($pictureType === "physical") {

    if ($numPics == "50") {
        $price = 500;
    } elseif ($numPics == "70") {
        $price = 750;
    } elseif ($numPics == "100") {
        $price = 900;
    } elseif ($numPics == "100_plus") {

        $extraPics = (int) ($_POST["extra_pics"] ?? 0);

        if ($extraPics > 100) {
            $extraAmount = $extraPics - 100;
            $price = 900 + ($extraAmount * 10);
        } else {
            die("Invalid number of pictures.");
        }
    }
}

// DIGITAL PICTURES
else {

    if ($numPics == "2") {
        $price = 300;
    } elseif ($numPics == "4") {
        $price = 600;
    } elseif ($numPics == "6") {
        $price = 750;
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

    <title>Photo Impact - Quote Result</title>

    <link rel="stylesheet" href="../style/header.css">
    <link rel="stylesheet" href="../style/quote.css">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/footer.css">
</head>

<body class="quote-page">

    <div id="header-container"></div>

    <div id="mainContainer">

        <div id="containerQoute">

            <div id="containerResult">

                <h2 style="margin: 5px;">
                    Your Quote
                </h2>

                <hr>

                <ul>

                    <li>
                        <strong>Type of event:</strong>
                        <?php echo htmlspecialchars($eventType); ?>
                    </li>

                    <li>
                        <strong>Photo format:</strong>

                        <?php
                        if ($pictureType === "physical") {
                            echo "Physical Album";
                        } else {
                            echo "Digital Pictures";
                        }
                        ?>
                    </li>

                    <li>
                        <?php
                        if ($pictureType === "physical") {
                            echo "<strong>Number of pictures:</strong> ";

                            if ($numPics === "100_plus") {
                                echo htmlspecialchars($extraPics);
                            } else {
                                echo htmlspecialchars($numPics);
                            }
                        } else {
                            echo "<strong>Hours to be covered:</strong> ";
                            echo htmlspecialchars($numPics);
                        }
                        ?>
                    </li>

                    <li>
                        <strong>Event date:</strong>
                        <?php echo htmlspecialchars($eventDate); ?>
                    </li>

                    <li>
                        <strong>Event location:</strong>
                        <?php echo htmlspecialchars($location); ?>
                    </li>

                    <li>
                        <strong>Special requests:</strong>
                        <?php echo htmlspecialchars($specialRequests); ?>
                    </li>

                </ul>

                <p id="p-quote-price">
                    The coverage of the event is:

                    <strong>
                        $<?php echo number_format($price, 2); ?>
                    </strong>
                </p>

                <p>
                    If you have any questions,

                    <a href="../Page/contactUs.html">
                        Contact us
                    </a>
                </p>

                <div class="buttons-section">

                    <a href="make-a-quote.php">
                        <button class="submit-button" type="button">
                            Generate a new quote
                        </button>
                    </a>

                    <form action="save_quote.php" method="POST">

                        <input
                            type="hidden"
                            name="type_of_event"
                            value="<?php echo htmlspecialchars($eventType); ?>"
                        >

                        <input
                            type="hidden"
                            name="type_of_pictures"
                            value="<?php echo htmlspecialchars($pictureType); ?>"
                        >

                        <input
                            type="hidden"
                            name="no_of_pics"
                            value="<?php echo htmlspecialchars($numPics); ?>"
                        >

                        <input
                            type="hidden"
                            name="price"
                            value="<?php echo htmlspecialchars($price); ?>"
                        >

                        <input
                            type="hidden"
                            name="extra_pics"
                            value="<?php echo htmlspecialchars($extraPics); ?>"
                        >

                        <input
                            type="hidden"
                            name="event_date"
                            value="<?php echo htmlspecialchars($eventDate); ?>"
                        >

                        <input
                            type="hidden"
                            name="event_location"
                            value="<?php echo htmlspecialchars($location); ?>"
                        >

                        <input
                            type="hidden"
                            name="special_requests"
                            value="<?php echo htmlspecialchars($specialRequests); ?>"
                        >

                        <button
                            type="submit"
                            class="submit-button"
                        >
                            Let's do it
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <div id="footer-container"></div>

    <script src="../js/loadHeader.js"></script>
    <script src="../js/loadFooter.js"></script>

</body>

</html>