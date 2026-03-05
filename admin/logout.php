<?php
// admin/logout.php
session_start();
session_destroy(); // Destroys all session data
header("Location: login.php");
exit;
?>