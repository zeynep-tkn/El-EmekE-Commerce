<?php
// Sepete ürün ekleme fonksiyonu
session_start();
include("../database.php"); 

// Oturum kontrolü ve kullanıcı giriş kontrolü
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    die("Oturum başlatılmadı veya kullanıcı giriş yapmadı.");
}

$user_id = (int)$_SESSION['user_id']; // Oturumdan user_id'yi al
//$user_id = $_SESSION['user_id']; 


if (!isset($_POST['urun_id'], $_POST['boyut'], $_POST['miktar'])) {
    die("Eksik veri gönderildi! Gelen veriler: " . print_r($_POST, true));
}

echo '<pre>';
print_r($_POST);
echo '</pre>';


// Formdan gelen verileri doğrula
$urun_id = (int)$_POST['urun_id'];
$boyut = (int)$_POST['boyut'];
$miktar = (int)$_POST['miktar'];

// Geçersiz ürün veya miktar kontrolü
if ($urun_id <= 0 || $miktar <= 0) {
    die("Geçersiz ürün veya miktar.");
}

// Müşteri kaydını kontrol et
$query = "SELECT Musteri_ID FROM musteri WHERE User_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $musteri_id = $row['Musteri_ID']; // Müşteri varsa ID'yi al
} else {
    // Müşteri kaydı yoksa ekle
    $query = "INSERT INTO musteri (User_ID) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $musteri_id = $conn->insert_id; // Yeni eklenen müşteri ID'sini al
    } else {
        die("Müşteri kaydı eklenirken bir hata oluştu: " . $stmt->error);
    }
}

// Ürünün sepette olup olmadığını kontrol et
$query = "SELECT * FROM sepet WHERE Musteri_ID = ? AND Urun_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $musteri_id, $urun_id);
$stmt->execute();
$result = $stmt->get_result();

$eklenme_tarihi = date('Y-m-d');

if ($result->num_rows > 0) {
    // Aynı ürün varsa miktarı güncelle
    $query = "UPDATE sepet SET Miktar = Miktar + ? WHERE Musteri_ID = ? AND Urun_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $miktar, $musteri_id, $urun_id);
} else {
    // Yeni ürün ekle
    $query = "INSERT INTO sepet (Boyut, Miktar, Eklenme_Tarihi, Urun_ID, Musteri_ID) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iisii", $boyut, $miktar, $eklenme_tarihi, $urun_id, $musteri_id);
}

// Sorguyu çalıştır ve sonucu kontrol et
if ($stmt->execute()) {
    // Başarılı ekleme sonrası sepete yönlendir
    header("Location: ../php/my_cart.php"); // Sepet sayfasına yönlendir
    exit();
} else {
    die("Sepete eklerken bir hata oluştu: " . $stmt->error);
}
?>
