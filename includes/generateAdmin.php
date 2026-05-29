<?php
//echo password_hash("admin123", PASSWORD_DEFAULT);
require_once "../includes/db.php";

$sql = "
        INSERT INTO users (
            fname,
            lname,
            email,
            phone_number,
            password_hash,
            role
        )
        VALUES (
            'Admin',
            'User',
            'admin@photoimpact.com',
            '3055551234',
            '\$2y\$10\$KTVwLmb5y8/0p619NPDua.vzKHAdZNQid673EUGFCQA/S0p4kyioy',
            'admin'
        );";
$conn->query($sql);
if ($conn->error) {
    echo "Error inserting admin user: " . $conn->error;
} else {
    echo "Admin user inserted successfully.";
}

?>