<?php
// satıcı panel sayfası
session_start();
include('../database.php');
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit();
}

$seller_id = $_SESSION['user_id'];
$query = "SELECT * FROM Urun WHERE Satici_ID = '$seller_id'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Sorgu başarısız: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Satıcı Paneli</title>
</head>
<body>
    <h1>Satıcı Paneli</h1>
    <h2>Ürünler</h2>
    <table border="1">
        <tr>
            <th>Ürün Adı</th>
            <th>Fiyat</th>
            <th>Stok</th>
            <th>Durum</th>
            <th>İşlemler</th>
        </tr>
        <?php while ($product = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $product['Urun_Adi']; ?></td>
            <td><?php echo $product['Urun_Fiyati']; ?> TL</td>
            <td><?php echo $product['Stok_Adedi']; ?></td>
            <td><?php echo $product['Aktiflik_Durumu'] ? 'Aktif' : 'Pasif'; ?></td>
            <td>
                <a href="edit_product.php?id=<?php echo $product['Urun_ID']; ?>">Düzenle</a>
                <a href="delete_product.php?id=<?php echo $product['Urun_ID']; ?>">Sil</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <a href="add_product.php"><button>Ürün Ekle</button></a>
</body>
</html>