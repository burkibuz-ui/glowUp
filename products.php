<?php
require 'db.php';
include 'header.php';

// DATABASE ürünleri
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$dbProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Ürünler | GlowUp</title>
  <link rel="stylesheet" href="style.css">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fff0f6;
      margin: 0;
    }

    h2 {
      text-align: center;
      margin: 40px 0 20px;
      color: #ff4d88;
      font-size: 26px;
    }

    .search-bar {
      max-width: 400px;
      margin: 0 auto 40px;
      display: block;
      padding: 12px;
      border-radius: 8px;
      border: 1px solid #ddd;
      width: 90%;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto 60px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 30px;
      padding: 0 20px;
    }

    .product-card {
      background: #fff;
      border-radius: 14px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: 0.3s;
    }

    .product-card:hover {
      transform: translateY(-5px);
    }

    .product-card img {
      width: 100%;
      height: 220px;
      object-fit: contain;
      margin-bottom: 10px;
    }

    .price {
      font-weight: 700;
      color: #ff4d88;
      margin: 10px 0;
    }

    .add-btn {
      background: #ff6fa8;
      color: white;
      padding: 10px 20px;
      border-radius: 8px;
      text-decoration: none;
      display: inline-block;
      transition: 0.3s;
    }

    .add-btn:hover {
      background: #ff4d88;
    }
  </style>
</head>

<body>

<h2>💄 GlowUp Ürünleri</h2>

<!-- SEARCH -->
<input
  type="text"
  class="search-bar"
  placeholder="Ürün ara..."
  onkeyup="searchProduct()"
>

<div class="container" id="productContainer">

  <?php if (!empty($dbProducts)): ?>
    <?php foreach ($dbProducts as $product): ?>
      <div class="product-card">
        <img src="<?= htmlspecialchars($product['image']) ?>" alt="">
        <h3><?= htmlspecialchars($product['name']) ?></h3>
        <p><?= htmlspecialchars($product['description']) ?></p>
        <div class="price"><?= number_format($product['price'], 2) ?> TL</div>
        <a href="cart.php?add=<?= $product['id'] ?>" class="add-btn">
          Sepete Ekle
        </a>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p style="grid-column:1/-1;text-align:center;color:#999;">
      Henüz ürün eklenmemiş 💅
    </p>
  <?php endif; ?>

</div>

<?php include 'footer.php'; ?>

<script>
function searchProduct() {
  const val = document.querySelector(".search-bar").value.toLowerCase();
  document.querySelectorAll(".product-card").forEach(card => {
    const title = card.querySelector("h3").innerText.toLowerCase();
    card.style.display = title.includes(val) ? "block" : "none";
  });
}
</script>

</body>
</html>



