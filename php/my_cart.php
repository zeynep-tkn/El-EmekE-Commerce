<?php
session_start();
include('../database.php');

// Kullanıcının oturum açıp açmadığını kontrol et
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$musteri_id = $_SESSION['user_id'];
$query = "SELECT Urun.Urun_ID, Urun.Urun_Adi, Urun.Urun_Fiyati, Urun.Urun_Gorseli, Sepet.Miktar 
          FROM Sepet 
          JOIN Urun ON Sepet.Urun_ID = Urun.Urun_ID 
          WHERE Sepet.Musteri_ID = '$musteri_id'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Sorgu başarısız: " . mysqli_error($conn));
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
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
        <h1>SEPETİM</h1>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Ürün Görseli</th>
                    <th>Ürün Adı</th>
                    <th>Fiyatı</th>
                    <th>Miktarı</th>
                    <th>Toplam</th>
                    <th>Durum</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = mysqli_fetch_assoc($result)) { 
                    $subtotal = $item['Urun_Fiyati'] * $item['Miktar'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><img src="<?php echo $item['Urun_Gorseli']; ?>" alt="Product Image" class="product-image"></td>
                    <td><?php echo $item['Urun_Adi']; ?></td>
                    <td><?php echo $item['Urun_Fiyati']; ?> TL</td>
                    <td><?php echo $item['Miktar']; ?></td>
                    <td><?php echo $subtotal; ?> TL</td>
                    <td class="action-buttons">
                        <form action="update_cart.php" method="POST" style="display:inline;">
                            <input type="hidden" name="urun_id" value="<?php echo $item['Urun_ID']; ?>">
                            <input type="hidden" name="action" value="add">
                            <button type="submit">+</button>
                        </form>
                        <form action="update_cart.php" method="POST" style="display:inline;">
                            <input type="hidden" name="urun_id" value="<?php echo $item['Urun_ID']; ?>">
                            <input type="hidden" name="action" value="remove">
                            <button type="submit" class="delete-button">🗑️</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
                <tr class="total-row">
                    <td colspan="5"><strong>Total</strong></td>
                    <td><strong><?php echo $total; ?> TL</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>