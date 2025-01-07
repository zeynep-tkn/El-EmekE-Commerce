<?php
// Sepetim Sayfası
session_start();
include("../database.php");

// Kullanıcı kontrolü
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

// Kullanıcı ID'sini oturumdan al
$user_id = $_SESSION['user_id'];

// Kullanıcının Müşteri ID'sini almak için sorgu
$query = "SELECT Musteri_ID FROM Musteri WHERE User_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $musteri_id = $row['Musteri_ID'];  // Müşteri ID'sini al
} else {
    // Eğer müşteri ID'si yoksa hata mesajı
    echo "Müşteri ID'si bulunamadı.";
    exit();
}

// Şimdi doğru müşteri ID'sini kullanarak sepet verilerini çekebilirsiniz
$query = "SELECT Sepet.Sepet_ID, Sepet.Boyut, Sepet.Miktar, Sepet.Eklenme_Tarihi, Urun.Urun_Adi, Urun.Urun_Fiyati, Urun.Urun_Gorseli 
          FROM Sepet 
          JOIN Urun ON Sepet.Urun_ID = Urun.Urun_ID 
          WHERE Sepet.Musteri_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $musteri_id);  // Burada doğru müşteri ID'sini kullanıyoruz
$stmt->execute();
$result = $stmt->get_result();



// Sepet ürünü silme işlemi
if (isset($_GET['delete'])) {
    $sepet_id = $_GET['delete'];
    $delete_query = "DELETE FROM Sepet WHERE Sepet_ID = ? AND Musteri_ID = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("ii", $sepet_id, $musteri_id);
    $delete_stmt->execute();
    header("Location: my_cart.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepetim</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .cart-table th, .cart-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .cart-table th {
            background-color: #f8f8f8;
        }

        .cart-table tr:hover {
            background-color: #f1f1f1;
        }

        .total-row {
            background-color: #f8f8f8;
            font-weight: bold;
        }

        .total-row td {
            border-top: 2px solid #ddd;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .action-buttons button:hover {
            background-color: #0056b3;
        }

        .action-buttons .delete-button {
            background-color: #dc3545;
        }

        .action-buttons .delete-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Sepetim</h1>
    <?php if ($result->num_rows > 0): ?>
        <form action="checkout.php" method="POST">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Ürün Adı</th>
                        <th>Fiyat</th>
                        <th>Boyut</th>
                        <th>Miktar</th>
                        <th>Toplam</th>
                        <th>Eklenme Tarihi</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $genel_toplam = 0;
                    while ($row = $result->fetch_assoc()): 
                        $urun_toplam = $row['Urun_Fiyati'] * $row['Miktar'];
                        $genel_toplam += $urun_toplam;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Urun_Adi']) ?></td>
                            <td><?= htmlspecialchars($row['Urun_Fiyati']) ?> TL</td>
                            <td><?= htmlspecialchars($row['Boyut']) ?></td>
                            <td><?= htmlspecialchars($row['Miktar']) ?></td>
                            <td><?= $urun_toplam ?> TL</td>
                            <td><?= htmlspecialchars($row['Eklenme_Tarihi']) ?></td>
                            <td class="action-buttons">
                                <!-- Ürün Silme -->
                                <a href="?delete=<?= $row['Sepet_ID'] ?>" class="delete-button" onclick="return confirm('Bu ürünü sepetinizden silmek istediğinizden emin misiniz?');">Sil</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <tr class="total-row">
                        <td colspan="4"><strong>Genel Toplam:</strong></td>
                        <td><strong><?= $genel_toplam ?> TL</strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <div style="text-align: right; margin-top: 20px;">
                <button type="submit" class="btn btn-success">Sepeti Onayla</button>
            </div>
        </form>
    <?php else: ?>
        <p>Sepetinizde ürün bulunmamaktadır.</p>
    <?php endif; ?>
</div>
</body>
</html>
