<?php
//rolü adminse logine yönlendir
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>
