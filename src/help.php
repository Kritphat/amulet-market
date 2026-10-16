<?php
require 'config.php';
$loggedIn = !empty($_SESSION['staff']);
$active = '';
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ศูนย์ช่วยเหลือสมาชิก | ตลาดพระออนไลน์</title>
  <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
  <?php if ($loggedIn) include 'partials/staff_top.php'; ?>
  <main class="wrap">
    <div class="pagehead">
      <div><h2>ศูนย์ช่วยเหลือสมาชิก</h2><p>เราพร้อมช่วยเหลือเรื่องบัญชี การเข้าใช้งาน และการซื้อ-ขาย</p></div>
      <a class="btn sm ghost" href="<?= $loggedIn ? '/dashboard.php' : '/' ?>">← กลับ</a>
    </div>
    <div class="grid cols">
      <div class="card"><div class="sec">
        <h3 style="margin-top:0">ติดต่อเรา</h3>
        <p>📞 โทร: <b>02-000-4357</b></p>
        <p>✉️ อีเมล: <b>support@talardpra.online</b></p>
        <p>🕑 เวลาทำการ: จันทร์–ศุกร์ 09:00–18:00 น.</p>
        <p class="muted">กรณีเร่งด่วนนอกเวลาทำการ โปรดติดต่อผ่านไลน์ทางการของตลาดพระ</p>
      </div></div>
      <div class="card"><div class="sec">
        <h3 style="margin-top:0">บริการที่ใช้บ่อย</h3>
        <p><a href="/forgot.php" style="color:var(--teal-d);font-weight:600">รีเซ็ตรหัสผ่าน →</a></p>
        <p><a href="/directory.php" style="color:var(--teal-d);font-weight:600">ค้นหาร้านค้า / เซียนพระ →</a></p>
        <p><a href="/requests.php" style="color:var(--teal-d);font-weight:600">ขอใบรับประกันพระแท้ →</a></p>
      </div></div>
    </div>
  </main>
</body>
</html>
