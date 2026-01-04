<?php
// GlowUp DB bağlantısı (MAMP uyumlu)
$host = 'localhost';
$port = '8889'; // MAMP varsayılan MySQL portu
$db   = 'glowup_db';
$user = 'root';
$pass = 'root';

try {
    // PDO bağlantısı (UTF-8 desteğiyle)
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Hataları yakala
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // fetch_assoc gibi
            PDO::ATTR_EMULATE_PREPARES => false // SQL enjeksiyon önlemi
        ]
    );
} catch (PDOException $e) {
    // Hata durumunda mesaj göster
    die("Veritabanı bağlantısı başarısız 😔: " . $e->getMessage());
}

// Oturum başlat
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>


