<?php
//rolü satıcı ise logine yönlendir
session_start();
if ($_SESSION['role'] !== 'seller') {
    header("Location: index.php");
    exit;
}
?>
