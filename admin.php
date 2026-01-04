<?php
session_start();
require_once 'db.php';
include 'header.php';

/* ===============================
   ADMIN KORUMA
================================ */
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit;
}

/* ===============================
   SİPARİŞ SİLME (POST)
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: admin_orders.php");
    exit;
}

/* ===============================
   SİPARİŞLERİ ÇEK
================================ */
$stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Admin | Siparişler</title>

<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: #fff0f7;
}

.page-content {
    min-height: 80vh;
    padding: 40px 20px;
}

.admin-container {
    max-width: 1000px;
    margin: 0 auto;
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.admin-container h2 {
    text-align: center;
    color: #ff4d88;
    margin-bottom: 25px;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.admin-table th,
.admin-table td {
    padding: 12px;
    border-bottom: 1px solid #f3d1e1;
    text-align: center;
    font-size: 14px;
}

.admin-table th {
    background: #ff6fa8;
    color: #fff;
    font-weight: 600;
}

.admin-table tr:hover {
    background: #fff6fa;
}

.btn-delete {
    background: #ff4d88;
    color: white;
    border: none;
    padding: 6px 14px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
}

.btn-delete:hover {
    background: #e63d77;
}

.empty-text {
    text-align: center;
    color: #999;
    font-size: 16px;
    margin-top: 30px;
}
</style>
</head>

<body>

<main class="page-content">
    <div class="admin-container">
        <h2>📦 Gelen Siparişler</h2>

        <?php if (!empty($orders)): ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ad Soyad</th>
                    <th>Telefon</th>
                    <th>Adres</th>
                    <th>Toplam</th>
                    <th>Tarih</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['name']) ?></td>
                    <td><?= htmlspecialchars($order['phone']) ?></td>
                    <td><?= htmlspecialchars($order['address']) ?></td>
                    <td><?= number_format($order['total_price'], 2) ?> TL</td>
                    <td><?= date("d.m.Y H:i", strtotime($order['created_at'])) ?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Bu siparişi silmek istiyor musun?');">
                            <input type="hidden" name="delete_id" value="<?= $order['id'] ?>">
                            <button type="submit" class="btn-delete">Sil</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p class="empty-text">Henüz sipariş bulunmuyor.</p>
        <?php endif; ?>
    </div>
</main>

<?php include 'footer.php'; ?>

</body>
</html>

