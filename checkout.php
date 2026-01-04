<?php
session_start();
require_once 'db.php';
include 'header.php';

// Siparişi kaydetme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $total = 0;

    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, name, phone, address) VALUES (NULL, ?, ?, ?, ?)");
        $stmt->execute([$total, $name, $phone, $address]);

        $_SESSION['cart'] = []; // Sepeti sıfırla
        header("Location: success.php");
        exit;
    } else {
        $message = "Sepetiniz boş, sipariş verilemez.";
    }
}
?>

<main class="page-content">
    <div class="checkout-container">
        <h2>💳 Ödeme Sayfası</h2>

        <?php if(isset($message)): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST" class="checkout-form">
            <label>Ad Soyad</label>
            <input type="text" name="name" placeholder="Adınızı ve soyadınızı girin" required>

            <label>Telefon</label>
            <input type="text" name="phone" placeholder="05XX XXX XX XX" required>

            <label>Adres</label>
            <textarea name="address" rows="4" placeholder="Teslimat adresinizi girin" required></textarea>

            <button type="submit">Siparişi Tamamla</button>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>

<style>
.page-content {
    min-height: 80vh;
    background: #fff0f7;
    padding: 40px 20px;
    font-family: 'Poppins', sans-serif;
}
.checkout-container {
    max-width: 600px;
    margin: 0 auto;
    background: white;
    border-radius: 12px;
    padding: 30px 40px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.checkout-container h2 {
    text-align: center;
    color: #ff4d88;
    margin-bottom: 25px;
    font-size: 26px;
    font-weight: 700;
}
.checkout-form label {
    font-weight: 600;
    color: #444;
    margin-bottom: 5px;
    display: block;
}
.checkout-form input,
.checkout-form textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 15px;
    margin-bottom: 18px;
    transition: 0.2s;
}
.checkout-form input:focus,
.checkout-form textarea:focus {
    border-color: #ff6fa8;
    outline: none;
    box-shadow: 0 0 5px rgba(255, 111, 168, 0.3);
}
.checkout-form button {
    width: 100%;
    background: #ff6fa8;
    color: white;
    border: none;
    padding: 12px 0;
    border-radius: 8px;
    font-size: 17px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}
.checkout-form button:hover {
    background: #ff4d88;
    transform: translateY(-2px);
}
.message {
    text-align: center;
    color: #ff4d88;
    font-weight: 600;
    margin-bottom: 15px;
}
</style>

