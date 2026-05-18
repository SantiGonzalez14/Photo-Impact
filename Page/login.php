<?php
session_start();
require_once '../db.php';

$emailErr = $passwordErr = $loginErr = "";
$email = $password = "";


function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);    // this removes backslashes
    return $data;
}

if (isset($_POST['submit'])) {

    // required field and also email validation for format
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    //this is for password validation
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
    }

    if (empty($emailErr) && empty($passwordErr)) {
        
        $safe_email = mysqli_real_escape_string($conn, $email);

        $sql = "SELECT * FROM users WHERE email = '$safe_email'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);

                if ($password === $user['password'] || password_verify($password, $user['password'])) {
                    
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['email'] = $user['email'];

                    if ($user['email'] === 'admin@photoimpact.com') {
                        $_SESSION['role'] = 'admin';
                        header("Location: ../admin.php");
                        exit();
                    } else {
                        $_SESSION['role'] = 'user';
                        header("Location: index.php");
                        exit();
                    }
                } else {
                    $loginErr = "Invalid email or password combination";
                }
            } else {
                $loginErr = "Invalid email or password combination";
            }
        } else {
            $loginErr = "Database query error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Impact - Login</title>

    <link rel="stylesheet" href="../style/header.css">
    <link rel="stylesheet" href="../style/footer.css">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/signUp.css">
    <style>
        
        .error {
            color: #FF0000;
            font-size: 0.85em;
            display: block;
            margin-top: 3px;
            font-weight: bold;
        }
        .success-box {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            margin-bottom: 15px;
            border: 1px solid #c3e6cb;
            font-weight: bold;
        }
    </style>
</head>

<body class="signUp-page">

    <div id="header-container"></div>

    <div class="signUp-main-container">

        <header class="signUp-header">
            <h2>Log In</h2>
        </header>

        <div class="container">

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

               
                <?php if (isset($_SESSION['reg_success'])): ?>
                    <div class="success-box">
                        <?php 
                            echo htmlspecialchars($_SESSION['reg_success']); 
                            unset($_SESSION['reg_success']); 
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($loginErr)): ?>
                    <span class="error" style="text-align: center; margin-bottom: 12px;"><?php echo htmlspecialchars($loginErr); ?></span>
                <?php endif; ?>

                <label for="email">Email:</label>
    
                <input
                    id="email"
                    name="email"
                    type="text"
                    placeholder="youremail@gmail.com"
                    size="40"
                    value="<?php echo htmlspecialchars($email); ?>"
                >
                <span class="error"><?php echo $emailErr; ?></span>

                <label for="password">Password:</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Enter your password"
                    size="40"
                >
                <span class="error"><?php echo $passwordErr; ?></span>

                <br>
                <br>

                <button
                    type="submit"
                    name="submit"
                    class="submit-button"
                    style="margin: auto; display: block;"
                >
                    Log in
                </button>

                <a href="signUp.html" style="display: block; text-align: center; margin-top: 15px;">or sign up</a>

            </form>

        </div>

    </div>

    <div id="footer-container"></div>

    <script src="../js/loadHeader.js"></script>
    <script src="../js/loadFooter.js"></script>

</body>
</html>