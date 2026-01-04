<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Sipariş Başarılı | GlowUp</title>
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
      animation: fadeIn 0.6s ease;
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(20px);}
      to {opacity: 1; transform: translateY(0);}
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
      transition: all 0.3s ease;
    }

    .btn-continue:hover {
      transform: translateY(-2px);
      background: linear-gradient(90deg, #ff4d88, #ff66a3);
    }

    .emoji {
      font-size: 50px;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

  <div class="success-container">
    <div class="emoji">🎉</div>
    <h2>Siparişiniz Başarıyla Alındı!</h2>
    <p>Teşekkür ederiz 💖 Ürünleriniz özenle hazırlanıyor. En kısa sürede kargoya verilecektir.</p>
    <a href="index.php" class="btn-continue">🛍️ Alışverişe Devam Et</a>
  </div>

<?php include 'footer.php'; ?>
</body>
</html>
