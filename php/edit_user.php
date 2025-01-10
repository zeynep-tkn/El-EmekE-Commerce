<?php
//admin panel içinde kullanıcı düzenleme sayfası
session_start();
include('../database.php');
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $query = "SELECT * FROM users WHERE id='$user_id'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen veriler
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    
    // Kullanıcıyı güncelleme
    $update_query = "UPDATE users SET username='$username', email='$email', role='$role' WHERE id='$user_id'";
    if (mysqli_query($conn, $update_query)) {
        header("Location: admin_dashboard.php"); // Başarıyla güncellendikten sonra admin paneline yönlendir
        exit();
    } else {
        echo "Bir hata oluştu.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Düzenle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        h1 {
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }
        input[type="text"],
        input[type="email"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color:rgb(155, 10, 109) ;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color:rgb(155, 10, 109) ;
        }
    </style>
</head>
<body>
    
    <form action="edit_user.php?id=<?php echo $user['id']; ?>" method="POST">
    <h1>Kullanıcı Düzenle</h1>    
    <label for="username">Kullanıcı Adı:</label>
        <input type="text" name="username" value="<?php echo $user['username']; ?>" required><br>

        <label for="email">E-posta:</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>

        <label for="role">Rol:</label>
        <select name="role" required>
            <option value="customer" <?php echo ($user['role'] == 'customer') ? 'selected' : ''; ?>>Müşteri</option>
            <option value="seller" <?php echo ($user['role'] == 'seller') ? 'selected' : ''; ?>>Satıcı</option>
            <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
        </select><br>

        <button type="submit">Güncelle</button>
    </form>
</body>
</html>