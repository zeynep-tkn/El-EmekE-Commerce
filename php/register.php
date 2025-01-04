<?php 
//Kayıt olma sayfası
include('../database.php');
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    $role = $_POST['role']; // Rol bilgisi al
    $sql = "INSERT INTO users(username, email, password) VALUES('$username','$email','$password')";

     // Veritabanına ekleme
     $query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
     if (mysqli_query($conn, $query)) {
        header("Location: http://localhost/El-Emek/php/login.php");
        exit();
    }
    else{
        echo "Hata: " . $sql . "<br>" . $conn->error;
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
            background: url('background2.jpg') no-repeat center center fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #fff;
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

        input[type="text"], input[type="email"], input[type="password"] {
            width: 80%;
            padding: 15px;
            margin: 4px -15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size:15px;
        }

        input[type="text"]{
            margin-bottom: 5px;
        }
        input[type="email"]{
            margin-top: 30px;
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
       <label for="role">Rol Seçin:</label>
          <select id="role" name="role" required>
            <option value="customer">Müşteri</option>
            <option value="seller">Satıcı</option>
            <option value="admin">Admin</option>
          </select><br>
       <button type="submit">Kayıt Ol</button>
    </form>
</div>


</body>
<html>

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
