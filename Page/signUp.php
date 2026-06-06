<?php
require_once "../includes/db.php";
$nameErr = $lastNameErr = $emailErr = $telephoneErr = $passwordErr = "";
$name = $lastName = $email = $telephone = $password = "";
$successMsg = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST["submit"])) {

    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
    }

    if (empty($_POST["lastName"])) {
        $lastNameErr = "Last name is required";
    } else {
        $lastName = test_input($_POST["lastName"]);
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    if (empty($_POST["telephone"])) {
        $telephoneErr = "Telephone is required";
    } else {
        $telephone = test_input($_POST["telephone"]);
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);

        if (strlen($password) < 12 || strlen($password) > 20) {
            $passwordErr = "Password must be between 12 and 20 characters";
        }
    }

    if ($nameErr == "" && $lastNameErr == "" && $emailErr == "" && $telephoneErr == "" && $passwordErr == "") {

        $check = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $check);

        if (mysqli_num_rows($result) > 0) {
            $emailErr = "This email is already registered.";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users 
                    (fname, lname, email, phone_number, password_hash)
                    VALUES 
                    ('$name', '$lastName', '$email', '$telephone', '$password_hash')";

            if (mysqli_query($conn, $sql)) {
                $successMsg = "User registered successfully.";
                $name = $lastName = $email = $telephone = $password = "";
            } else {
                $successMsg = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Impact</title>
    
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../style/header.css">
    <link rel="stylesheet" href="../style/footer.css">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/signUp.css">

    <style>
        .error {
            color: red;
            font-size: 14px;
            display: block;
            margin-bottom: 10px;
        }

        .success {
            color: green;
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>

<body class="signUp-page">

    <div id="header-container"></div>

    <div class="signUp-main-container">

        <header class="signUp-header">
            <h2>Sign Up</h2>
        </header>

        <div class="container">

            <?php
            if ($successMsg != "") {
                echo "<p class='success'>$successMsg</p>";
            }
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

                <label for="name">Name:</label>
                
                <input
                    id="name"
                    name="name"
                    type="text"
                    placeholder="Enter your name"
                    size="40"
                    maxlength="50"
                    value="<?php echo $name; ?>"
                >
                <span class="error"><?php echo $nameErr; ?></span>

                <label for="lastName">Last name:</label>

                <input
                    id="lastName"
                    name="lastName"
                    type="text"
                    placeholder="Enter your last name"
                    size="40"
                    maxlength="50"
                    value="<?php echo $lastName; ?>"
                >
                <span class="error"><?php echo $lastNameErr; ?></span>

                <label for="email">Email:</label>

                <input
                    id="email"
                    name="email"
                    type="email"
                    placeholder="youremail@gmail.com"
                    size="40"
                    value="<?php echo $email; ?>"
                >
                <span class="error"><?php echo $emailErr; ?></span>

                <label for="telephone">Telephone:</label>

                <input
                    id="telephone"
                    name="telephone"
                    type="tel"
                    placeholder="000-000-0000"
                    size="40"
                    value="<?php echo $telephone; ?>"
                >
                <span class="error"><?php echo $telephoneErr; ?></span>

                <label for="password">Password:</label>

                <div class="password-container">
                    <div class="icons">
                        <i class="fa-regular fa-eye toggle-password"></i>
                    </div>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="pass"
                        placeholder="Enter your password"
                        size="40"
                    >
                </div>
                <span class="error"><?php echo $passwordErr; ?></span>

                <br>
                <br>

                <button
                    type="submit"
                    name="submit"
                    class="submit-button"
                    style="margin: auto; display: block;"
                >
                    Sign up
                </button>

                <a href="../Page/login.php">or log in</a>

            </form>

        </div>

    </div>

    <div id="footer-container"></div>

    <script src="../js/loadHeader.js"></script>
    <script src="../js/loadFooter.js"></script>

    <script>
        document.querySelectorAll(".toggle-password").forEach(icon => {

            icon.addEventListener("click", () => {

                const passwordInput =
                    icon.parentElement.parentElement.querySelector(".pass");
                console.log(passwordInput);

                if (passwordInput.type === "password") {
                    console.log("type password");
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