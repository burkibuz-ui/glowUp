<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'admin' && $password === '1234') {
        $_SESSION['is_admin'] = true;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Admin bilgileri hatalı";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Admin Giriş</title>

<style>
body {
    margin: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #fff0f6;
    font-family: Poppins, sans-serif;
}
.box {
    background: white;
    padding: 30px;
    width: 320px;
    border-radius: 12px;
}
.box h2 {
    text-align: center;
    color: #ff4d88;
}
.box input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
}
.box button {
    width: 100%;
    padding: 12px;
    background: #ff6fa8;
    border: none;
    color: white;
    border-radius: 8px;
}
.error {
    color: red;
    text-align: center;
}
</style>
</head>

<body>
<div class="box">
    <h2>🔒 Admin Girişi</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <input name="username" placeholder="Admin kullanıcı adı">
        <input type="password" name="password" placeholder="Admin şifre">
        <button>Giriş Yap</button>
    </form>
</div>
</body>
</html>
