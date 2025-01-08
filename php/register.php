<?php
include('../database.php'); // Veritabanı bağlantısı

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Formdan gelen rol

    // Şifreyi hashle
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Genel kullanıcı tablosuna ekleme
    $query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', '$role')";
    if (mysqli_query($conn, $query)) {
        $user_id = mysqli_insert_id($conn); // Eklenen kullanıcının ID'sini al

        // Role göre ilgili tabloya ekleme
        if ($role == 'admin') {
            $admin_query = "INSERT INTO admin (User_ID) VALUES ('$user_id')";
            mysqli_query($conn, $admin_query);
        } 
       
        elseif ($role == 'customer') {
            $customer_query = "INSERT INTO musteri (User_ID) VALUES ('$user_id')";
            mysqli_query($conn, $customer_query);
        }

        // Başarılı kayıt
        echo "Kayıt başarılı! Hoş geldiniz, $username.";
        header("Location: login.php"); // Login sayfasına yönlendirme
        exit();
    } else {
        echo "Kayıt sırasında bir hata oluştu.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>

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
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 30px;
            
        }

        input[type="text"], input[type="email"], input[type="password"], select {
            width: 80%;
            padding: 15px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 15px;
            box-sizing: border-box;
        }

        select {
            appearance: none;
            background-color: #fff;
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns%3D%27http://www.w3.org/2000/svg%27 viewBox%3D%270 0 4 5%27%3E%3Cpath fill%3D%27%23444%27 d%3D%27M2 0L0 2h4zm0 5L0 3h4z%27/%3E%3C/svg%3E');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 10px 10px;
            padding-right: 30px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            margin-top: 25px;
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
    <h2>Kayıt Formu</h2>

    <form action="register.php" method="post">
       <input type="text" name="username" id="username" placeholder="Kullanıcı Adı" required><br><br>
       <input type="email" name="email" id="email" placeholder="E-posta" required>
       <input type="password" name="password" id="password" placeholder="Şifre" required>
       <select id="role" name="role" required>
            <option value="" disabled selected>Rol Seçin</option>
            <option value="customer">Müşteri</option>
            <option value="seller">Satıcı</option>
            <option value="admin">Admin</option>
       </select><br>
       <button type="submit">Kayıt Ol</button>
    </form>
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
</script>
</body>
</html>