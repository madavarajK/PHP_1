<?php
// logout.php
session_start();
session_unset();
session_destroy();
header("Location: ../public/admin_login.php");
exit;
?>
