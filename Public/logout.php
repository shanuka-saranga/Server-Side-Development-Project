<?php
// Public/logout.php
session_start();

// Destroy all session data
session_destroy();

// Redirect to ROOT index.php
header("Location: ../Public/index.php");
exit;
?>