<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Sipariş Alındı | GlowUp</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background-color: #fff0f6;
      font-family: 'Poppins', sans-serif;
    }

    .success-container {
      max-width: 550px;
      margin: 100px auto;
      background: white;
      border-radius: 15px;
      padding: 40px 30px;
      text-align: center;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }

    .emoji {
      font-size: 50px;
      margin-bottom: 15px;
    }

    .success-container h2 {
      color: #ff4d88;
      font-size: 26px;
      margin-bottom: 15px;
    }

    .success-container p {
      color: #555;
      font-size: 16px;
      margin-bottom: 30px;
    }

    .btn-continue {
      display: inline-block;
      background: linear-gradient(90deg, #ff66a3, #ff4d88);
      color: white;
      padding: 12px 28px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: 0.3s;
    }

    .btn-continue:hover {
      transform: translateY(-2px);
      background: linear-gradient(90deg, #ff4d88, #ff66a3);
    }
  </style>
</head>
<body>

<div class="success-container">
    <div class="emoji">🎉</div>
    <h2>Siparişiniz Başarıyla Alındı!</h2>
    <p>
        Siparişiniz alınmıştır. Ürünleriniz hazırlanıyor ve en kısa sürede kargoya verilecektir 💖
    </p>
    <a href="index.php" class="btn-continue">🛍️ Alışverişe Devam Et</a>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
