<?php
//veri tabanı bağlantısı
$servername="localhost";
$username ="root";
$password ="";
$dbname="user_registration";

//Bağlantıyı oluştur
$conn =new mysqli($servername,$username,$password,$dbname);

//Bağlantı kontrol
if ($conn->connect_error){
    die("Bağlantı hatası: " . $conn->connect_error);
}

?>