<?php
//veri tabanı sayfamız elemekdb
$servername="localhost";
$username ="root";
$password ="";
$dbname="elemekdb";

//Bağlantıyı oluştur
$conn =new mysqli($servername,$username,$password,$dbname);

//Bağlantı kontrol
if ($conn->connect_error){
    die("Bağlantı hatası: " . $conn->connect_error);
}
//deneme kontrol

?>