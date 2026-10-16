<?php
require 'config.php';
if (empty($_SESSION['staff'])) { header('Location: index.php'); exit; }
// Customers (storefront-only) may not access staff pages.
if (($_SESSION['role'] ?? '') === 'customer') { header('Location: shop.php'); exit; }
require 'partials/data.php';
$active = 'dir';
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ร้านค้า & เซียนพระ | ตลาดพระออนไลน์</title>
  <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
  <?php include 'partials/staff_top.php'; ?>
  <main class="wrap">
    <div class="pagehead">
      <div><h2>ร้านค้า & เซียนพระ</h2><p>รายชื่อร้านค้าและผู้เชี่ยวชาญพระเครื่องในระบบ</p></div>
      <a class="btn sm ghost" href="/dashboard.php">← กลับหน้าแรก</a>
    </div>
    <div class="card">
      <table class="tbl">
        <thead><tr><th>ชื่อ</th><th>ชื่อผู้ใช้</th><th>หมวดหมู่</th><th>ประเภท</th><th>ติดต่อ</th></tr></thead>
        <tbody>
          <?php foreach ($STAFF_DIR as $s): ?>
            <tr>
              <td><b><?= htmlspecialchars($s[0]) ?></b></td>
              <td class="mono"><?= htmlspecialchars($s[1]) ?></td>
              <td><?= htmlspecialchars($s[2]) ?></td>
              <td><?= htmlspecialchars($s[3]) ?></td>
              <td><?= htmlspecialchars($s[4]) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>
