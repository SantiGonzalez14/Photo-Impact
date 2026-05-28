<?php
session_start();

session_unset();
session_destroy();
?>

<script>
localStorage.removeItem("loggedInUser");
window.location.href = "/Photo-Impact/Page/login.php";
</script>