<?php
require_once 'db.php';

if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ];
        header('Location: index.php');
        exit;
    } else {
        $error = 'E-posta veya şifre hatalı.';
    }
}

include 'header.php';
?>

<div class="login-page">
  <div class="login-box">
    <h2>🔑 GlowUp Giriş Yap</h2>

    <?php if($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
      <input type="email" name="email" placeholder="E-posta" required>
      <input type="password" name="password" placeholder="Şifre" required>
      <button type="submit">Giriş Yap</button>
    </form>

    <p>Hesabınız yok mu?</p>
    <a href="register.php" class="ghost-btn">💖 Kayıt Ol</a>
  </div>
</div>

<?php include 'footer.php'; ?>

<style>
.login-page {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 80vh;
  background: #fff5fa;
}

.login-box {
  background: white;
  padding: 35px 40px;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(255, 111, 168, 0.25);
  text-align: center;
  width: 340px;
}

.login-box h2 {
  color: #ff4d88;
  margin-bottom: 20px;
  font-size: 22px;
}

.login-box input {
  width: 100%;
  padding: 10px;
  margin: 8px 0;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 14px;
}

.login-box button {
  width: 100%;
  background: #ff6fa8;
  color: white;
  border: none;
  border-radius: 8px;
  padding: 10px;
  font-weight: 600;
  cursor: pointer;
  margin-top: 8px;
}

.login-box button:hover { background: #ff4d88; }

.ghost-btn {
  display: inline-block;
  background: white;
  color: #ff4d88;
  border: 2px solid #ff4d88;
  border-radius: 8px;
  padding: 8px 20px;
  font-weight: 600;
  text-decoration: none;
  margin-top: 10px;
}

.ghost-btn:hover {
  background: #ff4d88;
  color: white;
}

.error {
  color: #ff3b6f;
  background: #ffe6ef;
  border-radius: 6px;
  padding: 8px;
  margin-bottom: 12px;
}
</style>


