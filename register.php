<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        $error = "Bu e-posta zaten kayıtlı!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);
        header("Location: login.php");
        exit;
    }
}
include 'header.php';
?>

<main class="auth-section">
  <div class="auth-card">
    <h2>💖 GlowUp Üye Ol</h2>

    <?php if(isset($error)): ?>
      <p class="error-msg"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
      <input type="text" name="name" placeholder="Ad Soyad" required>
      <input type="email" name="email" placeholder="E-posta" required>
      <input type="password" name="password" placeholder="Şifre" required>
      <button type="submit">Kayıt Ol</button>
    </form>

    <p>Zaten üye misiniz?</p>
    <a href="login.php" class="ghost-btn">🔑 Giriş Yap</a>
  </div>
</main>

<?php include 'footer.php'; ?>

<style>
.auth-section {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 80vh;
  background-color: #fff5fa;
}
.auth-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 5px 15px rgba(255, 111, 168, 0.25);
  padding: 35px 45px;
  text-align: center;
  width: 360px;
}
.auth-card h2 {
  color: #ff4d88;
  margin-bottom: 20px;
}
.auth-card input {
  width: 100%;
  padding: 10px;
  margin: 8px 0;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 14px;
}
.auth-card button {
  width: 100%;
  background-color: #ff6fa8;
  color: white;
  border: none;
  border-radius: 8px;
  padding: 10px;
  font-weight: 600;
  cursor: pointer;
  margin-top: 8px;
}
.auth-card button:hover { background-color: #ff4d88; }
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
  background-color: #ff4d88;
  color: white;
}
.error-msg {
  color: #ff3b6f;
  background: #ffe6ef;
  border-radius: 6px;
  padding: 8px;
  margin-bottom: 12px;
}
</style>


