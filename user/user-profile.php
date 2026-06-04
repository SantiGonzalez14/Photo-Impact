<?php

require_once '../includes/db.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../Page/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$fname = "";
$lname = "";
$email = "";
$phone_number = "";

$fnameErr = "";
$lnameErr = "";
$emailErr = "";
$phoneErr = "";

$passwordErr = "";
$successMessage = "";

/*
|--------------------------------------------------------------------------
| DELETE ACCOUNT
|--------------------------------------------------------------------------
*/

if (isset($_POST["delete_account"])) {

    $stmt = $conn->prepare(
        "UPDATE users
         SET is_hidden = 1
         WHERE user_id = ?"
    );

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    session_destroy();

    header("Location: ../Page/login.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| GET USER DATA
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $stmt = $conn->prepare(
        "SELECT
            fname,
            lname,
            email,
            phone_number
         FROM users
         WHERE user_id = ?"
    );

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        die("User not found.");
    }

    $fname = $user["fname"];
    $lname = $user["lname"];
    $email = $user["email"];
    $phone_number = $user["phone_number"];
}

/*
|--------------------------------------------------------------------------
| UPDATE PROFILE
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["update_profile"])
) {

    $fname = trim($_POST["fname"]);
    $lname = trim($_POST["lname"]);
    $email = trim($_POST["email"]);
    $phone_number = trim($_POST["phone_number"]);

    if (empty($fname)) {
        $fnameErr = "* First name is required";
    }

    if (empty($lname)) {
        $lnameErr = "* Last name is required";
    }

    if (
        !empty($email) &&
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        $emailErr = "* Invalid email";
    }

    if (
        !empty($phone_number) &&
        !preg_match("/^[0-9]{10}$/", $phone_number)
    ) {
        $phoneErr = "* Phone number must be 10 digits";
    }

    if (
        empty($fnameErr) &&
        empty($lnameErr) &&
        empty($emailErr) &&
        empty($phoneErr)
    ) {

        $stmt = $conn->prepare(
            "UPDATE users
             SET
                fname = ?,
                lname = ?,
                email = ?,
                phone_number = ?
             WHERE user_id = ?"
        );

        $stmt->bind_param(
            "ssssi",
            $fname,
            $lname,
            $email,
            $phone_number,
            $user_id
        );

        $stmt->execute();

        $successMessage =
            "Profile updated successfully.";
    }
}

/*
|--------------------------------------------------------------------------
| CHANGE PASSWORD
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["change_password"])
) {

    $currentPassword =
        $_POST["current_password"];

    $newPassword =
        $_POST["new_password"];

    $confirmPassword =
        $_POST["confirm_password"];

    $stmt = $conn->prepare(
        "SELECT password
         FROM users
         WHERE user_id = ?"
    );

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (
        !password_verify(
            $currentPassword,
            $user["password"]
        )
    ) {

        $passwordErr =
            "Current password is incorrect.";

    } elseif (
        $newPassword !== $confirmPassword
    ) {

        $passwordErr =
            "Passwords do not match.";

    } else {

        $hashedPassword =
            password_hash(
                $newPassword,
                PASSWORD_DEFAULT
            );

        $stmt = $conn->prepare(
            "UPDATE users
             SET password = ?
             WHERE user_id = ?"
        );

        $stmt->bind_param(
            "si",
            $hashedPassword,
            $user_id
        );

        $stmt->execute();

        $successMessage =
            "Password updated successfully.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="../style/header.css">
    <link rel="stylesheet" href="../style/footer.css">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/user-profile.css">


</head>
<body class="user-profile-page">

    <div id="header-container"></div>

    <section id="profile-title">
        <h1>Profile settings</h1>
    </section>

    <section id="content">

        <div class="form-container">
            <div class="form-header">
                <h2>My Profile</h2>
            </div>

            <?php if($successMessage): ?>
            <p style="color:green; margin: 0px 10px;">
                <?= $successMessage ?>
            </p>
            <?php endif; ?>

            <form method="POST" class="input-group">

                <label>First Name</label>
                <input type="text"
                    name="fname"
                    value="<?= htmlspecialchars($fname) ?>">

                <span class="error">
                    <?= $fnameErr ?>
                </span>

                <label>Last Name</label>
                <input type="text"
                    name="lname"
                    value="<?= htmlspecialchars($lname) ?>">

                <span class="error">
                    <?= $lnameErr ?>
                </span>

                <label>Email</label>
                <input type="email"
                    name="email"
                    value="<?= htmlspecialchars($email) ?>">

                <span class="error">
                    <?= $emailErr ?>
                </span>

                <label>Phone Number</label>
                <input type="text"
                    name="phone_number"
                    value="<?= htmlspecialchars($phone_number) ?>">

                <span class="error">
                    <?= $phoneErr ?>
                </span>

                <button
                    type="submit"
                    name="update_profile"
                    class="submit-button">
                    Save Changes
                </button>

            </form>
        </div>

        <div class="form-container">
            <div class="form-header">
                <h2>Change Password</h2>
            </div>

            <form method="POST" class="input-group">

                <label>Current Password</label>
                <input
                    type="password"
                    name="current_password"
                    required>

                <label>New Password</label>
                <input
                    type="password"
                    name="new_password"
                    required>

                <label>Confirm Password</label>
                <input
                    type="password"
                    name="confirm_password"
                    required>

                <span class="error">
                    <?= $passwordErr ?>
                </span>

                <br>

                <button
                    type="submit"
                    name="change_password"
                    class="submit-button">
                    Change Password
                </button>

            </form>

        </div>

        <div class="form-container">
            <div class="form-header">
                <h2>Delete Account</h2>
            </div>

            <form
                method="POST"
                onsubmit="
                    return confirm(
                        'Are you sure you want to delete your account?');"
                class="input-group">

                <button
                    type="submit"
                    name="delete_account"
                    class="submit-button button red">
                    Delete Account
                </button>

            </form>

        </div>
    </section>
    
    <div id="footer-container"></div>

    <script src="../js/loadHeader.js"></script>
    <script src="../js/loadFooter.js"></script>
</body>
</html>