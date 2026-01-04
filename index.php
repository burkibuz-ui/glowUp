<?php
include 'db.php';
include 'header.php';
?>

<main class="home">
  <section class="hero">
    <div class="hero-text">
      <h1>✨ Güzelliğini Parlat, <span>GlowUp</span> ile Parla!</h1>
      <p>Doğal ışıltını ortaya çıkaran kozmetik ürünleriyle tanış.  
         Her cilt için özel, her gün için mükemmel 💖</p>
      <a href="products.php" class="btn-primary">Alışverişe Başla</a>
    </div>
    <div class="hero-img">
      <img src="assets/uploads/hero.jpeg" alt="GlowUp Kozmetik">
    </div>
  </section>

  <section class="features">
    <h2>🌸 Neden GlowUp?</h2>
    <div class="feature-grid">
      <div class="feature-card">
        <h3>💅 Doğal Formüller</h3>
        <p>Cildine zarar vermeyen, dermatolojik olarak test edilmiş ürünler.</p>
      </div>
      <div class="feature-card">
        <h3>🚚 Ücretsiz Kargo</h3>
        <p>350 TL üzeri alışverişlerde kargo bizden!</p>
      </div>
      <div class="feature-card">
        <h3>💖 Parla, Güzel Hisset!</h3>
        <p>Kendini iyi hissetmen için her gün yeni ışıltılar ekliyoruz.</p>
      </div>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>

<style>
body {
  font-family: 'Poppins', sans-serif;
  background: #fff5fa;
  margin: 0;
}

/* Ana sayfa */
.home {
  text-align: center;
  padding-bottom: 40px;
}

.hero {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;
  gap: 40px;
  background: linear-gradient(90deg, #ffe2ed, #fff5fa);
  padding: 60px 20px;
}

.hero-text {
  max-width: 500px;
  text-align: left;
}

.hero-text h1 {
  font-size: 32px;
  color: #ff4d88;
  line-height: 1.3;
}

.hero-text h1 span {
  color: #ff6fa8;
  font-weight: 700;
}

.hero-text p {
  color: #555;
  font-size: 16px;
  margin: 15px 0 25px;
}

.btn-primary {
  background: #ff6fa8;
  color: white;
  padding: 12px 25px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  transition: 0.3s;
}

.btn-primary:hover {
  background: #ff4d88;
}

.hero-img img {
  width: 340px;
  border-radius: 20px;
  box-shadow: 0 6px 20px rgba(255, 111, 168, 0.3);
}

/* Özellikler bölümü */
.features {
  margin-top: 60px;
}

.features h2 {
  color: #ff4d88;
  margin-bottom: 25px;
}

.feature-grid {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 25px;
  padding: 0 20px;
}

.feature-card {
  background: white;
  border-radius: 12px;
  padding: 25px;
  width: 260px;
  box-shadow: 0 3px 10px rgba(255, 111, 168, 0.15);
  transition: 0.3s;
}

.feature-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 15px rgba(255, 111, 168, 0.25);
}

.feature-card h3 {
  color: #ff4d88;
  margin-bottom: 10px;
}
.feature-card p {
  color: #555;
  font-size: 14px;
}
</style>

