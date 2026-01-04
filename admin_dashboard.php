<?php
session_start();
require 'db.php';

/* ADMIN KORUMA */
if (!isset($_SESSION['is_admin'])) {
    header("Location: admin_login.php");
    exit;
}

/* ÜRÜNLERİ ÇEK */
$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

/* SİPARİŞLERİ ÇEK (STATUS DAHİL) */
$orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

/* ÜRÜN EKLE */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $price = floatval($_POST['price']);

    $stmt = $pdo->prepare("
        INSERT INTO products (name, description, price, image)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([
        $_POST['name'],
        $_POST['description'],
        $price,
        $_POST['image']
    ]);

    header("Location: admin_dashboard.php");
    exit;
}

/* ÜRÜN SİL */
if (isset($_POST['delete_product'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_POST['product_id']]);

    header("Location: admin_dashboard.php");
    exit;
}

/* SİPARİŞ İPTAL */
if (isset($_POST['cancel_order'])) {
    $stmt = $pdo->prepare("UPDATE orders SET status='cancelled' WHERE id = ?");
    $stmt->execute([$_POST['order_id']]);

    header("Location: admin_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Admin Panel</title>

<style>
body {
    margin: 0;
    font-family: Poppins, sans-serif;
    background: #fff0f6;
}
.container {
    max-width: 1100px;
    margin: 40px auto;
}
.card {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 40px;
}
h2 {
    color: #ff4d88;
    margin-bottom: 15px;
}
input {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
}
button {
    background: #ff6fa8;
    border: none;
    color: white;
    padding: 8px 14px;
    border-radius: 6px;
    cursor: pointer;
}
.btn-delete {
    background: #ff4d88;
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 10px;
    border-bottom: 1px solid #eee;
    text-align: center;
}
th {
    background: #ff6fa8;
    color: white;
}
.badge {
    padding: 4px 8px;
    border-radius: 6px;
    color: white;
    font-size: 12px;
}
.pending {
    background: #f0ad4e;
}
.cancelled {
    background: #d9534f;
}
</style>
</head>

<body>

<div class="container">

<!-- ÜRÜN EKLE -->
<div class="card">
<h2>➕ Ürün Ekle</h2>
<form method="POST">
    <input name="name" placeholder="Ürün adı" required>
    <input name="description" placeholder="Açıklama" required>
    <input type="number" step="0.01" name="price" placeholder="Fiyat (örn: 129.90)" required>
    <input name="image" placeholder="Görsel yolu (assets/uploads/ruj.jpg)" required>
    <button name="add_product">Ürün Ekle</button>
</form>
</div>

<!-- ÜRÜNLER -->
<div class="card">
<h2>🛍️ Ürünler</h2>
<table>
<tr>
    <th>ID</th>
    <th>Ad</th>
    <th>Fiyat</th>
    <th>İşlem</th>
</tr>

<?php foreach ($products as $p): ?>
<tr>
    <td><?= $p['id'] ?></td>
    <td><?= htmlspecialchars($p['name']) ?></td>
    <td><?= number_format($p['price'], 2) ?> TL</td>
    <td>
        <form method="POST">
            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
            <button name="delete_product" class="btn-delete">Sil</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>

</table>
</div>

<!-- SİPARİŞLER -->
<div class="card">
<h2>📦 Gelen Siparişler</h2>
<table>
<tr>
    <th>ID</th>
    <th>Ad Soyad</th>
    <th>Toplam</th>
    <th>Durum</th>
    <th>İşlem</th>
</tr>

<?php foreach ($orders as $o): ?>
<tr>
    <td><?= $o['id'] ?></td>
    <td><?= htmlspecialchars($o['name']) ?></td>
    <td><?= number_format($o['total_price'], 2) ?> TL</td>

    <td>
        <?php if ($o['status'] === 'pending'): ?>
            <span class="badge pending">Beklemede</span>
        <?php else: ?>
            <span class="badge cancelled">İptal Edildi</span>
        <?php endif; ?>
    </td>

    <td>
        <?php if ($o['status'] === 'pending'): ?>
        <form method="POST" onsubmit="return confirm('Sipariş iptal edilsin mi?');">
            <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
            <button name="cancel_order" class="btn-delete">İptal Et</button>
        </form>
        <?php else: ?>
            —
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>

</table>
</div>

</div>
</body>
</html>

