<?php
session_start();
include('database.php');

// Satıcı kontrolü
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siparis_id = $_POST['siparis_id'];
    $siparis_durumu = $_POST['siparis_durumu'];

    // Sipariş durumu güncelleme
    $query = "UPDATE Siparis SET Siparis_Durumu = ? WHERE Siparis_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $siparis_durumu, $siparis_id);

    if ($stmt->execute()) {
        header("Location: order_manage.php?status=success");
    } else {
        header("Location: order_manage.php?status=error");
    }
}
?>
