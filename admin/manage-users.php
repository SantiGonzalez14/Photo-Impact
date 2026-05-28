<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../Page/login.php");
    exit();
}

include "../db.php";

$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="../style/style.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            text-align: center;
            padding-top: 80px;
        }

        .admin-box {
            background: white;
            width: 950px;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px gray;
        }

        a {
            display: block;
            margin: 15px;
            font-size: 20px;
            text-decoration: none;
            color: #1f1f3d;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            background: white;
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
    </style>
</head>

<body>

<div class="admin-box">

    <h1>Admin Dashboard</h1>

    <p>Welcome, <?php echo $_SESSION["fname"]; ?>.</p>

    <a href="manage-users.php">Manage Users</a>
    <a href="manage-quotes.php">Manage Quotes</a>
    <a href="manage-bookings.php">Manage Bookings</a>
    <a href="../logout.php">Log Out</a>

    <h2>Registered Users</h2>

    <table>
        <tr>
            <th>User ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Role</th>
        </tr>

        <?php
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <tr>
                <td><?php echo $row["user_id"]; ?></td>
                <td><?php echo $row["fname"]; ?></td>
                <td><?php echo $row["lname"]; ?></td>
                <td><?php echo $row["email"]; ?></td>
                <td><?php echo $row["phone_number"]; ?></td>
                <td><?php echo $row["role"]; ?></td>
            </tr>
        <?php
        }
        ?>

    </table>

</div>

</body>
</html>