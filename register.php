<?php
include "db.php";

$nameErr = $lastNameErr = $emailErr = $telephoneErr = $passwordErr = "";
$name = $lastName = $email = $telephone = $password = "";

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
    }

    if ($nameErr == "" && $lastNameErr == "" && $emailErr == "" && $telephoneErr == "" && $passwordErr == "") {

        $check = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $check);

        if (mysqli_num_rows($result) > 0) {
            echo "This email is already registered.";
            echo "<br><a href='Page/signUp.html'>Go Back</a>";
        } else {
            $sql = "INSERT INTO users (name, lastName, email, telephone, password)
                    VALUES ('$name', '$lastName', '$email', '$telephone', '$password')";

            if (mysqli_query($conn, $sql)) {
                echo "User registered successfully.";
                echo "<br><a href='Page/login.html'>Go to Login</a>";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }

    } else {
        echo $nameErr . "<br>";
        echo $lastNameErr . "<br>";
        echo $emailErr . "<br>";
        echo $telephoneErr . "<br>";
        echo $passwordErr . "<br>";
        echo "<br><a href='Page/signUp.php'>Go Back</a>";
    }
} else {

    header("Location: Page/signUp.php");
    exit();
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>