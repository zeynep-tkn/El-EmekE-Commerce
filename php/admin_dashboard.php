<?php
session_start();
include('../database.php');
//rolü admin değilse logine geç
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM users WHERE role='seller' OR role='customer'";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
</head>
<body>
    <h1>Admin Paneli</h1>
    <h2>Kullanıcılar</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Adı Soyadı</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Durum</th>
            <th>İşlem</th>
        </tr>
        <?php while ($user = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['role']; ?></td>
            <td><?php echo $user['status'] == 1 ? 'Aktif' : 'Pasif'; ?></td>
            <td>
                <a href="edit_user.php?id=<?php echo $user['id']; ?>">Düzenle</a>
                <a href="delete_user.php?id=<?php echo $user['id']; ?>">Sil</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
