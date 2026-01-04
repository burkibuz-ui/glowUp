<?php
session_start();
require 'db.php';
include 'header.php';

/* =========================
   SEPETE EKLEME (BURADA)
========================= */
if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity']++;
    } else {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $_SESSION['cart'][$id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1
            ];
        }
    }

    // SAYFADA KAL
    header("Location: products.php?added=1");
    exit;
}

/* =========================
   ÜRÜNLERİ ÇEK
========================= */
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
}
.notice {
  text-align:center;
  color:#4CAF50;
  font-weight:600;
  margin-bottom:15px;
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
.product-card p {
  font-size:14px;
  color:#666;
  min-height:40px;
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

<?php if (isset($_GET['added'])): ?>
  <div class="notice">✅ Ürün sepete eklendi</div>
<?php endif; ?>

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
      <p><?= htmlspecialchars($product['description'] ?? '') ?></p>
      <div class="price"><?= number_format($product['price'], 2) ?> TL</div>
      <a href="products.php?add=<?= $product['id'] ?>" class="add-btn">
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



