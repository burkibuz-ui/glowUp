<?php
require_once 'db.php'; 

$isLoggedIn = isset($_SESSION['user']) && is_array($_SESSION['user']);
$userName = $isLoggedIn && isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : null;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>GlowUp</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">

  <style>
    /* HEADER GENEL STİL */
    body { margin: 0; font-family: 'Poppins', sans-serif; }

    .site-header {
      position: sticky;
      top: 0;
      z-index: 1000;
      background: linear-gradient(90deg, #ff5fa0, #ff73b2);
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .nav {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 24px;
      color: #fff;
    }

    /* LOGO */
    .brand a {
      font-size: 22px;
      font-weight: 700;
      color: #fff;
      text-decoration: none;
      letter-spacing: 0.5px;
    }
    .brand a:hover {
      text-shadow: 0 0 8px rgba(255,255,255,0.6);
    }

    /* MENÜ */
    .links {
      display: flex;
      gap: 22px;
      flex-wrap: wrap;
    }
    .links a {
      color: #fff;
      text-decoration: none;
      font-weight: 600;
      opacity: 0.95;
      transition: 0.3s;
    }
    .links a:hover {
      opacity: 1;
      text-decoration: underline;
    }

    /* AUTH KISMI */
    .auth {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .welcome {
      font-weight: 600;
      font-size: 15px;
    }

    .btn-mini {
      background: #fff;
      color: #ff4d88;
      border: none;
      border-radius: 8px;
      padding: 6px 12px;
      font-weight: 700;
      cursor: pointer;
      text-decoration: none;
      transition: 0.3s;
    }
    .btn-mini:hover {
      background: #ffe6ef;
      transform: translateY(-2px);
    }
    .btn-mini.ghost {
      background: transparent;
      color: #fff;
      border: 1px solid rgba(255,255,255,0.7);
    }
    .btn-mini.ghost:hover {
      background: rgba(255,255,255,0.1);
    }

    /* Mobil uyum */
    @media (max-width: 768px) {
      .nav {
        flex-direction: column;
        gap: 10px;
        text-align: center;
      }
      .links { gap: 15px; }
    }
  </style>
</head>
<body>

<header class="site-header">
  <nav class="nav">
    <div class="brand">💄 <a href="index.php">GlowUp</a></div>

    <div class="links">
      <a href="index.php">Ana Sayfa</a>
      <a href="products.php">Ürünler</a>
      <a href="cart.php">Sepetim</a>
      <a href="contact.php">İletişim</a>
      <a href="about.php">Hakkımızda</a>
    </div>

    <div class="auth">
      <?php if ($isLoggedIn): ?>
        <span class="welcome">Hoş geldin, <?= htmlspecialchars($userName) ?> 💖</span>
        <a class="btn-mini ghost" href="logout.php">Çıkış</a>
      <?php else: ?>
        <a class="btn-mini ghost" href="login.php">Giriş Yap</a>
        <a class="btn-mini" href="register.php">Kayıt Ol</a>
      <?php endif; ?>
    </div>
  </nav>
</header>



