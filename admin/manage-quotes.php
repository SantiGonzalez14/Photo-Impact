<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../Page/login.php");
    exit();
}

include "../includes/db.php";

/* DELETE MESSAGE */
if (isset($_GET["delete"])) {

    $id = $_GET["delete"];

    $sql = "DELETE FROM contact WHERE contact_id = $id";

    mysqli_query($conn, $sql);

    header("Location: ./manage-quotes.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Quotes</title>

    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/header.css">
    <link rel="stylesheet" href="../style/footer.css">
    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            text-align: center;
            padding-top: 50px;
        }

        .quotes-box {
            background: white;
            width: 90%;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px gray;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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

        a {
            text-decoration: none;
            font-weight: bold;
        }

        .delete-btn {
            color: red;
        }

    </style>

</head>

<body>

<div id="header-container"></div>
<div class="quotes-box">
    <h1>Manage Quotes</h1>

    <table>

        <tr>
            <th>Contact ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>User ID</th>
            <th>Delete</th>
        </tr>

        <?php
        while ($row = mysqli_fetch_assoc($result)) {
        ?>

        <tr>

            <td><?php echo $row["contact_id"]; ?></td>

            <td><?php echo $row["name"]; ?></td>

            <td><?php echo $row["email"]; ?></td>

            <td><?php echo $row["message"]; ?></td>

            <td><?php echo $row["user_id"]; ?></td>

            <td>
                <a class="delete-btn"
                   href="manage-quotes.php?delete=<?php echo $row['contact_id']; ?>">
                   Delete
                </a>
            </td>

        </tr>

        <?php
        }
        ?>

    </table>

    <br>

    <a href="manage-users.php">Back to Dashboard</a>

</div>

<div id="footer-container"></div>

<script src="../js/loadHeader.js"></script>
<script src="../js/loadFooter.js"></script>
</body>
</html>