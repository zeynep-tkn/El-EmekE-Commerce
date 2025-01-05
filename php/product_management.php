<?php
//ürün düzenleme sayfası
session_start();
include('../database.php');

// Kullanıcı doğrulama
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit();
}

$seller_user_id = $_SESSION['user_id'];

// Satıcı ID'sini al
$query = "SELECT Satici_ID FROM Satici WHERE User_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $seller_user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Satıcı kaydı bulunamadı. Lütfen bir satıcı hesabı oluşturun.");
}

$satici = $result->fetch_assoc();
$satici_id = $satici['Satici_ID'];

// Ürün silme işlemi
if (isset($_GET['delete'])) {
    $product_id = intval($_GET['delete']);
    $query = "DELETE FROM Urun WHERE Urun_ID = ? AND Satici_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $product_id, $satici_id);

    if ($stmt->execute()) {
        header("Location: product_management.php");
        exit();
    } else {
        echo "Ürün silinirken bir hata oluştu: " . $conn->error;
    }
}

// Ürünleri listele
$query = "SELECT * FROM Urun WHERE Satici_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $satici_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Yönetimi</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <h1>Ürün Yönetimi</h1>
        <a href="add_product.php" class="btn btn-primary">Yeni Ürün Ekle</a>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ürün Adı</th>
                    <th>Fiyat</th>
                    <th>Stok</th>
                    <th>Durum</th>
                    <th>Görsel</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($product = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['Urun_ID']) ?></td>
                            <td><?= htmlspecialchars($product['Urun_Adi']) ?></td>
                            <td><?= htmlspecialchars($product['Urun_Fiyati']) ?> TL</td>
                            <td><?= htmlspecialchars($product['Stok_Adedi']) ?></td>
                            <td><?= $product['Aktiflik_Durumu'] ? 'Aktif' : 'Pasif' ?></td>
                            <td>
                                <?php if ($product['Urun_Gorseli']): ?>
                                    <img src="../uploads/<?= htmlspecialchars($product['Urun_Gorseli']) ?>" alt="Ürün Görseli" width="50">
                                <?php else: ?>
                                    Görsel Yok
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_product.php?id=<?= $product['Urun_ID'] ?>" class="btn btn-warning">Düzenle</a>
                                <a href="product_management.php?delete=<?= $product['Urun_ID'] ?>" class="btn btn-danger" onclick="return confirm('Bu ürünü silmek istediğinizden emin misiniz?')">Sil</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Henüz ürün eklenmedi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
