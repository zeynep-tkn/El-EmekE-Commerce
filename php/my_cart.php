<?php
//sepetim sayfası
session_start();
include("../database.php");

// Müşteri kontrolü
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$musteri_id = $_SESSION['user_id'];

// Sepetteki ürünleri çek
$query = "SELECT Sepet.Sepet_ID, Sepet.Boyut, Sepet.Miktar, Sepet.Eklenme_Tarihi, Urun.Urun_Adi, Urun.Urun_Fiyati 
          FROM Sepet 
          JOIN Urun ON Sepet.Urun_ID = Urun.Urun_ID 
          WHERE Sepet.Musteri_ID = ?";
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

    .product-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
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
        <form action="checkout.php" method="POST">
            <table class="table">
                <thead>
                    <tr>
                        <th>Ürün Adı</th>
                        <th>Fiyat</th>
                        <th>Boyut</th>
                        <th>Miktar</th>
                        <th>Toplam</th>
                        <th>Eklenme Tarihi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    while ($row = $result->fetch_assoc()): 
                        $subtotal = $row['Urun_Fiyati'] * $row['Miktar'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Urun_Adi']) ?></td>
                            <td><?= htmlspecialchars($row['Urun_Fiyati']) ?> TL</td>
                            <td><?= htmlspecialchars($row['Boyut'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['Miktar']) ?></td>
                            <td><?= $subtotal ?> TL</td>
                            <td><?= htmlspecialchars($row['Eklenme_Tarihi']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <tr>
                        <td colspan="4"><strong>Genel Toplam:</strong></td>
                        <td><strong><?= $total ?> TL</strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <button type="submit" class="btn btn-success">Sepeti Onayla</button>
        </form>
    </div>
</body>
</html>