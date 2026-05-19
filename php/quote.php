<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ./Page/quote.html");
    exit();
}


// FORM DATA
$eventType = $_POST["type-of-event"];
$pictureType = $_POST["type_of_pictures"];
$numPics = $_POST["no-of-pics"];


$price = 0;

// PHYSICAL ALBUM
if ($pictureType === "physical") {

    if ($numPics == "50") {
        $price = 500;
    }

    elseif ($numPics == "70") {
        $price = 750;
    }

    elseif ($numPics == "100") {
        $price = 900;
    }

    elseif ($numPics == "100_plus") {

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
    }

    elseif ($numPics == "4") {
        $price = 600;
    }

    elseif ($numPics == "6") {
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

    <!-- STYLES -->
    <link rel="stylesheet" href="../style/header.css">
    <link rel="stylesheet" href="../style/quote.css">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/footer.css">

</head>

<body class="quote-page">

    <!-- HEADER -->
    <div id="header-container"></div>

    <!-- MAIN CONTENT -->
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
                        <?= htmlspecialchars($eventType) ?>
                    </li>
    
                    <li>
                        <strong>Photo format:</strong>
    
                        <?= $pictureType === "physical"
                            ? "Physical Album"
                            : "Digital Pictures"
                        ?>
                    </li>
    
                    <li>
    
                        <?php if ($pictureType === "physical"): ?>
    
                            <strong>Number of pictures:</strong>
    
                            <?= $numPics === "100_plus"
                                ? htmlspecialchars($extraPics)
                                : htmlspecialchars($numPics)
                            ?>
    
                        <?php else: ?>
    
                            <strong>Hours to be covered:</strong>
    
                            <?= htmlspecialchars($numPics) ?>
    
                        <?php endif; ?>
    
                    </li>
    
                </ul>
    
                <p id="p-quote-price">
    
                    The coverage of the event is:
    
                    <strong>
                        $<?= number_format($price, 2) ?>
                    </strong>
    
                </p>
    
                <p>
    
                    If you have any questions,
    
                    <a href="/MAC272/Project/Page/contactUs.html">
                        Contact us
                    </a>
    
                </p>
    
                <div class="buttons-section">

                    <a href="/MAC272/Project/Page/quote.html">

                        <button class="submit-button" type="button">
                            Generate a new quote
                        </button>

                    </a>

                    <form action="/save_quote.php" method="POST">

                        <input
                            type="hidden"
                            name="event_type"
                            value="<?= htmlspecialchars($eventType) ?>"
                        >

                        <input
                            type="hidden"
                            name="picture_type"
                            value="<?= htmlspecialchars($pictureType) ?>"
                        >

                        <input
                            type="hidden"
                            name="num_pics"
                            value="<?= htmlspecialchars($numPics) ?>"
                        >

                        <input
                            type="hidden"
                            name="price"
                            value="<?= htmlspecialchars($price) ?>"
                        >

                        <input
                            type="hidden"
                            name="extra_pics"
                            value="<?= htmlspecialchars($extraPics ?? '') ?>"
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

    <!-- FOOTER -->
    <div id="footer-container"></div>

    <!-- SCRIPTS -->
    <script src="../js/loadHeader.js"></script>
    <script src="../js/loadFooter.js"></script>

    <script>

        function saveQuote() {

            alert(
                "Your quote has been saved, you will be contacted soon to confirm date and location of event."
            );
        }

    </script>

</body>

</html>