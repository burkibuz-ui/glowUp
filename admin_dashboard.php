<?php
require_once 'db.php';


/**

 * admin_login.php girişte: $_SESSION['admin_logged_in'] = true; ve $_SESSION['admin_username'] = 'admin' bekliyorum.
 */
if (empty($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

$adminUsername = $_SESSION['admin_username'] ?? 'admin';

function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

/* ========= ACTIONS ========= */

// 1) Ürün ekle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name  = trim($_POST['product_name'] ?? '');
    $price = trim($_POST['product_price'] ?? '');
    $description = trim($_POST['product_description'] ?? '');
    
    $imagePath = null;

    if ($name === '' || $price === '' || !is_numeric($price)) {
        header("Location: admin_dashboard.php?err=product");
        exit;
    }

    // upload klasörü
    $uploadDir = __DIR__ . "/assets/uploads/";
    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0777, true);
    }

    // Fotoğraf seçilmişse yükle
    if (!empty($_FILES['product_image']['name'])) {
        $file = $_FILES['product_image'];

        if ($file['error'] === 0) {
            $maxSize = 5 * 1024 * 1024; // 5MB
            if ($file['size'] > $maxSize) {
                header("Location: admin_dashboard.php?err=imgsize");
                exit;
            }

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp'];
            if (!in_array($ext, $allowed, true)) {
                header("Location: admin_dashboard.php?err=imgtype");
                exit;
            }

            $safeName = "product_" . time() . "_" . rand(1000,9999) . "." . $ext;
            $targetFull = $uploadDir . $safeName;

            if (move_uploaded_file($file['tmp_name'], $targetFull)) {
                $imagePath = "assets/uploads/" . $safeName;
            }
        }
    }

    // products tablon: (id, name, price, image) varsayıyorum
    // image kolonu yoksa: sadece insert name/price yaparsın.
    try {
        // image kolonu varsa
        $stmt = $pdo->prepare("INSERT INTO products (name, description ,price, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $imagePath]);
    } catch (Exception $ex) {
        // image kolonu yoksa fallback
        $stmt = $pdo->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
        $stmt->execute([$name, $price]);
    }

    header("Location: admin_dashboard.php?ok=product_added");
    exit;
}

// 2) Ürün sil
if (isset($_GET['delete_product'])) {
    $id = (int)$_GET['delete_product'];

    // silmeden önce görseli de silelim (varsa)
    $img = null;
    try {
        $s = $pdo->prepare("SELECT image FROM products WHERE id=?");
        $s->execute([$id]);
        $img = $s->fetchColumn();
    } catch (Exception $e) {}

    $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
    $stmt->execute([$id]);

    if ($img) {
        $full = __DIR__ . "/" . $img;
        if (is_file($full)) @unlink($full);
    }

    header("Location: admin_dashboard.php?ok=product_deleted");
    exit;
}

// 3) Sipariş onay (kargoya verildi)
if (isset($_GET['approve_order'])) {
    $orderId = (int)$_GET['approve_order'];
    $stmt = $pdo->prepare("UPDATE orders SET status='shipped' WHERE id=?");
    $stmt->execute([$orderId]);
    header("Location: admin_dashboard.php?ok=order_shipped");
    exit;
}

// 4) Sipariş iptal
if (isset($_GET['cancel_order'])) {
    $orderId = (int)$_GET['cancel_order'];
    $stmt = $pdo->prepare("UPDATE orders SET status='cancelled' WHERE id=?");
    $stmt->execute([$orderId]);
    header("Location: admin_dashboard.php?ok=order_cancelled");
    exit;
}

// 5) Sipariş sil
if (isset($_GET['delete_order'])) {
    $orderId = (int)$_GET['delete_order'];
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id=?");
    $stmt->execute([$orderId]);
    header("Location: admin_dashboard.php?ok=order_deleted");
    exit;
}

