<?php
require 'config.php';
if (empty($_SESSION['staff'])) { header('Location: index.php'); exit; }
// Customers (storefront-only) may not access staff pages.
if (($_SESSION['role'] ?? '') === 'customer') { header('Location: shop.php'); exit; }
require 'partials/data.php';
$user = $_SESSION['staff'];
$first = ucfirst(explode('.', $user)[strpos($user,'.')!==false ? 1 : 0] ?? $user);
$active = 'home';
// Stage 1 flag — visible once you hold a staff session (i.e. after the SQLi/WAF bypass).
$flag1 = getenv('FLAG_STAGE1') ?: 'LOC9{sqli_w4f_byp4ss_st4ge1}';
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>หน้าแรก | ตลาดพระออนไลน์</title>
  <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
  <?php include 'partials/staff_top.php'; ?>

  <main class="wrap">
    <section class="banner">
      <div>
        <h2>สวัสดี คุณ<?= htmlspecialchars($first) ?></h2>
        <p>คุณมีคำสั่งซื้อรอดำเนินการ 2 รายการ และมีข้อความใหม่จากผู้ขาย 1 ข้อความ</p>
      </div>
      <div class="date">วัน<?= ['Sunday'=>'อาทิตย์','Monday'=>'จันทร์','Tuesday'=>'อังคาร','Wednesday'=>'พุธ','Thursday'=>'พฤหัสบดี','Friday'=>'ศุกร์','Saturday'=>'เสาร์'][date('l')] ?><br><?= date('j') ?> <?= ['','ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'][(int)date('n')] ?> <?= date('Y')+543 ?></div>
    </section>

    <div class="card" style="margin-top:22px">
      <div class="sechead"><h3>🔓 เข้าถึงพอร์ทัลพนักงานสำเร็จ</h3><span class="pill off">Stage 1</span></div>
      <div class="sec">
        <p class="muted" style="margin:0 0 14px">คุณผ่านการยืนยันตัวตนเข้าสู่ระบบพนักงานแล้ว (SQL injection / WAF bypass) — นี่คือธงด่านที่ 1</p>
        <div class="flagcard">
          <div class="lbl">🏁 Flag (Stage 1)</div>
          <div class="flagval"><?= htmlspecialchars($flag1) ?></div>
        </div>
      </div>
    </div>

    <div class="grid stats">
      <a class="card stat" href="/documents.php">
        <div class="k t"><svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c-1.3 1.9-1.9 3.8-1.3 5.7C9 6.9 7.3 6.6 5.4 7c.3 1.9 1.4 3.5 3 4.6-1.9.2-3.6 1-5 2.4 2.3 2.6 5.3 3.9 8.6 3.9s6.3-1.3 8.6-3.9c-1.4-1.4-3.1-2.2-5-2.4 1.6-1.1 2.7-2.7 3-4.6-1.9-.4-3.6-.1-5.3.7.6-1.9 0-3.8-1.3-5.7z"/></svg></div>
        <div><div class="n">1,284</div><div class="l">พระเครื่องในตลาด</div></div>
      </a>
      <a class="card stat" href="/directory.php">
        <div class="k b"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 4-6 8-6s8 2 8 6"/></svg></div>
        <div><div class="n">312</div><div class="l">ร้านค้า & เซียนพระ</div></div>
      </a>
      <a class="card stat" href="/requests.php">
        <div class="k o"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v5l3 2"/><circle cx="12" cy="12" r="9"/></svg></div>
        <div><div class="n">2</div><div class="l">คำสั่งซื้อรอดำเนินการ</div></div>
      </a>
    </div>

    <div class="card" style="margin-top:22px">
      <div class="sechead">
        <h3>พระเครื่องแนะนำ</h3>
        <a href="/documents.php" style="color:var(--teal-d);font-size:13px;font-weight:600">ดูทั้งหมด</a>
      </div>
      <div class="grid products" style="margin:0;padding:18px 22px">
        <?php foreach (array_slice($PRODUCTS, 0, 6) as $i => $p): ?>
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
    </div>

    <div class="grid cols">
      <div class="card">
        <div class="sechead">
          <h3>คู่มือ & เอกสารสมาชิก</h3>
          <a href="/documents.php" style="color:var(--teal-d);font-size:13px;font-weight:600">ดูทั้งหมด</a>
        </div>
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

      <div class="card">
        <div class="sechead"><h3>ประกาศ</h3></div>
        <div class="ann"><span class="pin w"></span><div><div class="atxt"><b>แจ้งปิดปรับปรุงระบบ</b> ตลาดพระจะเปิดให้ดูได้อย่างเดียว เสาร์นี้ 02:00–04:00 น.</div><div class="at">2 วันก่อน · ฝ่ายไอที</div></div></div>
        <div class="ann"><span class="pin t"></span><div><div class="atxt"><b>แจ้งเตือนก่อนเปิดระบบ:</b> โปรดเปลี่ยนรหัสผ่านที่ใช้ร่วมกันหรือรหัสเก่าทั้งหมดก่อนเปิดใช้งานจริง</div><div class="at">4 วันก่อน · ทีมความปลอดภัย</div></div></div>
        <div class="ann"><span class="pin b"></span><div><div class="atxt">นโยบายการรับประกันพระแท้ฉบับใหม่ มีผลไตรมาสหน้า ดูได้ในเอกสารสมาชิก</div><div class="at">1 สัปดาห์ก่อน · ฝ่ายรับประกัน</div></div></div>
      </div>
    </div>
  </main>
</body>
</html>
