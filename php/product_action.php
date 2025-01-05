<?php
//veri tabanına ürün ekleme 
session_start();
include('../database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: login.php");
    exit();
}

$seller_user_id = $_SESSION['user_id'];

// Satıcı ID kontrolü
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

if (isset($_POST['add_product'])) {
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $product_price = isset($_POST['product_price']) ? $conn->real_escape_string($_POST['product_price']) : 0;
    $product_stock = isset($_POST['product_stock']) ? $conn->real_escape_string($_POST['product_stock']) : 0;

    // Yükleme dizini kontrolü
    $upload_dir = '../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Dosya yükleme işlemi
    $product_image = null;
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp = $_FILES['product_image']['tmp_name'];
        $image_name = uniqid() . "_" . basename($_FILES['product_image']['name']);
        $image_path = $upload_dir . $image_name;

        if (move_uploaded_file($image_tmp, $image_path)) {
            $product_image = $image_name;
        } else {
            die("Dosya yüklenirken bir hata oluştu.");
        }
    }

    $query = "INSERT INTO Urun (Urun_Adi, Urun_Fiyati, Stok_Adedi, Urun_Gorseli, Satici_ID) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sdisi", $product_name, $product_price, $product_stock, $product_image, $satici_id);

    if ($stmt->execute()) {
        header("Location: product_management.php");
        exit();
    } else {
        die("Ürün ekleme sırasında bir hata oluştu: " . $conn->error);
    }
}


// Ürün düzenleme işlemi
if (isset($_POST['edit_product'])) {
    $product_id = $conn->real_escape_string($_POST['product_id']);
    header("Location: edit_product.php?product_id=$product_id");
    exit();
}

// Ürün silme işlemi
if (isset($_POST['delete_product'])) {
    $product_id = $conn->real_escape_string($_POST['product_id']);

    // Ürün bilgilerini çek
    $query = "SELECT Urun_Gorseli FROM Urun WHERE Urun_ID = ? AND Satici_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $product_id, $seller_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $product_image = $product['Urun_Gorseli'];

        // Ürün silme sorgusu
        $delete_query = "DELETE FROM Urun WHERE Urun_ID = ? AND Satici_ID = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("ii", $product_id, $seller_id);

        if ($delete_stmt->execute()) {
            // Görseli sil
            if ($product_image && file_exists("../uploads/" . $product_image)) {
                unlink("../uploads/" . $product_image);
            }
            header("Location: product_management.php");
            exit();
        } else {
            die("Ürün silinirken bir hata oluştu: " . $conn->error);
        }
    } else {
        die("Ürün bulunamadı veya silme yetkiniz yok.");
    }
}

// Eğer buraya ulaşıldıysa, yanlış bir istek yapılmış demektir.
header("Location: product_management.php");
exit();
?>