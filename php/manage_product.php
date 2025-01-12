<?php
// ürün yönetim sayfası
session_start();
include('../database.php');


// Giriş yapmış kullanıcı bilgilerini kontrol et
$logged_in = isset($_SESSION['user_id']); // Kullanıcı giriş yapmış mı kontrol et
$username = $logged_in ? $_SESSION['username'] : null; // Kullanıcı adını al


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

// Ürün ekleme işlemi
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $urun_adi = $_POST['product_name'] ?? '';
    $urun_fiyati = $_POST['product_price'] ?? 0;
    $stok_adedi = $_POST['product_stock'] ?? 0;
    $urun_aciklama = $_POST['product_description'] ?? '';
    $aktiflik_durumu = isset($_POST['product_status']) ? 1 : 0;

    $urun_gorseli = null;
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['product_image']['tmp_name'];
        $file_name = uniqid() . "_" . $_FILES['product_image']['name'];
        $upload_dir = "../uploads/";

        if (move_uploaded_file($file_tmp_path, $upload_dir . $file_name)) {
            $urun_gorseli = $file_name;
        } else {
            $message = "Dosya yüklenirken bir hata oluştu.";
        }
    }

    if ($urun_adi && $urun_fiyati && $stok_adedi) {
        $query = "INSERT INTO Urun (Urun_Adi, Urun_Fiyati, Stok_Adedi, Urun_Gorseli, Urun_Aciklamasi, Aktiflik_Durumu, Satici_ID) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sdissii", $urun_adi, $urun_fiyati, $stok_adedi, $urun_gorseli, $urun_aciklama, $aktiflik_durumu, $satici_id);

        if ($stmt->execute()) {
            $message = "Ürün başarıyla eklendi.";
        } else {
            $message = "Ürün eklenirken bir hata oluştu: " . $conn->error;
        }
    } else {
        $message = "Lütfen tüm alanları doldurun.";
    }
}

// Ürün silme işlemi
if (isset($_GET['delete'])) {
    $product_id = intval($_GET['delete']);
    $query = "DELETE FROM Urun WHERE Urun_ID = ? AND Satici_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $product_id, $satici_id);

    if ($stmt->execute()) {
        header("Location: manage_product.php");
        exit();
    } else {
        $message = "Ürün silinirken bir hata oluştu.";
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
    width: 80%;
    margin: 20px auto;
    background-color: #fff;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.product-form {
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

.form-group input[type="file"] {
    padding: 3px;
}

.form-group input[type="checkbox"] {
    width: auto;
}

.btn-primary {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.btn-primary:hover {
    background-color: #0056b3;
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
                    <a id="navbarDropdown" class="nav-link" href="customer_orders.php">
                        Sipariş Yönetimi
                    </a>
                </li>
            </ul>
            <div class="d-flex me-3" style="margin-left: 145px;">
    <i class="bi bi-person-circle text-white fs-4"></i>
    <?php if (isset($_SESSION['username'])): ?>
        <!-- Kullanıcı giriş yaptıysa -->
        <a href="logout.php" class="text-white mt-2 ms-2" style="font-size: 15px; text-decoration: none;">
            <?php echo htmlspecialchars($_SESSION['username']); ?> <!-- Kullanıcı adı gösteriliyor -->
        </a>
    <?php else: ?>
        <!-- Kullanıcı giriş yapmamışsa -->
        <a href="login.php" class="text-white mt-2 ms-2" style="font-size: 15px; text-decoration: none;">
            Giriş Yap
        </a>
    <?php endif; ?>
</div>
        </div>
    </div>
</nav>


    <div class="container">
        <h1>Ürün Yönetimi</h1>
        <?php if ($message): ?>
            <div class="alert"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form action="manage_product.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="product_name">Ürün Adı:</label>
                <input type="text" name="product_name" id="product_name" required>
            </div>
            <div class="form-group">
                <label for="product_price">Fiyat:</label>
                <input type="number" name="product_price" id="product_price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="product_stock">Stok Adedi:</label>
                <input type="number" name="product_stock" id="product_stock" required>
            </div>
            <div class="form-group">
                <label for="product_image">Ürün Görseli:</label>
                <input type="file" name="product_image" id="product_image">
            </div>
            <div class="form-group">
                <label for="product_description">Ürün Açıklaması:</label>
                <textarea name="product_description" id="product_description"></textarea>
            </div>
            <div class="form-group">
                <label for="product_status">Aktiflik Durumu:</label>
                <input type="checkbox" name="product_status" id="product_status">
            </div>
            <button type="submit" class="btn btn-primary">Ürün Ekle</button>
        </form>

        <hr>

        <h2>Mevcut Ürünler</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>id</th>
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
                                <a href="manage_product.php?delete=<?= $product['Urun_ID'] ?>" class="btn btn-danger" onclick="return confirm('Bu ürünü silmek istediğinizden emin misiniz?')">Sil</a>
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
