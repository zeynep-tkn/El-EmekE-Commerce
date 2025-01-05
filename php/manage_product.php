<?php
// Ürün yönetim sayfası
session_start();
include('../database.php');

// Kullanıcı oturum ve yetki kontrolü
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit();
}

$seller_id = $_SESSION['user_id']; // Oturumdaki kullanıcının ID'si

// Satıcının ürünlerini getirme sorgusu
$query = "SELECT * FROM Urun WHERE Satici_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();

// Hata kontrolü
if (!$result) {
    die("Sorgu başarısız: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Yönetimi</title>
    <!-- Bootstrap ve diğer gerekli stil dosyaları -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
     <!-- !BOOTSTRAP'S CSS-->
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Edu+AU+VIC+WA+NT+Hand:wght@400..700&family=Montserrat:wght@100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="stylesheet" href="css/css.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courgette&family=Edu+AU+VIC+WA+NT+Hand:wght@400..700&family=Montserrat:wght@100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
/>
 <script src="https://code.jquery.com/jquery-1.8.2.min.js" integrity="sha256-9VTS8JJyxvcUR+v+RTLTsd0ZWbzmafmlzMmeZO9RFyk=" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 20px;
        }
        .product-form {
            margin-bottom: 30px;
        }
        .product-table img {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: rgb(244, 74, 51);">
    <div class="container-fluid">
        <a class="navbar-brand d-flex ms-4" href="#" style="margin-left: 5px;">
         
            <div class="baslik fs-3"> ELEMEK</div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse mt-1 bg-custom" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="margin-left: 110px;">
            <li class="nav-item ps-3">
                    <a id="navbarDropdown" class="nav-link" href="seller_dashboard.php">
                        Satıcı Paneli
                    </a>
                </li>
                <li class="nav-item ps-3">
                    <a id="navbarDropdown" class="nav-link" href="seller_manage.php">
                        Mağaza Yönetimi
                    </a>
                </li>
                <li class="nav-item ps-3">
                    <a id="navbarDropdown" class="nav-link" href="manage_product.php">
                        Ürün Yönetimi
                    </a>
                </li>
                <li class="nav-item ps-3">
                    <a id="navbarDropdown" class="nav-link" href="seller_manage.php">
                        Sipariş Yönetimi
                    </a>
                </li>
            </ul>
            <div class="d-flex me-3" href="#" style="margin-left: 145px;">
                <i class="bi bi-person-circle text-white fs-4"></i>
                <a href="login.php" class="text-white mt-2 ms-2" style="font-size: 15px; text-decoration: none;">
                    Giriş Yap
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="mt-4">Ürün Yönetimi</h2>

    <!-- Ürün ekleme formu -->
    <div class="product-form">
        <form action="product_action.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="product-name" class="form-label">Ürün Adı</label>
                <input type="text" name="product_name" id="product-name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="product-price" class="form-label">Ürün Fiyatı</label>
                <input type="number" name="product_price" id="product-price" class="form-control" required>
            </div>
            <div class="mb-3">
            <label for="product-stock" class="form-label">Stok Adedi</label>
            <input type="number" name="product_stock" id="product-stock" class="form-control" required>
        </div>
            <div class="mb-3">
                <label for="product-image" class="form-label">Ürün Görseli</label>
                <input type="file" name="product_image" id="product-image" class="form-control" required>
            </div>
            <button type="submit" name="add_product" class="btn btn-success">Ürün Ekle</button>
        </form>
    </div>

    <!-- Ürün listeleme tablosu -->
    <table class="table table-striped product-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ürün Adı</th>
                <th>Fiyat</th>
                <th>Stok</th>
                <th>Görsel</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Urun_ID']) ?></td>
                    <td><?= htmlspecialchars($row['Urun_Adi']) ?></td>
                    <td>₺<?= htmlspecialchars($row['Urun_Fiyati']) ?></td>
                    <td><?= htmlspecialchars($row['Stok_Adedi']) ?></td>
                    <td><img src="../uploads/<?= htmlspecialchars($row['Urun_Gorseli']) ?>" alt="Ürün Görseli"></td>
                    <td>
                        <form action="product_action.php" method="POST" class="d-inline">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($row['Urun_ID']) ?>">
                            <button type="submit" name="edit_product" class="btn btn-primary btn-sm">Düzenle</button>
                        </form>
                        <form action="product_action.php" method="POST" class="d-inline">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($row['Urun_ID']) ?>">
                            <button type="submit" name="delete_product" class="btn btn-danger btn-sm">Sil</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- !BOOTSTRAP'S jS-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>


</body>
</html>
