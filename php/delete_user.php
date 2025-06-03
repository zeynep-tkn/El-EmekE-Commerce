<?php
session_start();
include('../database.php');

// Eğer admin giriş yapmamışsa, login sayfasına yönlendir
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Kullanıcıyı silmek için ID'yi al
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Kullanıcıyı silme işlemi
    $delete_query = "DELETE FROM users WHERE id='$user_id'";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: admin_dashboard.php"); // Silme işlemi başarılıysa admin paneline yönlendir
        exit();
    } else {
        echo "Bir hata oluştu. Lütfen tekrar deneyin.";
    }
} else {
    echo "Geçersiz işlem.";
}
?>
