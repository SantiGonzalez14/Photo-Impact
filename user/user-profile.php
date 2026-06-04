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

$passwordErr = $passwordChangeSuccessful = "";
$successMessage = "";

/*
|--------------------------------------------------------------------------
| DELETE ACCOUNT
|--------------------------------------------------------------------------
*/

if (isset($_POST["delete_account"])) {

    try {
        $checkBookings = $conn->prepare(
            "SELECT b.booking_id
                FROM bookings b
                JOIN quotes q
                ON q.quote_id = b.quote_id
                JOIN users u
                ON u.user_id = q.user_id
                WHERE u.user_id = ?
                AND b.booking_status = 'scheduled';
            "
        );
        $checkBookings->bind_param("i", $user_id);
        $checkBookings->execute();
        $result = $checkBookings->get_result();

        if($result->num_rows > 0){
            echo "
            <script>
                alert('You have active bookings. Please contact an administrator to close your account.');
                window.location.href = '../user/user-profile.php';
            </script>";
            
            $checkBookings->close();
            exit();
        }

        $stmt = $conn->prepare(
            "UPDATE users
                SET is_hidden = 1
                WHERE user_id = ?;"
        );
        
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
        echo "
            <script>
                alert('Your account has been successfully deleted.');
            </script>";
        session_destroy();
        
        header("Location: ../Page/login.php");
        exit();
    } catch (Exception $e){
        die("Error while deleting account: " . $e->getMessage());
    }

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

    try{
        $stmt = $conn->prepare(
            "SELECT password_hash
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
                $user["password_hash"]
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
                 SET password_hash = ?
                 WHERE user_id = ?"
            );
    
            $stmt->bind_param(
                "si",
                $hashedPassword,
                $user_id
            );
    
            $stmt->execute();

    
            $passwordChangeSuccessful =
                "Password updated successfully.";
        }
    } catch (Exception $e){
        die("Error changing the password: " . $e->getMessage());
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
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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

            <?php if($passwordChangeSuccessful): ?>
            <p class="success">
                <?= $passwordChangeSuccessful ?>
            </p>
            <?php endif; ?>

            <form method="POST" class="input-group">

                <label>Current Password</label>
                <div class="password-container">
                    <div class="icons">
                        <i class="fa-regular fa-eye toggle-password"></i>
                    </div>
                    <input
                        type="password"
                        placeholder="Enter current password"
                        name="current_password"
                        class="pass"
                        required>
                    </div>

                <label>New Password</label>

                <div class="password-container">
                    <div class="icons">
                        <i class="fa-regular fa-eye toggle-password"></i>
                    </div>

                    <input
                        type="password"
                        placeholder="Enter new password"
                        name="new_password"
                        class="pass"
                        required>
                </div>

                <label>Confirm Password</label>
                <div class="password-container">
                    <div class="icons">
                        <i class="fa-regular fa-eye toggle-password"></i>
                    </div>

                    <input
                        type="password"
                        placeholder="Re-enter new password"
                        name="confirm_password"
                        class="pass"
                        required>
                </div>

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

    <script>
        document.querySelectorAll(".toggle-password").forEach(icon => {

            icon.addEventListener("click", () => {

                const passwordInput =
                    icon.parentElement.parentElement.querySelector(".pass");

                if (passwordInput.type === "password") {

                    passwordInput.type = "text";
                    icon.classList.replace("fa-eye", "fa-eye-slash");

                } else {

                    passwordInput.type = "password";
                    icon.classList.replace("fa-eye-slash", "fa-eye");
                }
            });

        });
    </script>
</body>
</html>