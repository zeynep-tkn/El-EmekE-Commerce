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
    <title>Satıcı Yönetim</title>
    <!-- !BOOTSTRAP'S CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- !BOOTSTRAP'S CSS-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Edu+AU+VIC+WA+NT+Hand:wght@400..700&family=Montserrat:wght@100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/css.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f4f4f9;
        }
        .navbar {
            background-color: #5b8cd5;
        }
        .navbar-brand .baslik {
            font-family: 'Playfair Display', serif;
        }
        .card {
            transition: transform 0.3s ease-in-out;
            min-height: 200px;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card.bg-primary {
    background-color: rgba(54, 162, 235, 0.6) !important;
    border-color: rgba(54, 162, 235, 1) !important;
}

.card.bg-success {
    background-color: rgba(75, 192, 192, 0.6) !important;
    border-color: rgba(75, 192, 192, 1) !important;
}

.card.bg-danger {
    background-color: rgba(255, 99, 132, 0.6) !important;
    border-color: rgba(255, 99, 132, 1) !important;
}
        .table th, .table td {
            vertical-align: middle;
        }
        .table img {
            border-radius: 8px;
        }
        .btn-custom {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }
        .btn-custom:hover {
            background-color: #218838;
        }
        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .row{
            margin-top: 50px;
        }

        .table-hover {
            margin-top: 10px;
}

h2 {
    margin-top: 50px;
}

        .chart-container {
    width: 100%;
    height: 400px;
    margin-top: 50px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
}
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand d-flex ms-4" href="#">
            <div class="baslik fs-3">ELEMEK</div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse mt-1 bg-custom" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="margin-left: 110px;">
                <li class="nav-item ps-3">
                    <a id="navbarDropdown" class="nav-link" href="seller_dashboard.php">Satıcı Paneli</a>
                </li>
                <li class="nav-item ps-3">
                    <a id="navbarDropdown" class="nav-link" href="seller_manage.php">Mağaza Yönetimi</a>
                </li>
                <li class="nav-item ps-3">
                    <a id="navbarDropdown" class="nav-link" href="manage_product.php">Ürün Yönetimi</a>
                </li>
                <li class="nav-item ps-3">
                    <a id="navbarDropdown" class="nav-link" href="seller_manage.php">Sipariş Yönetimi</a>
                </li>
            </ul>
            <div class="d-flex me-3" style="margin-left: 145px;">
                <i class="bi bi-person-circle text-white fs-4"></i>
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="logout.php" class="text-white mt-2 ms-2" style="font-size: 15px; text-decoration: none;">
                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="text-white mt-2 ms-2" style="font-size: 15px; text-decoration: none;">Giriş Yap</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-5 fade-in">
    <h1>Satıcı Yönetim Paneli</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Toplam Ürün</div>
                <div class="card-body">
                    <h5 class="card-title">150</h5>
                    <p class="card-text">Sistemde kayıtlı toplam ürün sayısı.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Aktif Ürünler</div>
                <div class="card-body">
                    <h5 class="card-title">50</h5>
                    <p class="card-text">Sistemde aktif olarak satış yapan ürün sayısı.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Kaldırılan Ürünler</div>
                <div class="card-body">
                    <h5 class="card-title">20</h5>
                    <p class="card-text">Sistemde pasif durumda olan ürün sayısı.</p>
                </div>
            </div>
        </div>
    </div>

     <!-- Grafik -->
     <div class="chart-container">
        <canvas id="productChart"></canvas>
    </div>



    
    <table class="table table-hover">
    <h2>Ürünler</h2>
        <thead>
            <tr>
                <th>id</th>
                <th>Ürün Adı</th>
                <th>Fiyat</th>
                <th>Stok</th>
                <th>Durum</th>
                <th>Görsel</th>
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
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!-- !BOOTSTRAP'S jS-->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('productChart').getContext('2d');
const productChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Toplam Ürün', 'Aktif Ürünler', 'Kaldırılan Ürünler'],
        datasets: [{
            label: 'Ürün Sayısı',
            data: [150, 50, 20], // Bu verileri dinamik olarak PHP ile değiştirebilirsiniz
            backgroundColor: [
                'rgba(54, 162, 235, 0.6)',
                'rgba(75, 192, 192, 0.6)',
                'rgba(255, 99, 132, 0.6)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 2,
            borderRadius: 10,
            borderSkipped: false
        }]
    },
    options: {
        plugins: {
            title: {
                display: true,
                text: 'Ürün Grafiği',
                color: '#333',
                font: {
                    size: 25,
                    family: 'Montserrat'
                }
            },
            legend: {
                display: true,
                labels: {
                    color: '#333',
                    font: {
                        size: 14,
                        family: 'Montserrat'
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                titleFont: {
                    family: 'Montserrat',
                    size: 16
                },
                bodyFont: {
                    family: 'Montserrat',
                    size: 14
                },
                cornerRadius: 5
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#333',
                    font: {
                        size: 12,
                        family: 'Montserrat'
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)'
                }
            },
            x: {
                ticks: {
                    color: '#333',
                    font: {
                        size: 12,
                        family: 'Montserrat'
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)'
                }
            }
        }
    }
});
</script>
</body>
</html>
