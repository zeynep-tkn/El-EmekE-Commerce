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
}

.store-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-right: 20px;
}

.store-info {
    flex-grow: 1;
}

.store-name {
    margin: 0;
    font-size: 24px;
}

.seller-name {
    margin: 5px 0;
    color: #777;
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
    width: 30%; /* Bu değeri ekleyerek arama çubuğunun genişliğini ayarlayabilirsiniz */
    margin-left: auto; /* Bu değeri ekleyerek arama çubuğunu ortalayabilirsiniz */
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
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.product {
    background-color: #fff;
    padding: 5px; 
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); /* Box-shadow değerini küçülttüm */
    width: calc(30% - 60px); /* Genişliği biraz küçülttüm */
    box-sizing: border-box;
    margin: auto;
}

.product-image {
    width: 100%;
    height: 40%;
}

.product-name {
    font-size: 18px;
    margin: 10px 0 5px;
}

.product-price {
    color: #ff6f61;
    font-size: 16px;
}
    </style>


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
            <div class="d-flex me-3" href="#" style="margin-left: 145px;">
                <i class="bi bi-person-circle text-white fs-4"></i>
                <a href="login.php" class="text-white mt-2 ms-2" style="font-size: 15px; text-decoration: none;">
                    Giriş Yap
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="container">
        <div class="store-header">
            <img src="../images/magaza.png" class="store-image">
            <div class="store-info">
                <h1 class="store-name">Mağaza Adı</h1>
                <p class="seller-name">Satıcı Adı Soyadı</p>
                <button class="follow-button">Takip Et</button>
            </div>
            </div>
            <div class="search-bar">
              <input type="text" id="search-input" placeholder="Ürün ara...">
              <button onclick="searchProducts()">Ara</button>
           </div>

        <div class="products">
            <!-- Ürünler buraya gelecek -->
            <div class="product">
                <img src="../images/59.png" alt="Ürün 1" class="product-image">
                <p class="product-name">Ürün Adı 1</p>
                <p class="product-price">₺100</p>
            </div>
            <div class="product">
                <img src="../images/60.png" alt="Ürün 2" class="product-image">
                <p class="product-name">Ürün Adı 2</p>
                <p class="product-price">₺200</p>
            </div>
            <div class="product">
                <img src="../images/59.png" alt="Ürün 3" class="product-image">
                <p class="product-name">Ürün Adı 2</p>
                <p class="product-price">₺200</p>
            </div>
            <div class="product">
                <img src="../images/60.png" alt="Ürün 4" class="product-image">
                <p class="product-name">Ürün Adı 2</p>
                <p class="product-price">₺200</p>
            </div>
            <div class="product">
                <img src="../images/59.png" alt="Ürün 5" class="product-image">
                <p class="product-name">Ürün Adı 2</p>
                <p class="product-price">₺200</p>
            </div>
            <div class="product">
                <img src="../images/60.png" alt="Ürün 6" class="product-image">
                <p class="product-name">Ürün Adı 2</p>
                <p class="product-price">₺200</p>
            </div>
            <div class="product">
                <img src="../images/60.png" alt="Ürün 7" class="product-image">
                <p class="product-name">Ürün Adı 2</p>
                <p class="product-price">₺200</p>
            </div>
            <div class="product">
                <img src="../images/59.png" alt="Ürün 8" class="product-image">
                <p class="product-name">Ürün Adı 2</p>
                <p class="product-price">₺200</p>
            </div>
            <div class="product">
                <img src="../images/60.png" alt="Ürün 9" class="product-image">
                <p class="product-name">Ürün Adı 2</p>
                <p class="product-price">₺200</p>
            </div>
            <!-- Daha fazla ürün ekleyebilirsiniz -->
        </div>
    </div>

</div>


    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- !BOOTSTRAP'S jS-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- !BOOTSTRAP'S jS-->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
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
