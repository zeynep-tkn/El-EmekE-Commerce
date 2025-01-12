<?php
// Satıcı panel sayfası
session_start();
include('../database.php');

// Giriş yapmış kullanıcı bilgilerini kontrol et
$logged_in = isset($_SESSION['user_id']); // Kullanıcı giriş yapmış mı kontrol et
$username = $logged_in ? $_SESSION['username'] : null; // Kullanıcı adını al


$user_id = $_SESSION['user_id']; // Kullanıcı ID'sini alıyoruz

// Satıcının Satici_ID ve mağaza adı, ad soyadını çekmek için sorgu
$seller_info_query = "SELECT Satici_ID, Magaza_Adi, Ad_Soyad FROM satici WHERE User_ID = '$user_id'";
$seller_info_result = mysqli_query($conn, $seller_info_query);

// Debug satırı: Satıcı bilgilerini kontrol et
//echo "Seller Info Query: " . $seller_info_query . "<br>";

if ($seller_info_result && mysqli_num_rows($seller_info_result) > 0) {
    $seller_info = mysqli_fetch_assoc($seller_info_result);
    $satici_id = $seller_info['Satici_ID'];  // Satici_ID'yi alıyoruz
    $store_name = $seller_info['Magaza_Adi'];
    $seller_name = $seller_info['Ad_Soyad'];
} else {
    // Eğer satıcı bilgisi bulunamazsa varsayılan değerler
    $satici_id = null; // Eğer satıcı yoksa null olarak ayarla
    $store_name = "Mağaza Adı Bulunamadı";
    $seller_name = "Satıcı Adı Bulunamadı";
}

// Satıcının ürünlerini çekmek için sorgu
$product_query = "SELECT * FROM Urun WHERE Satici_ID = '$satici_id'";  // Burada Satici_ID kullanılıyor
$product_result = mysqli_query($conn, $product_query);

// Debug satırı: Ürün sorgusunu kontrol et
//echo "Product Query: " . $product_query . "<br>";

if (!$product_result) {
    die("Sorgu başarısız: " . mysqli_error($conn));
}
?>



<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Satıcı Paneli</title>
    <style>
    
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.container {
    width: 80%;
    margin: 0 auto;
    background-color: #fff;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.store-header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #ccc;
}

.store-image {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    margin-right: 20px;
}

.store-info {
    flex-grow: 1;
}

.store-name {
    margin: 0;
    font-size: 28px;
    font-weight: bold;
    color: #333;
}

.seller-name {
    margin: 5px 0;
    color: #777;
    font-size: 16px;
}

.follow-button {
    padding: 10px 20px;
    background-color: #ff6f61;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.follow-button:hover {
    background-color: #ff3b2f;
}

.search-bar {
    display: flex;
    margin-bottom: 20px;
    width: 40%;
    margin-left: auto;
}

#search-input {
    flex-grow: 1;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px 0 0 5px;
}

.search-bar button {
    padding: 10px 20px;
    background-color: #ff6f61;
    color: #fff;
    border: none;
    border-radius: 0 5px 5px 0;
    cursor: pointer;
}

.search-bar button:hover {
    background-color: #ff3b2f;
}

.products {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.product {
    background-color: #fff;
    padding: 15px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-in-out;
    text-align: center;
}

.product:hover {
    transform: scale(1.05);
}

.product-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    margin-bottom: 15px;
}

.product-name {
    font-size: 18px;
    margin: 10px 0 5px;
    font-weight: bold;
    color: #333;
}

.product-price {
    color: #ff6f61;
    font-size: 16px;
    font-weight: bold;
}

    </style>
</head>

     <!-- !BOOTSTRAP'S CSS-->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                    <a id="navbarDropdown" class="nav-link" href="order_manage.php">
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

<div class="container mt-5">
    <div class="store-header">
        <img src="../images/magaza.png" class="store-image">
        <div class="store-info">
            <h1 class="store-name"><?php echo htmlspecialchars($store_name); ?></h1>
            <p class="seller-name"><?php echo htmlspecialchars($seller_name); ?></p>
            <button class="follow-button">Takip Et</button>
        </div>
    </div>

    <div class="search-bar">
        <input type="text" id="search-input" placeholder="Ürün ara...">
        <button onclick="searchProducts()">Ara</button>
    </div>

    <!-- Ürünlerin görüntülenmesi -->
    <div class="products">
        <?php if (mysqli_num_rows($product_result) > 0): ?>
            <?php while ($product = mysqli_fetch_assoc($product_result)): ?>
                <div class="product">
                    <img src="../uploads/<?php echo htmlspecialchars($product['Urun_Gorseli']); ?>" alt="<?php echo htmlspecialchars($product['Urun_Adi']); ?>" class="product-image">
                    <p class="product-name"><?php echo htmlspecialchars($product['Urun_Adi']); ?></p>
                    <p class="product-price">₺<?php echo htmlspecialchars($product['Urun_Fiyati']); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Henüz eklenmiş ürün yok.</p>
        <?php endif; ?>
    </div>
</div>

    <script>
        function searchProducts() {
    const input = document.getElementById('search-input').value.toLowerCase();
    const products = document.getElementsByClassName('product');

    for (let i = 0; i < products.length; i++) {
        const productName = products[i].getElementsByClassName('product-name')[0].innerText.toLowerCase();
        if (productName.includes(input)) {
            products[i].style.display = '';
        } else {
            products[i].style.display = 'none';
        }
    }
}
    </script>
</body>
</html>
