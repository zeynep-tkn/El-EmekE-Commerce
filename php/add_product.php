<?php
//Ürün ekleme sayfası
//boş şu an
session_start();
include('../database.php');

// Satıcı olup olmadığını kontrol et
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM Satici WHERE User_ID = '$user_id'";
$result = mysqli_query($conn, $query);
$satici = mysqli_fetch_assoc($result);

if (!$satici) {
    echo "Satıcı kaydınız yok. Lütfen admin tarafından onaylanın.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen ürün bilgileri
    $urun_adi = $_POST['product_name'];
    $urun_fiyati = $_POST['price'];
    $stok_adedi = $_POST['stock'];
    $urun_aciklamasi = $_POST['description'];
    $satici_id = $satici['Satici_ID']; // Satıcı ID'si
    
        // Ürün ekle
        $query = "INSERT INTO Urun (Urun_Adi, Urun_Fiyati, Stok_Adedi, Urun_Aciklamasi, Satici_ID) VALUES ('$urun_adi', '$urun_fiyati', '$stok_adedi', '$urun_aciklamasi', '$satici_id')";
        if (mysqli_query($conn, $query)) {
            // Ürün başarıyla eklendi, satıcı paneline yönlendir
            header("Location: seller_dashboard.php");
            exit();
        } else {
            echo "Ürün eklenirken bir hata oluştu.";
        }
    }
    ?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Ekleme</title>
</head>
<body>   
<form action="add_product.php" method="POST">
    <label for="product_name">Ürün Adı:</label>
    <input type="text" name="product_name" required><br>

    <label for="price">Fiyat:</label>
    <input type="number" name="price" step="0.01" required><br>

    <label for="stock">Stok:</label>
    <input type="number" name="stock" required><br>

    <label for="description">Açıklama:</label>
    <textarea name="description" required></textarea><br>

    <button type="submit">Ürün Ekle</button>
</form>
</body>
</html>