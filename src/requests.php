<?php
require 'config.php';
if (empty($_SESSION['staff'])) { header('Location: index.php'); exit; }
// Customers (storefront-only) may not access staff pages.
if (($_SESSION['role'] ?? '') === 'customer') { header('Location: shop.php'); exit; }
require 'partials/data.php';
$active = 'req';
$submitted = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted = true;
    $ref = 'OR-' . random_int(2100, 2999);   // mock reference id
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>คำสั่งซื้อของฉัน | ตลาดพระออนไลน์</title>
  <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
  <?php include 'partials/staff_top.php'; ?>
  <main class="wrap">
    <div class="pagehead">
      <div><h2>คำสั่งซื้อของฉัน</h2><p>คำสั่งซื้อ การจอง และคำขอใบรับประกันพระแท้</p></div>
      <a class="btn sm ghost" href="/dashboard.php">← กลับหน้าแรก</a>
    </div>

    <?php if ($submitted): ?>
      <div class="success">✅ คำขอ <b><?= htmlspecialchars($ref) ?></b> ถูกส่งแล้ว ทางร้านค้าได้รับการแจ้งเตือนเพื่อดำเนินการ</div>
    <?php endif; ?>

    <div class="grid cols">
      <div class="card">
        <div class="sechead"><h3>รายการล่าสุด</h3></div>
        <table class="tbl">
          <thead><tr><th>เลขที่</th><th>รายการ</th><th>วันที่</th><th>สถานะ</th></tr></thead>
          <tbody>
            <?php if ($submitted): ?>
              <tr><td class="mono"><?= htmlspecialchars($ref) ?></td><td><?= htmlspecialchars($_POST['type'] ?? 'คำสั่งซื้อ') ?></td><td><?= htmlspecialchars($_POST['dates'] ?? '—') ?></td><td><span class="pill off">รอดำเนินการ</span></td></tr>
            <?php endif; ?>
            <?php foreach ($REQUESTS as $r): ?>
              <tr>
                <td class="mono"><?= htmlspecialchars($r[0]) ?></td>
                <td><?= htmlspecialchars($r[1]) ?></td>
                <td><?= htmlspecialchars($r[2]) ?></td>
                <td><span class="pill <?= $r[3]==='อนุมัติแล้ว'?'on':'off' ?>"><?= htmlspecialchars($r[3]) ?></span></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="card formcard">
        <div class="sechead"><h3>สร้างคำขอใหม่</h3></div>
        <div class="sec">
          <form method="POST">
            <label>ประเภทคำขอ</label>
            <div class="field"><input type="text" name="type" placeholder="เช่น ขอใบรับประกันพระแท้" value="ขอใบรับประกันพระแท้"></div>
            <label>วันที่</label>
            <div class="field"><input type="text" name="dates" placeholder="เช่น 1–3 พ.ย. 2569"></div>
            <button class="btn" type="submit">ส่งคำขอ</button>
          </form>
        </div>
      </div>
    </div>
  </main>
</body>
</html>
