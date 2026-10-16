<?php
require 'config.php';
if (empty($_SESSION['staff'])) { header('Location: index.php'); exit; }
// Customers (storefront-only) may not access staff pages.
if (($_SESSION['role'] ?? '') === 'customer') { header('Location: shop.php'); exit; }

$file = $_GET['file'] ?? '';

// --- "Sanitizer" (intentionally weak, Stage 2 intermediate bump) -------------
// Strips "../" ONCE and non-recursively, so a payload like `....//` collapses
// back into `../` after replacement.  (Vulnerability: non-recursive filtering
// that fails to re-scan the result.)
$safe = str_replace('../', '', $file);

$path    = __DIR__ . '/documents/' . $safe;   // path traversal sink
$exists  = is_file($path);
$content = $exists ? file_get_contents($path) : null;   // reads traversed file
$display = $safe !== '' ? $safe : '(none)';
$active  = 'docs';
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars(basename($display)) ?> · ตลาดพระออนไลน์</title>
  <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
  <?php include 'partials/staff_top.php'; ?>

  <main class="viewer">
    <div class="crumbs"><a href="dashboard.php">เอกสาร</a> &nbsp;/&nbsp; <?= htmlspecialchars(basename($display)) ?></div>
    <div class="doc">
      <div class="docbar">
        <span class="ft"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h10l6 6v10a2 2 0 0 1-2 2H4z"/><path d="M14 4v6h6"/></svg></span>
        <div>
          <div style="font-weight:700"><?= htmlspecialchars(basename($display)) ?></div>
          <div class="muted" style="font-size:12px"><?= htmlspecialchars($display) ?></div>
        </div>
      </div>
      <?php if ($exists): ?>
        <pre class="docbody"><?= htmlspecialchars($content) ?></pre>
      <?php else: ?>
        <div class="notfound">
          <div style="font-size:40px">🗂️</div>
          <p><b>ไม่พบไฟล์</b><br>ไม่สามารถค้นหา “<?= htmlspecialchars($display) ?>” ในคลังเอกสารได้</p>
          <a href="dashboard.php" style="color:var(--teal-d);font-weight:600">← กลับไปที่เอกสาร</a>
        </div>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>
