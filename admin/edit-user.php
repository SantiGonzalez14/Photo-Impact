<?php

require_once '../includes/db.php';
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../Page/login.php");
    exit();
}
$user_id = $fname = $lname = $email = $phone_number = "";
$user_idErr = $fnameErr = $lnameErr = $emailErr = $phone_numberErr = "";

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(!isset($_GET['user_id'])) {
        die("User ID is required.");
    }
    $user_id = (int)trim($_GET['user_id']);

    $stmt = $conn->prepare(
        "SELECT user_id, fname, lname, email, phone_number
         FROM users
         WHERE user_id = ?"
    );

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if(!$user){
        header("Location: ./manage_users.php");
        exit();
    }

    $user_id = $user['user_id'];
    $fname = $user['fname'];
    $lname = $user['lname'];
    $email = $user['email'];
    $phone_number = $user['phone_number'];
   
} else {
    $user_id = $_POST["user_id"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $phone_number = $_POST["phone_number"];
        
    // Name validation
    if (empty($fname)) { $fnameErr = "* First name is required";}
    else if (!preg_match("/^[a-zA-Z-' ]*$/",$fname)) {
        $fnameErr = "* Only letters and white space allowed";
    }
    
    // Last name validation
    if (empty($lname)) { $lnameErr = "* Last name is required";}
        else if (!preg_match("/^[a-zA-Z-' ]*$/",$lname)) {
            $lnameErr = "* Only letters and white space allowed";
        }

    // Email validation
    if (empty($email)) {
        $emailErr = "* Email is required";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "* Invalid email format";
    }

    //Phone number validation
    if (empty($phone_number)) {
        $phone_numberErr = "* Phone number is required";
    } else if (!preg_match("/^[0-9]{10}$/", $phone_number)) {
        $phone_numberErr = "* Invalid phone number format. Must be 10 digits.";
    }

    if(
        empty($fnameErr) &&
        empty($lnameErr) &&
        empty($emailErr) &&
        empty($phone_numberErr)
    ){
        $sql = "UPDATE users
                SET fname = ?, lname = ?, email = ?, phone_number = ?
                WHERE user_id = ?";
    
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("ssssi", $fname, $lname, $email, $phone_number, $user_id);
        $result = $stmt->execute();
        if(!$result) {
            die("Error updating user: " . $stmt->error);
        }
        header("Location: ./manage-users.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>

    <link rel="stylesheet" href="../style/header.css">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/edit-users.css">
    <link rel="stylesheet" href="../style/footer.css">
</head>

<body class="edit-users-page">

    <!-- HEADER -->
    <div id="header-container"></div>

    <section id="edit-users-title">
        <h2>Edit user</h2>
    </section>

    <section id="content">

        <p><span class="error">* required field</span></p>

        <div id="form-container">
            <form method="post">

                <label for="user_id">User ID:</label>
                <input type="text" name="user_id" readonly value="<?php echo $user_id; ?>">
                <br><br>
                
                <label for="fname">First name:</label>
                <input type="text" name="fname" value="<?php echo $fname; ?>">
                <span class="error"><?php echo $fnameErr; ?></span>
                <br><br>

                <label for="lname">Last name:</label>
                <input type="text" name="lname" value="<?php echo $lname; ?>">
                <span class="error"><?php echo $lnameErr; ?></span>
                <br><br>

                <label for="email">Email:</label>
                <input type="text" name="email" value="<?php echo $email; ?>">
                <span class="error"><?php echo $emailErr; ?></span>
                <br><br>

                <label for="phone_number">Phone number:</label>
                <input type="text" name="phone_number" value="<?php echo $phone_number; ?>">
                <span class="error"><?php echo $phone_numberErr; ?></span>
                <br><br>

                <input type="submit" name="submit" class="submit-button" value="Submit">

            </form>
        </div>
    </section>

    <div id="footer-container"></div>

    <script src="../js/loadHeader.js"></script>
    <script src="../js/loadFooter.js"></script>

</body>
</html>