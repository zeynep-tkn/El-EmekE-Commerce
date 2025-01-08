<?php
// login sayfası
include('../database.php');
$error = "";
session_start();

// Form gönderimi kontrolü
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen veriler
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Veritabanında kullanıcıyı kontrol et
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    // Kullanıcı bulunduysa
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Şifre doğrulaması
        if (password_verify($password, $user['password'])) {
            // Giriş başarılı, oturum bilgilerini başlat
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Kullanıcı rolünü oturuma ekle

            // Rol tabanlı yönlendirme
            if ($user['role'] === 'admin') {
                header("Location: /El-Emek/php/admin_dashboard.php");//admin, admin_dashboard sayfasına yönlenidirilecek
            } elseif ($user['role'] === 'seller') {
                header("Location: /El-Emek/php/seller_dashboard.php");//satıcı, seller_dashboard sayfasına yönlendirilecek
            } else {
                header("Location: /El-Emek/index.php");//müşteri customer_dashboard yönlendirilecek
            }
            exit();
        } else {
            // Hatalı şifre
            $error = "Hatalı şifre. Lütfen tekrar deneyin.";
        }
    } else {
        // Kullanıcı bulunamadı
        $error = "Hatalı e-posta veya şifre. Lütfen tekrar deneyin.";
    }
}
?>


<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>

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

        .form-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 500px;
            height: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 30px;
            
        }

        .error-message{
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border:1px solid #f5c6cb;
            border-radius: 5px;
            margin-bottom: 15px;
            position: relative;
        }

        .close-btn{
            position: absolute;
            right: 5px;
            top: 2px;
            cursor: pointer;
            font-weight: bold;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 80%;
            padding: 15px;
            margin: 8px -15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size:15px;
        }

        input[type="email"]{
            margin-bottom: 20px;
        }
        input[type="password"]{
            margin-top: 30px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            margin-top: 50px;
            cursor: pointer;
            font-size: 20px;
        }

        button:hover {
            background-color: #45a049;
        }

    </style>

</head>
  <body>
  <div class="form-container">
        <h2>Giriş Yap</h2>

        <?php if (!empty($error)) : ?>
        <div class="error-message">
          <span class="close-btn">&times;</span>
          <?php echo $error; ?>
        </div>
        
        <?php endif; ?>
        <form action="login.php" id="registerForm" method="post">
            <input type="email" id="email" name="email" placeholder="E-posta" required>
            <input type="password" id="password" name="password" placeholder="Şifre" required>
            <button type="submit">Giriş Yap</button>
        </form>
        <br>
        <a href="register.php">Kayıt Ol</a>
    </div>

<script>
  
    // E-posta alanına @gmail.com eklemek için
    document.getElementById("email").addEventListener("input", function () {
        const emailInput = this;
        const gmailSuffix = "gmail.com";

        // Eğer kullanıcı @ işareti koyduysa ve henüz gmail.com eklenmemişse
        if (emailInput.value.includes("@") && !emailInput.value.includes(gmailSuffix)) {
            const parts = emailInput.value.split("@");
            emailInput.value = parts[0] + "@gmail.com"; // @'den sonraki kısmı gmail.com olarak tamamla
        }
    });


       // Hata mesajını kapatmak için çarpı butonu
       document.addEventListener("DOMContentLoaded", function () {
            var closeBtn = document.querySelector(".close-btn");
            if (closeBtn) {
                closeBtn.addEventListener("click", function () {
                    this.parentElement.style.display = "none";
                });
            }
        });

        // Form gönderildiğinde bilgileri kontrol et ve localStorage'a kaydet
        document.getElementById("loginForm").addEventListener("submit", function (event) {
            event.preventDefault(); // Formun normal şekilde gönderilmesini engeller
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            // Örnek kontrol: Doğru bilgi girilmiş mi?
            const correctEmail = "example@example.com"; // Bu kısımlar veritabanı kontrolü ile değiştirilebilir
            const correctPassword = "12345";

            if (email === correctEmail && password === correctPassword) {
                // Bilgiler doğruysa localStorage'a kaydet ve yönlendir
                localStorage.setItem("savedEmail", email);
                localStorage.setItem("savedPassword", password);
                alert("Başarıyla giriş yaptınız!");
                window.location.href = "index.php"; // Ana sayfaya yönlendirme
            } else {
                // Bilgiler yanlışsa hata mesajını göster
                document.querySelector(".error-message").style.display = "block";
            }
        });

        // Sayfa yüklendiğinde bilgileri localStorage'dan al ve formu doldur
        window.addEventListener("load", function () {
            const savedEmail = localStorage.getItem("savedEmail");
            const savedPassword = localStorage.getItem("savedPassword");

            if (savedEmail) {
                document.getElementById("email").value = savedEmail;
            }

            if (savedPassword) {
                document.getElementById("password").value = savedPassword;
            }
        });
    </script>
</body>
</html>