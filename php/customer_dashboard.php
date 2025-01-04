<?php
//rolü müşteri değilse logine yönlendir
session_start();
if ($_SESSION['role'] !== 'customer') {
    header("Location: register.php");
    exit;
}
?>
