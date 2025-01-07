<?php
session_start();
include('database.php');

// Müşteri kontrolü
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$musteri_id = $_SESSION['user_id'];

// Müşterinin siparişlerini çek
$query = "SELECT Siparis.*, SiparisUrun.Urun_ID, SiparisUrun.Miktar, SiparisUrun.Fiyat, Urun.Urun_Adi
          FROM Siparis
          JOIN SiparisUrun ON Siparis.Siparis_ID = SiparisUrun.Siparis_ID
          JOIN Urun ON SiparisUrun.Urun_ID = Urun.Urun_ID
          WHERE Siparis.Musteri_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $musteri_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siparişlerim</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Siparişlerim</h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Sipariş ID</th>
                        <th>Ürün Adı</th>
                        <th>Miktar</th>
                        <th>Fiyat</th>
                        <th>Sipariş Tarihi</th>
                        <th>Durum</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['Siparis_ID']; ?></td>
                            <td><?php echo $row['Urun_Adi']; ?></td>
                            <td><?php echo $row['Miktar']; ?></td>
                            <td><?php echo $row['Fiyat']; ?></td>
                            <td><?php echo $row['Siparis_Tarihi']; ?></td>
                            <td><?php echo $row['Siparis_Durumu']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Henüz bir siparişiniz bulunmamaktadır.</p>
        <?php endif; ?>
    </div>
</body>
</html>
