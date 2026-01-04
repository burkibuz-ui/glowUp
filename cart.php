<?php
session_start();
require_once 'db.php';
include 'header.php';

// Sepete ürün ekleme
if (isset($_GET['add'])) {
    $id = $_GET['add'];

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

    header("Location: cart.php");
    exit;
}

// Sepetten ürün silme
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit;
}
?>

<main class="page-content">
    <div class="cart-container">
        <h2>🛍️ GlowUp Sepetim</h2>

        <?php if (!empty($_SESSION['cart'])): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Ürün</th>
                        <th>Fiyat</th>
                        <th>Adet</th>
                        <th>Toplam</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $id => $item):
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= number_format($item['price'], 2) ?> TL</td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= number_format($subtotal, 2) ?> TL</td>
                        <td><a href="cart.php?remove=<?= $id ?>" class="btn-remove">Sil</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p class="cart-total">Genel Toplam: <?= number_format($total, 2) ?> TL</p>

            <div class="checkout-wrapper">
                <a href="checkout.php" class="checkout-btn">Sepeti Onayla</a>
            </div>
        <?php else: ?>
            <p class="empty-cart">Sepetiniz şu anda boş.</p>
        <?php endif; ?>
    </div>
</main>

<?php include 'footer.php'; ?>

<style>
.page-content {
    background: #fff0f7;
    min-height: 80vh;
    padding: 40px 20px;
    font-family: 'Poppins', sans-serif;
}
.cart-container {
    max-width: 950px;
    margin: 0 auto;
    background: #fff;
    border-radius: 12px;
    padding: 30px 40px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.cart-container h2 {
    text-align: center;
    color: #ff4d88;
    margin-bottom: 25px;
    font-size: 26px;
}
.cart-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 25px;
}
.cart-table th, .cart-table td {
    text-align: center;
    padding: 14px;
    border-bottom: 1px solid #f3d1e1;
    font-size: 16px;
}
.cart-table th {
    background-color: #ff6fa8;
    color: white;
    font-weight: 600;
}
.cart-table tr:hover {
    background-color: #fff8fb;
}
.btn-remove {
    background-color: #ff4d88;
    color: white;
    padding: 6px 12px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
}
.btn-remove:hover {
    background-color: #e63d77;
}
.cart-total {
    text-align: right;
    font-size: 18px;
    font-weight: 600;
    margin-top: 10px;
    color: #333;
}
.checkout-wrapper {
    text-align: center;
    margin-top: 25px;
}
.checkout-btn {
    display: inline-block;
    background-color: #ff4d88;
    color: white;
    padding: 12px 30px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
}
.checkout-btn:hover {
    background-color: #ff3575;
    transform: translateY(-2px);
}
.empty-cart {
    text-align: center;
    font-size: 18px;
    color: #888;
    padding: 30px 0;
}
</style>




