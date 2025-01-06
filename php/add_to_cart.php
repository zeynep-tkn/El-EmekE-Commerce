<?php
// sepete ürün ekleme fonksiyonu
session_start();
include('database.php');

// Müşteri kontrolü
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$musteri_id = $_SESSION['user_id'];
$urun_id = $_POST['urun_id'] ?? 0;

// Ürünün sepete eklenmesi
if ($urun_id > 0) {
    $query = "INSERT INTO Sepet (Musteri_ID, Urun_ID) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $musteri_id, $urun_id);

    if ($stmt->execute()) {
        header("Location: my_cart.php");
    } else {
        echo "Sepete eklerken bir hata oluştu.";
    }
} else {
    echo "Geçersiz ürün.";
}
?>
