<?php
require 'config.php';
require 'partials/data.php';
// Storefront — only members (logged-in) may browse พระเครื่อง.
$loggedIn = !empty($_SESSION['staff']);
$active = 'shop';
$q  = trim($_GET['q'] ?? '');
$ql = strtolower($q);
$items = ($q === '' ? $PRODUCTS : array_filter($PRODUCTS, fn($p) =>
    str_contains(strtolower($p[1]), $ql) || str_contains(strtolower($p[2]), $ql)));
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>พระเครื่องทั้งหมด | ตลาดพระออนไลน์</title>
  <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
  <?php if ($loggedIn) include 'partials/staff_top.php'; else include 'partials/guest_top.php'; ?>

  <main class="wrap">

  <?php if (!$loggedIn): ?>
    <!-- Guests cannot view amulets: members-only gate -->
    <section class="banner">
      <div>
        <h2>ตลาดกลางพระเครื่องแท้ ซื้อ-ขายปลอดภัย</h2>
        <p>สมัครสมาชิกหรือเข้าสู่ระบบเพื่อดูพระเครื่องทั้งหมดและสั่งซื้อ</p>
      </div>
      <a class="btn" style="width:auto;margin:0;background:#fff;color:var(--maroon)" href="/register.php">สมัครสมาชิกฟรี</a>
    </section>

    <div class="card" style="margin-top:22px"><div class="empty" style="padding:56px 24px">
      <div class="big">🔒</div>
      <b style="font-size:17px">เฉพาะสมาชิกเท่านั้น</b>
      <p class="muted" style="max-width:420px;margin:10px auto 20px">
        การเข้าชมพระเครื่องทั้งหมดสงวนไว้สำหรับสมาชิกที่เข้าสู่ระบบแล้ว
        โปรดสมัครสมาชิก (ฟรี) หรือเข้าสู่ระบบเพื่อดูรายการพระและราคา
      </p>
      <a class="btn" style="width:auto;display:inline-block;text-decoration:none" href="/register.php">สมัครสมาชิก</a>
      &nbsp;
      <a class="btn ghost" style="width:auto;display:inline-block;text-decoration:none" href="/">เข้าสู่ระบบ</a>
    </div></div>

  <?php else: ?>
    <section class="banner">
      <div>
        <h2>พระเครื่องทั้งหมด</h2>
        <p>เลือกชมพระเครื่องยอดนิยม · สั่งซื้อและขอใบรับประกันพระแท้ได้ทันที</p>
      </div>
    </section>

    <div class="pagehead" style="margin-top:22px">
      <div>
        <h2>รายการพระเครื่อง</h2>
        <p><?= $q === '' ? 'ทั้งหมด ' . count($items) . ' รายการ' : 'พบ ' . count($items) . ' รายการ สำหรับ “' . htmlspecialchars($q) . '”' ?></p>
      </div>
      <?php if ($q !== ''): ?><a class="btn sm ghost" href="/shop.php">ล้างการค้นหา</a><?php endif; ?>
    </div>

    <?php if (!$items): ?>
      <div class="card"><div class="empty"><div class="big">🔍</div><b>ไม่พบพระเครื่องที่ตรงกัน</b><br>ลองใช้คำค้นหาอื่น</div></div>
    <?php else: ?>
      <div class="grid products">
        <?php foreach ($items as $i => $p): ?>
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
    <?php endif; ?>
  <?php endif; ?>

  </main>
</body>
</html>
