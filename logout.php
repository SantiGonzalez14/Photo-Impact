<?php
session_start();

session_unset();
session_destroy();

header("Location: Page/login.php");
exit();
?>