// 6) Mesaj okundu
if (isset($_GET['read_msg'])) {
    $msgId = (int)$_GET['read_msg'];
    $stmt = $pdo->prepare("UPDATE contact_messages SET is_read=1 WHERE id=?");
    $stmt->execute([$msgId]);
    header("Location: admin_dashboard.php#messages");
    exit;
}

// 7) Mesaj sil
if (isset($_GET['delete_msg'])) {
    $msgId = (int)$_GET['delete_msg'];
    $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id=?");
    $stmt->execute([$msgId]);
    header("Location: admin_dashboard.php#messages");
    exit;
}

/* ========= DATA ========= */

$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

$orders = [];
try {
    // senin orders kolonların: (id, full_name/ad_soyad, phone, address, total, created_at, status) gibi.
    // O yüzden SELECT * yapıyorum.
    $orders = $pdo->query("SELECT * FROM orders ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

$messages = [];
$unreadCount = 0;
try {
    $messages = $pdo->query("SELECT * FROM contact_messages ORDER BY id DESC LIMIT 30")->fetchAll(PDO::FETCH_ASSOC);
    $unreadCount = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read=0")->fetchColumn();
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>GlowUp Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        body{
            margin:0;
            font-family: 'Poppins', sans-serif;
            background:#ffeaf3;
        }
        .topbar{
            background:#ff4d88;
            color:#fff;
            padding:12px 18px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            position:sticky;
            top:0;
            z-index:50;
        }
        .topbar .left{
            font-weight:700;
        }
        .topbar .right{
            display:flex;
            align-items:center;
            gap:14px;
            font-weight:600;
        }
        .icon-btn{
            color:#fff;
            text-decoration:none;
            display:flex;
            align-items:center;
            gap:8px;
            padding:8px 10px;
            border-radius:10px;
            background: rgba(255,255,255,0.15);
        }
        .badge{
            background:#fff;
            color:#ff4d88;
            font-weight:800;
            padding:2px 8px;
            border-radius:999px;
            font-size:12px;
        }
        .logout{
            color:#fff;
            text-decoration:none;
            padding:8px 10px;
            border-radius:10px;
            background: rgba(255,255,255,0.15);
        }

        .container{
            width:min(1100px, 92%);
            margin:22px auto 40px;
        }
        .card{
            background:rgba(255,255,255,0.85);
            border-radius:16px;
            box-shadow:0 10px 25px rgba(0,0,0,0.08);
            padding:18px;
            margin-bottom:18px;
            backdrop-filter: blur(6px);
        }
        .title{
            font-weight:800;
            color:#333;
            display:flex;
            gap:10px;
            align-items:center;
            margin:0 0 12px;
        }
        .sub{
            color:#666;
            margin:0 0 12px;
            font-size:14px;
        }

        .form-row{
            display:flex;
            gap:12px;
            flex-wrap:wrap;
            align-items:center;
        }
        .input{
            flex:1;
            min-width:180px;
            padding:12px;
            border:1px solid #ddd;
            border-radius:12px;
            outline:none;
            background:#fff;
        }
        .btn{
            border:none;
            border-radius:12px;
            padding:12px 14px;
            font-weight:800;
            cursor:pointer;
        }
        .btn-pink{ background:#ff4d88; color:#fff; }
        .btn-green{ background:#18a957; color:#fff; }
        .btn-orange{ background:#ff8a00; color:#fff; }
        .btn-red{ background:#e11d48; color:#fff; }

        table{
            width:100%;
            border-collapse:collapse;
            overflow:hidden;
            border-radius:14px;
        }
        thead{
            background:#ff4d88;
            color:#fff;
        }
        th, td{
            padding:12px 10px;
            text-align:left;
            border-bottom:1px solid rgba(0,0,0,0.06);
            font-size:14px;
        }
        tbody tr:hover{ background:rgba(255,77,136,0.06); }
        .img-thumb{
            width:48px; height:48px;
            border-radius:10px;
            object-fit:cover;
            border:1px solid #eee;
            background:#fff;
        }

        .status-pill{
            display:inline-block;
            padding:6px 10px;
            border-radius:999px;
            font-weight:800;
            font-size:12px;
            color:#111;
        }
        .st-pending{ background:#ffd6e6; color:#a11a4a; }
        .st-shipped{ background:#c7f9d4; color:#0a6a2b; }
        .st-cancelled{ background:#ffe0b5; color:#8a4b00; }

        .actions{
            display:flex;
            gap:8px;
            flex-wrap:wrap;
        }
        .small{
            padding:8px 10px;
            border-radius:10px;
            font-size:13px;
        }

        .muted{ color:#777; font-size:13px; }
        .msg-text{
            max-width:520px;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
        }

        .anchor{ scroll-margin-top:90px; }
    </style>
</head>
<body>

<div class="topbar">
    <div class="left">GlowUp Admin Panel</div>
    <div class="right">
        <a class="icon-btn" href="#messages" title="Mesajlar">
            📩 Mesajlar
            <span class="badge"><?php echo (int)$unreadCount; ?></span>
        </a>
        <div>👤 <?php echo e($adminUsername); ?></div>
        <a class="logout" href="logout.php">Çıkış</a>
    </div>
</div>

<div class="container">

    <!-- Ürün ekle -->
    <div class="card">
        <h3 class="title">➕ Ürün Ekle</h3>
        <p class="sub">Fotoğraf seçersen otomatik <b>assets/uploads</b> içine atar. (Max 5MB)</p>

        <form method="POST" enctype="multipart/form-data" class="form-row">
            <input class="input" type="text" name="product_name" placeholder="Ürün adı (örn: GlowUp Mascara)" required>
            <input class="input" type="text" name="product_price" placeholder="Fiyat (örn: 129.90)" required>
            <input class= "input" type="text" name="product_description" placeholder="Ürün Açıklaması (örn: Hacim veren ... rimel" row="3">
            <input class="input" type="file" name="product_image" accept=".jpg,.jpeg,.png,.webp">
            <button class="btn btn-pink" type="submit" name="add_product">Ekle</button>
        </form>
    </div>

    <!-- Ürünler -->
    <div class="card">
        <h3 class="title">🧴 Ürünler</h3>

        <table>
            <thead>
                <tr>
                    <th style="width:70px;">ID</th>
                    <th style="width:90px;">Görsel</th>
                    <th>Ad</th>
                    <th style="width:140px;">Fiyat</th>
                    <th style="width:140px;">İşlem</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?php echo (int)$p['id']; ?></td>
                    <td>
                        <?php
                            $img = $p['image'] ?? null;
                            if (!$img) $img = "assets/uploads/product1.png"; // yedek
                        ?>
                        <img class="img-thumb" src="<?php echo e($img); ?>" alt="">
                    </td>
                    <td><?php echo e($p['name'] ?? ''); ?></td>
                    <td><?php echo number_format((float)($p['price'] ?? 0), 2); ?> ₺</td>
                    <td>
                        <a class="btn btn-red small" href="admin_dashboard.php?delete_product=<?php echo (int)$p['id']; ?>" onclick="return confirm('Bu ürünü silmek istiyor musun?')">Sil</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($products)): ?>
                <tr><td colspan="5" class="muted">Henüz ürün yok.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Siparişler -->
    <div class="card">
        <h3 class="title">📦 Gelen Siparişler</h3>

        <table>
            <thead>
                <tr>
                    <th style="width:60px;">ID</th>
                    <th>Ad Soyad</th>
                    <th>Telefon</th>
                    <th>Adres</th>
                    <th style="width:120px;">Toplam</th>
                    <th style="width:160px;">Tarih</th>
                    <th style="width:140px;">Durum</th>
                    <th style="width:230px;">İşlem</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $o): ?>
                <?php
                    // kolon isimlerini esnek yakalayalım
                    $orderId = (int)($o['id'] ?? 0);
                    $name = $o['name'] ?? $o['full_name'] ?? $o['ad_soyad'] ?? $o['customer_name'] ?? '-';
                    $phone = $o['phone'] ?? $o['telefon'] ?? '-';
                    $address = $o['address'] ?? $o['adres'] ?? '-';
                    $total = $o['total_price'] ?? 0;

                    $created = $o['created_at'] ?? $o['tarih'] ?? $o['date'] ?? '';
                    $status = $o['status'] ?? 'pending';

                    $pillClass = 'st-pending';
                    $label = 'Beklemede';

                    if ($status === 'shipped' || $status === 'kargoya verildi') { $pillClass='st-shipped'; $label='Kargoya verildi'; }
                    if ($status === 'cancelled' || $status === 'iptal') { $pillClass='st-cancelled'; $label='İptal'; }
                ?>
                <tr>
                    <td>#<?php echo $orderId; ?></td>
                    <td><?php echo e($name); ?></td>
                    <td><?php echo e($phone); ?></td>
                    <td><?php echo e($address); ?></td>
                    <td><?php echo number_format((float)$total, 2); ?> ₺</td>
                    <td><?php echo e($created); ?></td>
                    <td><span class="status-pill <?php echo $pillClass; ?>"><?php echo e($label); ?></span></td>
                    <td>
                        <div class="actions">
                            <a class="btn btn-green small" href="admin_dashboard.php?approve_order=<?php echo $orderId; ?>">Onayla</a>
                            <a class="btn btn-orange small" href="admin_dashboard.php?cancel_order=<?php echo $orderId; ?>">İptal Et</a>
                            <a class="btn btn-red small" href="admin_dashboard.php?delete_order=<?php echo $orderId; ?>" onclick="return confirm('Siparişi tamamen silmek istiyor musun?')">Sil</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($orders)): ?>
                <tr><td colspan="8" class="muted">Henüz sipariş yok.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Mesajlar -->
    <div class="card anchor" id="messages">
        <h3 class="title">📩 Mesajlar</h3>
        <p class="sub">İletişim sayfasından gelen mesajlar burada görünür.</p>

        <table>
            <thead>
                <tr>
                    <th style="width:60px;">ID</th>
                    <th style="width:160px;">Ad</th>
                    <th style="width:220px;">E-posta</th>
                    <th>Mesaj</th>
                    <th style="width:160px;">Tarih</th>
                    <th style="width:110px;">Durum</th>
                    <th style="width:240px;">İşlem</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($messages as $m): ?>
                <?php
                    $mid = (int)$m['id'];
                    $isRead = (int)($m['is_read'] ?? 0) === 1;
                ?>
                <tr>
                    <td>#<?php echo $mid; ?></td>
                    <td><?php echo e($m['name'] ?? ''); ?></td>
                    <td><?php echo e($m['email'] ?? ''); ?></td>
                    <td class="msg-text" title="<?php echo e($m['message'] ?? ''); ?>">
                        <?php echo e($m['message'] ?? ''); ?>
                    </td>
                    <td><?php echo e($m['created_at'] ?? ''); ?></td>
                    <td>
                        <span class="status-pill <?php echo $isRead ? 'st-shipped' : 'st-pending'; ?>">
                            <?php echo $isRead ? 'Okundu' : 'Yeni'; ?>
                        </span>
                    </td>
                    <td>
                        <div class="actions">
                            <a class="btn btn-green small" href="mailto:<?php echo e($m['email'] ?? ''); ?>?subject=GlowUp%20Destek&body=Merhaba%20<?php echo urlencode($m['name'] ?? ''); ?>,%0D%0A%0D%0A">Cevapla</a>
                            <?php if (!$isRead): ?>
                                <a class="btn btn-orange small" href="admin_dashboard.php?read_msg=<?php echo $mid; ?>#messages">Okundu</a>
                            <?php endif; ?>
                            <a class="btn btn-red small" href="admin_dashboard.php?delete_msg=<?php echo $mid; ?>#messages" onclick="return confirm('Mesajı silmek istiyor musun?')">Sil</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($messages)): ?>
                <tr><td colspan="7" class="muted">Henüz mesaj yok. contact.php’den bir mesaj gönderip test et.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>


