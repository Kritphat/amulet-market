<?php
require 'config.php';
if (empty($_SESSION['staff'])) { header('Location: index.php'); exit; }
// Customers (storefront-only) may not access staff pages.
if (($_SESSION['role'] ?? '') === 'customer') { header('Location: shop.php'); exit; }
require 'partials/data.php';
$active = 'docs';
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>พระเครื่อง | ตลาดพระออนไลน์</title>
  <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
  <?php include 'partials/staff_top.php'; ?>
  <main class="wrap">
    <div class="pagehead">
      <div><h2>พระเครื่อง & เอกสาร</h2><p>พระเครื่องแนะนำและคู่มือสำหรับสมาชิก</p></div>
      <a class="btn sm ghost" href="/dashboard.php">← กลับหน้าแรก</a>
    </div>

    <div class="grid products">
      <?php foreach ($PRODUCTS as $i => $p): ?>
        <a class="card product" href="/product.php?id=<?= $i ?>">
          <div class="pimg">
            <?php if ($p[4] === 'จองแล้ว'): ?><span class="ribbon">จองแล้ว</span><?php endif; ?>
            <img src="/assets/img/<?= htmlspecialchars($p[0]) ?>" alt="<?= htmlspecialchars($p[1]) ?>" onerror="this.style.display='none'">
          </div>
          <div class="pbody">
            <div class="pname"><?= htmlspecialchars($p[1]) ?></div>
            <div class="ptemple"><?= htmlspecialchars($p[2]) ?></div>
            <div class="pfoot">
              <span class="price"><?= htmlspecialchars($p[3]) ?></span>
              <span class="pill <?= $p[4]==='มีสินค้า'?'on':'off' ?>"><?= htmlspecialchars($p[4]) ?></span>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

    <div class="card" style="margin-top:22px">
      <div class="sechead"><h3>คู่มือ & เอกสารสมาชิก</h3><span class="muted" style="font-size:12.5px">โหลดจากโฟลเดอร์ <span class="mono">documents/</span></span></div>
      <?php foreach ($DOCS as $d): ?>
        <a class="file" href="view.php?file=<?= urlencode($d[0]) ?>">
          <span class="ft"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h10l6 6v10a2 2 0 0 1-2 2H4z"/><path d="M14 4v6h6"/></svg></span>
          <div>
            <div class="fn"><?= htmlspecialchars($d[1]) ?></div>
            <div class="fm"><?= htmlspecialchars($d[2]) ?> · <?= htmlspecialchars($d[4]) ?></div>
          </div>
          <span class="sz"><?= htmlspecialchars($d[3]) ?></span>
          <span class="go"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg></span>
        </a>
      <?php endforeach; ?>
    </div>
  </main>
</body>
</html>
