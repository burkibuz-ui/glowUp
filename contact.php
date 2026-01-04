<?php
require_once 'db.php';
include 'header.php';

if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    echo "<script>alert('Teşekkürler $name! Mesajınız başarıyla gönderildi 💌');</script>";
}
?>

<main class="page-content">
    <div class="contact-container">
        <h2>📞 Bize Ulaşın</h2>
        <p class="contact-subtext">Her zaman yanınızdayız 💖</p>

        <form method="POST" class="contact-form">
            <label>Ad Soyad</label>
            <input type="text" name="name" placeholder="Adınızı girin" required>

            <label>E-posta</label>
            <input type="email" name="email" placeholder="E-posta adresiniz" required>

            <label>Mesajınız</label>
            <textarea name="message" rows="5" placeholder="Mesajınızı yazın..." required></textarea>

            <button type="submit" name="submit">Mesaj Gönder</button>
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
.contact-container {
    max-width: 700px;
    margin: 0 auto;
    background: #fff;
    border-radius: 12px;
    padding: 35px 40px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    text-align: center;
}
.contact-container h2 {
    color: #ff4d88;
    font-size: 28px;
    margin-bottom: 10px;
}
.contact-subtext {
    color: #555;
    margin-bottom: 25px;
}
.contact-form {
    text-align: left;
}
.contact-form label {
    font-weight: 600;
    color: #444;
    margin-bottom: 6px;
    display: block;
}
.contact-form input,
.contact-form textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 15px;
    margin-bottom: 18px;
    transition: 0.2s;
}
.contact-form input:focus,
.contact-form textarea:focus {
    border-color: #ff6fa8;
    outline: none;
    box-shadow: 0 0 6px rgba(255, 111, 168, 0.3);
}
.contact-form button {
    display: block;
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
.contact-form button:hover {
    background: #ff4d88;
    transform: translateY(-2px);
}
</style>


