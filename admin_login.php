<?php
session_start();
require_once 'db.php';

// Zaten girişliyse dashboard'a
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($username === "" || $password === "") {
        $error = "Kullanıcı adı ve şifre zorunlu.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin["password"])) {
            $_SESSION["admin_logged_in"] = true;
            $_SESSION["admin_username"] = $admin["username"];
            header("Location: admin_dashboard.php");
            exit;
        } else {
            $error = "Kullanıcı adı veya şifre yanlış";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Admin Girişi</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body{ background:#fff0f6; font-family:'Poppins',sans-serif; }
    .wrap{ min-height:100vh; display:flex; align-items:center; justify-content:center; padding:30px; }
    .card{
      width:460px; background:#fff; border-radius:14px; padding:28px;
      box-shadow:0 6px 18px rgba(0,0,0,.08);
    }
    h2{ text-align:center; color:#ff4d88; margin:0 0 18px; }
    .error{ color:#b00020; text-align:center; margin-bottom:12px; font-weight:600; }
    input{
      width:100%; padding:12px 14px; margin:10px 0;
      border:1px solid #f1c6d8; border-radius:10px; outline:none;
    }
    button{
      width:100%; padding:12px 14px; border:none; border-radius:10px;
      background:linear-gradient(90deg,#ff66a3,#ff4d88);
      color:#fff; font-weight:700; cursor:pointer; margin-top:8px;
    }
    button:hover{ opacity:.95; }
    .mini{ text-align:center; margin-top:12px; color:#777; font-size:13px; }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h2>🔒 Admin Girişi</h2>
      <?php if($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

      <form method="POST" action="">
        <input type="text" name="username" placeholder="Admin kullanıcı adı" autocomplete="username">
        <input type="password" name="password" placeholder="Admin şifre" autocomplete="current-password">
        <button type="submit">Giriş Yap</button>
      </form>

      <div class="mini">GlowUp Admin Panel</div>
    </div>
  </div>
</body>
</html>

