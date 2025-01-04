<?php
//sepetimdeki ürünü güncelleme silme veya sayısını arttırma
session_start();
include('../database.php');

// Kullanıcının oturum açıp açmadığını kontrol et
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$musteri_id = $_SESSION['user_id'];
$urun_id = $_POST['urun_id'];
$action = $_POST['action'];

if ($action == 'add') {
    $query = "UPDATE Sepet SET Miktar = Miktar + 1 WHERE Musteri_ID = '$musteri_id' AND Urun_ID = '$urun_id'";
} elseif ($action == 'remove') {
    $query = "DELETE FROM Sepet WHERE Musteri_ID = '$musteri_id' AND Urun_ID = '$urun_id'";
}

if (mysqli_query($conn, $query)) {
    header("Location: my_cart.php");
    exit();
} else {
    echo "Sepet güncellenirken bir hata oluştu: " . mysqli_error($conn);
}
?>