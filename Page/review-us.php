<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include "../includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $rating = $_POST["rating"];
    $review = $_POST["review"];

    if (isset($_SESSION["user_id"])) {
        $user_id = $_SESSION["user_id"];
    } else {
        $user_id = NULL;
    }

    if ($name != "" && $email != "" && $rating != "" && $review != "") {

        $sql = "INSERT INTO reviews
                (
                    user_id,
                    name,
                    email,
                    rating,
                    review
                )
                VALUES
                (
                    " . ($user_id === NULL ? "NULL" : "'$user_id'") . ",
                    '$name',
                    '$email',
                    '$rating',
                    '$review'
                )";

        if (mysqli_query($conn, $sql)) {
            header("Location: review-us.php?success=1");
            exit();
        } else {
            header("Location: review-us.php?error=1");
            exit();
        }

    } else {
        header("Location: review-us.php?empty=1");
        exit();
    }
}

$sql = "SELECT * FROM reviews ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Review Us - Photo Impact</title>

    <link rel="stylesheet" href="../style/header.css">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/footer.css">

    <style>
        body {
            background-color: #50546f;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .review-banner {
            width: 80%;
            max-width: 950px;
            margin: 45px auto 25px auto;
            text-align: center;
            color: white;
        }

        .review-banner h1 {
            font-size: 48px;
            margin-bottom: 10px;
            text-shadow: 2px 2px 5px rgba(0,0,0,0.35);
        }

        .review-banner p {
            font-size: 18px;
            line-height: 1.6;
            margin: auto;
            max-width: 750px;
        }

        .review-container {
            width: 80%;
            max-width: 950px;
            margin: auto;
        }

        .review-form {
            background-color: white;
            padding: 40px;
            border-radius: 18px;
            margin-bottom: 40px;
            box-shadow: 0 0 15px rgba(0,0,0,0.25);
        }

        .review-form h2 {
            text-align: center;
            color: #1f1f3d;
            margin-bottom: 10px;
        }

        .review-form p {
            text-align: center;
            color: #444;
            margin-bottom: 30px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 13px;
            margin-top: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 9px;
            font-size: 16px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        .submit-button {
            background-color: #1f1f3d;
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 9px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }

        .submit-button:hover {
            background-color: #31315f;
        }

        .message-box {
            width: 80%;
            max-width: 950px;
            margin: 25px auto;
            background: white;
            padding: 16px;
            border-radius: 10px;
            text-align: center;
            font-weight: bold;
            color: #1f1f3d;
            box-shadow: 0 0 10px rgba(0,0,0,0.25);
        }

        .review-list {
            background-color: white;
            padding: 40px;
            border-radius: 18px;
            margin-bottom: 50px;
            box-shadow: 0 0 15px rgba(0,0,0,0.25);
        }

        .review-list h2 {
            text-align: center;
            color: #1f1f3d;
            margin-bottom: 30px;
        }

        .review-card {
            border-bottom: 1px solid #ddd;
            padding: 22px 0;
        }

        .review-card:last-child {
            border-bottom: none;
        }

        .review-name {
            font-size: 22px;
            font-weight: bold;
            color: #1f1f3d;
        }

        .review-stars {
            color: goldenrod;
            font-size: 26px;
            margin: 10px 0;
        }

        .review-text {
            color: #444;
            line-height: 1.7;
            font-size: 16px;
        }

        .admin-response {
            margin-top: 15px;
            padding: 15px;
            background: #f5f5f5;
            border-left: 4px solid #1f1f3d;
            border-radius: 8px;
            color: #333;
        }
    </style>
</head>

<body>

<div id="header-container"></div>

<div class="review-banner">

    <h1>Share Your Experience</h1>

    <p>
        Tell us how Photo Impact captured your special moments.
        Your feedback helps us continue creating unforgettable memories.
    </p>

</div>

<?php
if (isset($_GET["success"])) {
?>
    <div class="message-box">
        Thank you! Your review was submitted successfully.
    </div>
<?php
}
?>

<?php
if (isset($_GET["empty"])) {
?>
    <div class="message-box">
        Please fill out all fields.
    </div>
<?php
}
?>

<?php
if (isset($_GET["error"])) {
?>
    <div class="message-box">
        Something went wrong. Please try again.
    </div>
<?php
}
?>

<div class="review-container">

    <div class="review-form">

        <h2>Leave a Review</h2>

        <p>We would love to hear about your experience with our photography services.</p>

        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

            <input
                type="text"
                name="name"
                placeholder="Your Name"
            >

            <input
                type="email"
                name="email"
                placeholder="Your Email"
            >

            <select name="rating">
                <option value="">Choose Your Rating</option>
                <option value="5">★★★★★ Excellent</option>
                <option value="4">★★★★ Very Good</option>
                <option value="3">★★★ Good</option>
                <option value="2">★★ Fair</option>
                <option value="1">★ Poor</option>
            </select>

            <textarea
                name="review"
                rows="6"
                placeholder="Tell us about your experience with Photo Impact..."
            ></textarea>

            <button
                type="submit"
                class="submit-button"
            >
                Submit Review
            </button>

        </form>

    </div>

    <div class="review-list">

        <h2>Customer Reviews</h2>

        <?php
        if (mysqli_num_rows($result) == 0) {

            echo "<p style='text-align:center;'>No reviews yet.</p>";

        } else {

            while ($row = mysqli_fetch_assoc($result)) {
        ?>

            <div class="review-card">

                <div class="review-name">
                    <?php echo $row["name"]; ?>
                </div>

                <div class="review-stars">
                    <?php
                    for ($i = 0; $i < $row["rating"]; $i++) {
                        echo "★";
                    }
                    ?>
                </div>

                <div class="review-text">
                    <?php echo $row["review"]; ?>
                </div>

                <?php
                if (!empty($row["admin_response"])) {
                ?>
                    <div class="admin-response">
                        <strong>Photo Impact Response:</strong>
                        <br><br>
                        <?php echo $row["admin_response"]; ?>
                    </div>
                <?php
                }
                ?>

            </div>

        <?php
            }
        }
        ?>

    </div>

</div>

<div id="footer-container"></div>

<script src="../js/loadHeader.js"></script>
<script src="../js/loadFooter.js"></script>

</body>

</html>