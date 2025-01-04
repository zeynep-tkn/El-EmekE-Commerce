<?php
//sepete ürün ekleme sayfası
session_start();
include('../database.php');

// Kullanıcının oturum açıp açmadığını kontrol et
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$musteri_id = $_SESSION['user_id'];
$urun_id = $_POST['urun_id'];
$urun_adi = $_POST['urun_adi'];
$urun_fiyati = $_POST['urun_fiyati'];
$urun_gorseli = $_POST['urun_gorseli'];

// Sepette ürün var mı kontrol et
$query = "SELECT * FROM Sepet WHERE Musteri_ID = '$musteri_id' AND Urun_ID = '$urun_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    // Ürün zaten sepette, miktarı artır
    $query = "UPDATE Sepet SET Miktar = Miktar + 1 WHERE Musteri_ID = '$musteri_id' AND Urun_ID = '$urun_id'";
} else {
    // Ürün sepette yok, yeni ekle
    $query = "INSERT INTO Sepet (Musteri_ID, Urun_ID, Miktar) VALUES ('$musteri_id', '$urun_id', 1)";
}

if (mysqli_query($conn, $query)) {
    header("Location: my_cart.php");
    exit();
} else {
    echo "Sepete eklenirken bir hata oluştu: " . mysqli_error($conn);
}
?>