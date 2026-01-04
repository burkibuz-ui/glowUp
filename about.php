<?php include 'header.php'; ?>
<?php include 'db.php'; ?>

<main class="about-page">
  <section class="about-hero">
    <h2>🌸 Hakkımızda</h2>
    <p>
      GlowUp Kozmetik, doğallık ve bilimi bir araya getirerek cilt bakımını bir yaşam tarzına dönüştürmeyi
      amaçlayan modern bir güzellik markasıdır. 2023 yılında kurulan GlowUp, her cilt tipine uygun doğal içerikli 
      ürünleriyle güzellik rutinini sadeleştirmeyi hedefler.
    </p>
    <p>
      Laboratuvarlarımızda geliştirilen ürünlerimiz dermatolojik olarak test edilmiştir. 
      Müşteri memnuniyetini ön planda tutan yaklaşımımızla, her ürünümüzün güvenilir ve etkili olmasını sağlıyoruz.
    </p>
    <p>
      GlowUp ailesi olarak, güzelliğin sadece dış görünüş değil, bir özgüven ifadesi olduğuna inanıyoruz.  
      Doğal, sade ve ışıltılı bir güzellik için buradayız 💖
    </p>
  </section>
</main>

<?php include 'footer.php'; ?>

<style>
  .about-page {
    background-color: #fff5fa;
    min-height: 80vh;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    padding: 50px 20px;
  }

  .about-hero {
    max-width: 800px;
    background: #fff;
    padding: 40px 60px;
    border-radius: 16px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(255, 111, 168, 0.2);
  }

  .about-hero h2 {
    color: #ff4d88;
    font-size: 28px;
    margin-bottom: 20px;
  }

  .about-hero p {
    color: #555;
    font-size: 16px;
    line-height: 1.8;
    margin-bottom: 15px;
  }

  .about-hero p:last-child {
    font-weight: 600;
    color: #ff4d88;
  }
</style>


