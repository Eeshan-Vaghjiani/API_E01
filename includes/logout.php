<?php
// logout.php
session_start();
session_unset();
session_destroy();

// Redirect to the homepage or login page after logout
header("Location: ../index.php");
exit();
?>
