<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../Page/login.php");
    exit();
}

$responseErr = $_SESSION["responseErr"] ?? "";
unset($_SESSION["responseErr"]);

$errorReviewId =
    $_SESSION["responseReviewId"] ?? null;

unset($_SESSION["responseReviewId"]);

include "../includes/db.php";

if (isset($_GET["delete"])) {
    $id = $_GET["delete"];

    $sql = "DELETE FROM reviews WHERE review_id = $id";
    mysqli_query($conn, $sql);

    header("Location: manage-reviews.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["review_id"];
    $admin_response = $_POST["admin_response"];

    if (trim($admin_response) === "") {

        $_SESSION["responseErr"] =
            "Provide a message please.";

        $_SESSION["responseReviewId"] = $id;

        header("Location: manage-reviews.php");
        exit();
    }

    $sql = "UPDATE reviews 
            SET admin_response = '$admin_response'
            WHERE review_id = $id";

    mysqli_query($conn, $sql);

    header("Location: manage-reviews.php");
    exit();
}

$sql = "SELECT * FROM reviews ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Reviews</title>

    <link rel="stylesheet" href="../style/style.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            text-align: center;
            padding-top: 50px;
        }

        .reviews-box {
            background: white;
            width: 95%;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px gray;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #1f1f3d;
            color: white;
        }

        textarea {
            width: 90%;
            height: 80px;
        }

        .submit-btn {
            background-color: #1f1f3d;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 10px;
        }

        .delete-btn {
            color: red;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="reviews-box">

    <h1>Manage Reviews</h1>

    <?php
    if (mysqli_num_rows($result) == 0) {
        echo "<p>No reviews yet.</p>";
    } else {
    ?>

    <table>
        <tr>
            <th>Review ID</th>
            <th>User ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Rating</th>
            <th>Review</th>
            <th>Admin Response</th>
            <th>Delete</th>
        </tr>

        <?php
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <tr>
                <td><?php echo $row["review_id"]; ?></td>
                <td><?php echo $row["user_id"]; ?></td>
                <td><?php echo $row["name"]; ?></td>
                <td><?php echo $row["email"]; ?></td>
                <td><?php echo $row["rating"]; ?></td>
                <td><?php echo $row["review"]; ?></td>

                <td>
                    <form method="POST">
                        <input type="hidden" name="review_id" value="<?php echo $row['review_id']; ?>">

                        <textarea name="admin_response"><?php echo $row["admin_response"]; ?></textarea>

                        <br>
                        <?php
                        if (
                            !empty($responseErr)
                            && $errorReviewId == $row["review_id"]
                        ) {
                            echo "
                                <p style='color:red;'>
                                    $responseErr
                                </p>
                            ";
                        }
                        ?>

                        <input class="submit-btn" type="submit" value="Respond">
                    </form>
                </td>

                <td>
                    <a class="delete-btn"
                       href="manage-reviews.php?delete=<?php echo $row['review_id']; ?>">
                        Delete
                    </a>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>

    <?php
    }
    ?>

    <br>
    <a href="manage-users.php">Back to Dashboard</a>

</div>

</body>
</html>