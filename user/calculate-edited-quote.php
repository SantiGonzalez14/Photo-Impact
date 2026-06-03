<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: my-quotes.php");
    exit();
}

$pictureType = $_POST["type_of_pictures"];
$numPics = $_POST["no_of_pics"];

$price = 0;

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

        }
    }

} else {

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

$_SESSION["edited_quote"] = $_POST; //Store the entire form data in the session to be used in edit-quote.php
$_SESSION["new_quote_price"] = $price;

header(
    "Location: edit-quote.php?quote_id=" .
    $_POST["quote_id"]
);
exit();