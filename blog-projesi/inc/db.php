<?php
// db.php
$host = 'localhost';
$dbname = 'blog_db'; // Buraya kendi veritabanı adını yaz
$username = 'root';
$password = 'mysql'; // Ampps kullanıyorsan şifre genelde 'mysql' olur, boşsa '' yap

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı kurulamadı: " . $e->getMessage());
}
?>