<?php
require 'db.php';

try {
    $stmt = $pdo->query("SELECT 1");
    echo "✅ Veritabanı bağlantısı başarılı!";
} catch (PDOException $e) {
    echo "❌ Hata: " . $e->getMessage();
}
?>
