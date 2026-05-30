<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../Page/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Impact - Manage Users</title>

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
    </style>
    <link rel="stylesheet" href="../style/footer.css">

</head>

<body class="manage-users-page">

    <!-- HEADER -->
    <div id="header-container"></div>

    <section id="manage-users-title">
        <h1>Manage Users</h1>
    </section>

    <!-- CONTENT -->
    <section id="content">
        <div id="container">

            <table class ="w3-table-all w3-card-4 w3-responsive">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Email</th>
                        <th>Phone number</th>
                        <th>Role</th>
                        <th>Since</th>
                        <th class="w3-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                //Fetch users from the database
                $sql = "SELECT user_id, fname, lname, email, phone_number, role, DATE(created_at) as 
                        date FROM photo_impact.users WHERE is_hidden = 0 ORDER BY created_at DESC";
                $result = mysqli_query($conn, $sql);

                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_assoc($result)){
                        //Output data
                        echo "
                            <tr>
                                <td>$row[user_id]</td>
                                <td>$row[fname]</td>
                                <td>$row[lname]</td>
                                <td>$row[email]</td>
                                <td>$row[phone_number]</td>
                                <td>$row[role]</td>
                                <td>$row[date]</td>
                                <td>
                                    <div class='actions-column'>
                                        <a class='button green' href='edit-user.php?user_id=$row[user_id]'>Edit</a>";
                                        
                                        $isAdminRow = ($row['role'] === "admin");
                                        if (!$isAdminRow) {
                                            echo "<br><a class='button red' href='delete-user.php?user_id=$row[user_id]'>Delete</a></td>";
                                        }
                                echo"</div>
                                </td>
                            </tr>
                        </tr>";
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- FOOTER -->
    <div id="footer-container"></div>
    <script src="../js/loadHeader.js"></script>
    <script src="../js/loadFooter.js"></script>
    <script>
        // Confirmation before deleting a user
        document.querySelectorAll('.button.red').forEach(button => {
            button.addEventListener('click', function(event) {
                if (!confirm('Are you sure you want to delete this user?')) {
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>