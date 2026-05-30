<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Photo Impact</title>

    <link rel="stylesheet" href="../style/header.css">
    <link rel="stylesheet" href="../style/quote.css">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/footer.css">
    <style>
        .error {
            color: red;
            font-size: 14px;
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>

<body class="quote-page">

    <div id="header-container"></div>

    <div id="main-container">

        <div id="container-quote">
            <form id="quote" action="generate-a-quote.php" method="POST">
                
                <h2 style="margin: 5px;">
                    Event details
                </h2>
                <span>
                    <?php
                    if (isset($_SESSION["user_not_logged_in_message"])) {
                        echo "<p class='error'>" . $_SESSION["user_not_logged_in_message"] . "</p>";
                        unset($_SESSION["user_not_logged_in_message"]);
                    }
                    ?>
                </span>

                <!-- EVENT TYPE -->
                <label for="type-of-event">
                    Choose the type of event:
                </label>

                <select name="type_of_event" id="type-of-event">

                    <option value="quince">
                        Quinceaños
                    </option>

                    <option value="wedding">
                        Wedding
                    </option>

                    <option value="photoshoot">
                        Photoshoot
                    </option>

                    <option value="corporate">
                        Corporate Event
                    </option>

                </select>

                <br>

                <!-- PHOTO TYPE -->
                <label for="physical-pictures">
                    I want a physical album
                </label>

                <input
                    type="radio"
                    id="physical-pictures"
                    name="type_of_pictures"
                    value="physical"
                    checked
                >

                <label for="digital-pictures">
                    I want digital pictures
                </label>

                <input
                    type="radio"
                    id="digital-pictures"
                    name="type_of_pictures"
                    value="digital"
                >

                <br>

                <!-- NUMBER OF PICTURES -->
                <label for="no-of-pics" id="lbl-no-of-pics">
                    Number of pictures
                </label>

                <select name="no_of_pics" id="no-of-pics">

                    <option value="50">50</option>
                    <option value="70">70</option>
                    <option value="100">100</option>
                    <option value="100_plus">+100</option>

                </select>

                <!-- EXTRA PICTURES -->
                <div
                    id="extra-pics-section"
                    style="display: none; margin-top: 10px;"
                >

                    <label for="extra-pics">
                        Enter total number of pictures:
                    </label>

                    <input
                        type="number"
                        id="extra-pics"
                        name="extra_pics"
                        min="101"
                        placeholder="e.g. 120"
                    >

                    <p style="font-size: 12px;">
                        It's $10 per extra picture after 100.
                    </p>

                </div>

                <br>

                <!-- DATE OF EVENT-->
                <label for="event-date">
                    Date of the event:
                </label>

                <input
                    type="date"
                    id="event-date"
                    name="event_date"
                    min="<?php echo date("Y-m-d"); ?>"
                    required
                >
                <br>

                <!-- LOCATION OF EVENT-->
                <label for="event-location">
                    Location of the event:
                </label>
                <input
                    type="text"
                    id="event-location"
                    name="event_location"
                    placeholder="e.g. New York City"
                    required
                >
                <span id="event-location-error" class="error"></span>
                <br>

                 <!-- SPECIAL REQUESTS-->

                <label for="special-requests">
                    Any special requests?
                </label>

                <textarea
                    id="special-requests"
                    name="special_requests"
                    placeholder="Please specify any special requests you have..."
                    rows="10"
                ></textarea>


                <!-- SUBMIT BUTTON -->
                <button
                    type="submit"
                    class="submit-button"
                >
                    Make a quote
                </button>

            </form>

        </div>

    </div>

    <div id="footer-container"></div>

    <!-- SCRIPTS -->
    <script src="../js/script.js"></script>
    <script src="../js/loadHeader.js"></script>
    <script src="../js/loadFooter.js"></script>
    <script>
        const locationInput = document.getElementById("event-location");
        const locationError = document.getElementById("event-location-error");

        locationInput.addEventListener("input", function(event) {
            if (locationInput.value.trim() === "") {
                locationError.textContent = "Event location is required.";
            } else {
                locationError.textContent = "";
            }
        });

        const form = document.getElementById("quote");
        form.addEventListener("submit", function(event) {
            if (locationInput.value.trim() === "") {
                event.preventDefault();
            }
        });

    </script>
</body>

</html>