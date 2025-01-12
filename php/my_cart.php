<?php
// Sepetim Sayfası
session_start();
include("../database.php");


// Giriş yapmış kullanıcı bilgilerini kontrol et
$logged_in = isset($_SESSION['user_id']); // Kullanıcı giriş yapmış mı kontrol et
$username = $logged_in ? $_SESSION['username'] : null; // Kullanıcı adını al


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
    <!-- !BOOTSTRAP'S CSS-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- !BOOTSTRAP'S CSS-->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Edu+AU+VIC+WA+NT+Hand:wght@400..700&family=Montserrat:wght@100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto+Slab:wght@100..900&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="css/css.css">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Courgette&family=Edu+AU+VIC+WA+NT+Hand:wght@400..700&family=Montserrat:wght@100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto+Slab:wght@100..900&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <script src="https://code.jquery.com/jquery-1.8.2.min.js"
    integrity="sha256-9VTS8JJyxvcUR+v+RTLTsd0ZWbzmafmlzMmeZO9RFyk=" crossorigin="anonymous">
    </script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  </head>

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
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 28px;
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
            font-size: 16px;
        }

        .cart-table td {
            font-size: 14px;
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

        .action-buttons a {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .action-buttons a:hover {
            background-color: #0056b3;
        }

        .action-buttons .delete-button {
            background-color:rgb(155, 10, 109) ;
        }

        .action-buttons .delete-button:hover {
            background-color:rgb(155, 10, 109) ;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
        }

        .quantity-controls button {
            background-color:rgb(0, 0, 0);
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            margin: 0 5px;
        }

        .quantity-controls button:hover {
            background-color:rgb(0, 0, 0);
        }

        .quantity-controls input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
        }
    </style>

<body>

<nav class="navbar  navbar-expand-lg navbar-dark" style="background-color:rgb(155, 10, 109) ;">
    <div class="container-fluid">

      <a class="navbar-brand d-flex ms-4" href="#" style="margin-left: 5px;">
        <img src="../images/logo.png" alt="Logo" width="35" height="35" class="align-text-top">

        <div class="baslik fs-3">
          <a class="dropdown-item" href="../index.php">
            ELEMEK
          </a>
        </div>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse mt-1 bg-custom" id="navbarSupportedContent">
        <ul class="navbar-nav  me-auto mb-2 mb-lg-0 " style="margin-left: 110px;">
          <li class="nav-item dropdown ps-3">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              Ana sayfa
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">Pizza Palace</a>
              <a class="dropdown-item" href="#">Coffee House</a>
              <a class="dropdown-item" href="#">Gourmet Gateway</a>
              <a class="dropdown-item" href="#">Flavor Fiesta</a>
              <a class="dropdown-item" href="#">Epicurean Explorer</a>
              <a class="dropdown-item" href="#">Epicurean Explorer Dark</a>
            </div>
          </li>
          <li class="nav-item dropdown ps-3">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              Satıcı
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="php/seller_register.php">Satıcı oluştur</a>
              <a class="dropdown-item" href="php/motivation.php">Girişimci Kadınlarımız</a>
              <a class="dropdown-item" href="#">Shop Details Coffee</a>
              <a class="dropdown-item" href="#">Cart</a>
              <a class="dropdown-item" href="#">Checkout</a>
            </div>
          </li>
          <li class="nav-item dropdown ps-3">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              Blog
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">Blog Grid One</a>
              <a class="dropdown-item" href="#">Blog Grid Two</a>
              <a class="dropdown-item" href="#">Blog Standard</a>
              <a class="dropdown-item" href="#">Blog Deails</a>

            </div>
          </li>
          <li class="nav-item dropdown ps-3">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              Siparişlerim
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="php/customer_orders.php">Sipariş Detay</a>
              <a class="dropdown-item" href="#">Chefs</a>
              <a class="dropdown-item" href="#">Faq</a>
              <a class="dropdown-item" href="#">Reservation</a>
              <a class="dropdown-item" href="#">Food Menu</a>
            </div>
          </li>
          <li class="nav-item dropdown ps-3">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              Hakkımızda
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">Contact</a>
              <a class="dropdown-item" href="#">Contact With Map</a>
            </div>
          </li>
        </ul>
        <!--SEARCH/FAVORİTES/CART-->
        <div style="margin-left: 0px;">
          <i class="bi bi-search text-white fs-5"></i>

          <a href="favourite.php">
          <i class="bi bi-heart text-white fs-5" style="margin-left: 20px;"></i>
          </a>

          <a href="my_cart.php">
            <i class="bi bi-cart3 text-white fs-5" style="margin-left: 20px;"></i>
          </a>
        </div>

        <div class="d-flex me-3" style="margin-left: 145px;">
    <i class="bi bi-person-circle text-white fs-4"></i>
    <?php if (isset($_SESSION['username'])): ?>
        <!-- Kullanıcı giriş yaptıysa -->
        <a href="php/logout.php" class="text-white mt-2 ms-2" style="font-size: 15px; text-decoration: none;">
            <?php echo htmlspecialchars($_SESSION['username']); ?> <!-- Kullanıcı adı gösteriliyor -->
        </a>
    <?php else: ?>
        <!-- Kullanıcı giriş yapmamışsa -->
        <a href="php/login.php" class="text-white mt-2 ms-2" style="font-size: 15px; text-decoration: none;">
            Giriş Yap
        </a>
    <?php endif; ?>
</div>

      </div>
    </div>
    </div>
    </div>
  </nav>



<div class="container">
    <h1>Sepetim</h1>
    <?php if ($result->num_rows > 0): ?>
        <form action="order_success.php" method="POST">
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
                            <td>
                                <div class="quantity-controls">
                                    <button type="button" onclick="decreaseQuantity(<?= $row['Sepet_ID'] ?>)">-</button>
                                    <input type="number" name="quantity[<?= $row['Sepet_ID'] ?>]" id="quantity-<?= $row['Sepet_ID'] ?>" value="<?= htmlspecialchars($row['Miktar']) ?>" min="1">
                                    <button type="button" onclick="increaseQuantity(<?= $row['Sepet_ID'] ?>)">+</button>
                                </div>
                            </td>
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
<script>
    function increaseQuantity(id) {
        var quantityInput = document.getElementById('quantity-' + id);
        quantityInput.value = parseInt(quantityInput.value) + 1;
    }

    function decreaseQuantity(id) {
        var quantityInput = document.getElementById('quantity-' + id);
        if (quantityInput.value > 1) {
            quantityInput.value = parseInt(quantityInput.value) - 1;
        }
    }
</script>
</body>
</html>