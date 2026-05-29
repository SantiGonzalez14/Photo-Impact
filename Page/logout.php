<?php
session_start();

session_unset();
session_destroy();
?>

<script>
localStorage.removeItem("loggedInUser");
window.location.href = "../Page/login.php";
</script>