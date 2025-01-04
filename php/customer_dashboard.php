<?php
//rolü müşteriyse logine yönlendir
session_start();
if ($_SESSION['role'] !== 'customer') {
    header("Location: register.php");
    exit;
}
?>
