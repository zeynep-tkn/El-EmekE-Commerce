<?php
// Sepete ürün ekleme fonksiyonu
session_start();
include('../database.php');
// Müşteri kontrolü: Kullanıcı giriş yapmış mı?
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

// Müşteri kaydını kontrol et
$query = "SELECT * FROM Musteri WHERE User_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $musteri_id);
$stmt->execute();
$result = $stmt->get_result();

// Müşteri kaydı yoksa, ekle
if ($result->num_rows == 0) {
    $query = "INSERT INTO Musteri (User_ID) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $musteri_id);
    if (!$stmt->execute()) {
        echo "Müşteri kaydı eklenirken bir hata oluştu.";
        exit();
    }
}



// Ürünün sepete eklenmesi
if ($urun_id > 0) {
    $eklenme_tarihi = date('Y-m-d');
    
    // Sepette aynı ürün var mı kontrol et
    $query = "SELECT * FROM Sepet WHERE Musteri_ID = ? AND Urun_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $musteri_id, $urun_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Aynı ürün varsa miktarı güncelle
        $query = "UPDATE Sepet SET Miktar = Miktar + ? WHERE Musteri_ID = ? AND Urun_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iii", $miktar, $musteri_id, $urun_id);
    } else {
        // Yeni ürün ekle
        $query = "INSERT INTO Sepet (Boyut, Miktar, Eklenme_Tarihi, Urun_ID, Musteri_ID) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iisii", $boyut, $miktar, $eklenme_tarihi, $urun_id, $musteri_id);
    }

    if ($stmt->execute()) {
        header("Location: my_cart.php");  // Başarılı ekleme sonrası sepete yönlendir
    } else {
        echo "Sepete eklerken bir hata oluştu.";
    }
} else {
    echo "Geçersiz ürün.";
}

?>
