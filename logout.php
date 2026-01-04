<?php
// logout.php
require_once 'db.php'; // session başlatılmış mı diye kontrol ediyoruz (db.php session_start() yapıyorsa tekrar çağırmak sorun olmaz)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tüm oturum verilerini temizle
$_SESSION = [];

// Eğer oturum çerezi varsa sil
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Oturumu tamamen yok et
session_unset();
session_destroy();

// Güvenlik için yeni bir boş oturum id'si oluştur (opsiyonel, ama iyi uygulama)
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
session_regenerate_id(true);

// Ana sayfaya yönlendir
header('Location: index.php');
exit;
