<?php
require 'config.php';
if (empty($_SESSION['staff'])) { header('Location: index.php'); exit; }
// Customers (storefront-only) may not access staff pages.
if (($_SESSION['role'] ?? '') === 'customer') { header('Location: shop.php'); exit; }
require 'partials/data.php';
$active = '';
$q = trim($_GET['q'] ?? '');
$ql = strtolower($q);

// Pure in-memory filtering over static arrays (no database query involved).
$docHits = $q === '' ? [] : array_values(array_filter($DOCS, fn($d) =>
    str_contains(strtolower($d[1]), $ql) || str_contains(strtolower($d[2]), $ql)));
$peopleHits = $q === '' ? [] : array_values(array_filter($STAFF_DIR, fn($s) =>
    str_contains(strtolower($s[0]), $ql) || str_contains(strtolower($s[1]), $ql) || str_contains(strtolower($s[2]), $ql)));
$total = count($docHits) + count($peopleHits);
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ค้นหา | ตลาดพระออนไลน์</title>
  <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
  <?php include 'partials/staff_top.php'; ?>
  <main class="wrap">
    <div class="pagehead">
      <div>
        <h2>ผลการค้นหา</h2>
        <p><?= $q === '' ? 'พิมพ์คำค้นหาในช่องด้านบน' : 'พบ ' . $total . ' รายการ สำหรับ “' . htmlspecialchars($q) . '”' ?></p>
      </div>
      <a class="btn sm ghost" href="/dashboard.php">← กลับหน้าแรก</a>
    </div>

    <?php if ($q !== '' && $total === 0): ?>
      <div class="card"><div class="empty"><div class="big">🔍</div><b>ไม่พบรายการที่ตรงกัน</b><br>ลองใช้คำค้นหาอื่น หรือดู <a href="/documents.php" style="color:var(--teal-d);font-weight:600">พระเครื่องทั้งหมด</a></div></div>
    <?php endif; ?>

    <?php if ($docHits): ?>
      <div class="card" style="margin-bottom:18px">
        <div class="sechead"><h3>เอกสาร</h3><span class="tag"><?= count($docHits) ?></span></div>
        <?php foreach ($docHits as $d): ?>
          <a class="file" href="view.php?file=<?= urlencode($d[0]) ?>">
            <span class="ft"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h10l6 6v10a2 2 0 0 1-2 2H4z"/><path d="M14 4v6h6"/></svg></span>
            <div><div class="fn"><?= htmlspecialchars($d[1]) ?></div><div class="fm"><?= htmlspecialchars($d[2]) ?></div></div>
            <span class="go"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg></span>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ($peopleHits): ?>
      <div class="card">
        <div class="sechead"><h3>ร้านค้า & เซียนพระ</h3><span class="tag"><?= count($peopleHits) ?></span></div>
        <table class="tbl">
          <thead><tr><th>ชื่อ</th><th>ชื่อผู้ใช้</th><th>หมวดหมู่</th><th>ติดต่อ</th></tr></thead>
          <tbody>
            <?php foreach ($peopleHits as $s): ?>
              <tr><td><b><?= htmlspecialchars($s[0]) ?></b></td><td class="mono"><?= htmlspecialchars($s[1]) ?></td><td><?= htmlspecialchars($s[2]) ?></td><td><?= htmlspecialchars($s[4]) ?></td></tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </main>
</body>
</html>
