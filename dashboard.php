<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

echo "Welcome, " . $_SESSION['user_name'] . "! <a href='logout.php'>Logout</a>";
?>
