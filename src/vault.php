<?php
require 'config.php';
if (empty($_SESSION['staff'])) { header('Location: index.php'); exit; }
// Final flag is granted ONLY to an account whose role was escalated to GOAT.
if (($_SESSION['role'] ?? '') !== 'GOAT') { header('Location: shop.php'); exit; }
$FLAG = getenv('FLAG') ?: 'LOC9{g0at_pr1v3sc_vi4_4dm1n_r0l3_mgmt}';
$user = $_SESSION['staff'];
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GOAT Vault | ตลาดพระออนไลน์</title>
  <link rel="stylesheet" href="/assets/app.css">
  <style>body{background:var(--bg);display:grid;place-items:center;min-height:100vh;padding:24px}</style>
</head>
<body>
  <div class="card" style="width:100%;max-width:520px">
    <div class="sec" style="padding:32px">
      <div class="brand">
        <span class="logo"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c-1.3 1.9-1.9 3.8-1.3 5.7C9 6.9 7.3 6.6 5.4 7c.3 1.9 1.4 3.5 3 4.6-1.9.2-3.6 1-5 2.4 2.3 2.6 5.3 3.9 8.6 3.9s6.3-1.3 8.6-3.9c-1.4-1.4-3.1-2.2-5-2.4 1.6-1.1 2.7-2.7 3-4.6-1.9-.4-3.6-.1-5.3.7.6-1.9 0-3.8-1.3-5.7z"/></svg></span>
        <div>ตลาดพระ <small>GOAT Vault</small></div>
      </div>
      <h1 style="font-size:22px;margin:18px 0 6px">🐐 ยินดีด้วย คุณคือ GOAT</h1>
      <p class="muted" style="margin:0 0 18px">บัญชี <b><?= htmlspecialchars(ucwords(str_replace('.', ' ', $user))) ?></b> ได้รับสิทธิ์ระดับสูงสุด (GOAT) — คุณยกระดับสิทธิ์ของบัญชีตัวเองผ่านคอนโซลผู้ดูแลสำเร็จ</p>
      <div class="flagcard">
        <div class="lbl">🏁 Final Flag</div>
        <div class="flagval"><?= htmlspecialchars($FLAG) ?></div>
      </div>
      <div style="text-align:center;margin-top:18px"><a href="/logout.php" style="color:var(--teal-d);font-size:13px;font-weight:600">ออกจากระบบ</a></div>
    </div>
  </div>
</body>
</html>
