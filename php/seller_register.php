<?php
// seller_register.php
include('../database.php'); // Veritabanı bağlantısını dahil et
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $store_name = mysqli_real_escape_string($conn, $_POST['store_name']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Şifreyi hashle
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $status = 1; // Otomatik doğrulama için aktif durumu

    // Aynı e-posta ile kayıtlı kullanıcı var mı kontrol et
    $check_query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        $error = "Bu e-posta zaten kullanılıyor.";
    } else {
        // `users` tablosuna ekle ve role'ü seller olarak ayarla
        $user_query = "INSERT INTO users (username, email, password, role) VALUES ('$name', '$email', '$password', 'seller')";
        
        // users tablosuna ekleme
        if (mysqli_query($conn, $user_query)) {
            $user_id = mysqli_insert_id($conn);
            if ($user_id) {
                // satici tablosuna ekleme
                $tckn_vkn = null;  // Boş bir değer gönder
                $seller_query = "INSERT INTO satici (User_ID, TCKN_VKN, Magaza_Adi, Ad_Soyad, Tel_No, Eposta, Adres, HesapDurumu) 
                                 VALUES ($user_id, NULL, '$store_name', '$name', '$phone', '$email', '$address', $status)";
                if (mysqli_query($conn, $seller_query)) {
                    // Kayıt başarılı, login sayfasına yönlendir
                    header("Location: login.php");
                    exit();
                } else {
                    die("Satıcı kaydı sırasında hata oluştu: " . mysqli_error($conn));
                }
            } else {
                die("Hata: Kullanıcı ID alınamadı.");
            }
        } else {
            die("Kullanıcı kaydı sırasında hata oluştu: " . mysqli_error($conn));
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Satıcı Kayıt</title>
    <style>
       body {
            font-family: Arial, sans-serif;
            background: url('../images/index.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            box-sizing: border-box;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color:rgb(155, 10, 109) ;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color:rgb(155, 10, 109) ;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Satıcı Kayıt Formu</h1>
        <form action="seller_register.php" method="POST">
            <label for="store_name">Mağaza Adı:</label>
            <input type="text" name="store_name" id="store_name" required>

            <label for="name">Ad Soyad:</label>
            <input type="text" name="name" id="name" required>

            
            <label for="phone">Telefon Numarası:</label>
            <input type="text" name="phone" id="phone" required>

            <label for="email">E-posta:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Şifre:</label>
            <input type="password" name="password" id="password" required>

            <label for="address">Adres:</label>
            <textarea name="address" id="address" required></textarea>

            <button type="submit">Kayıt Ol</button>
        </form>
    </div>
</body>
</html>
