<?php
require 'config.php';
if (empty($_SESSION['staff'])) { header('Location: index.php'); exit; }
require 'partials/data.php';
$active = 'docs';

$id = isset($_GET['id']) ? (int)$_GET['id'] : -1;
$p  = ($id >= 0 && $id < count($PRODUCTS)) ? $PRODUCTS[$id] : null;
// Per-product fixed QR phrase (เหนือดวง). Empty string => use the random pool.
$qrFixed = ($p && strpos($p[1], 'เหนือดวง') !== false) ? 'แรงใดในโลกจะเท่าแรงโยกอาตมา' : '';
// Base URL the QR points at (so a phone can reach it). Empty => browser origin.
$qrBase  = rtrim(getenv('PUBLIC_BASE_URL') ?: '', '/');
$variants = $p[5] ?? null;
$noPrice  = $p ? in_array($p[3], ['โทรถาม', 'โชว์พระ', 'ไม่มีราคา'], true) : false;

$ordered = false; $ref = ''; $ovar = '';
if ($p && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ordered = true;
    $ref = 'OR-' . random_int(2100, 2999);
    if ($variants) $ovar = $_POST['variant'] ?? '';
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $p ? htmlspecialchars($p[1]) : 'ไม่พบสินค้า' ?> | ตลาดพระออนไลน์</title>
  <link rel="stylesheet" href="/assets/app.css">
  <script src="/assets/js/qrcode.min.js"></script>
  <style>
    .qr-overlay{position:fixed;inset:0;background:rgba(15,23,32,.62);backdrop-filter:blur(2px);
      display:none;place-items:center;z-index:999;padding:20px}
    .qr-overlay.show{display:grid}
    .qr-modal{width:100%;max-width:360px;background:#fff;border-radius:18px;overflow:hidden;
      box-shadow:0 24px 60px rgba(0,0,0,.35);animation:qrpop .22s ease}
    @keyframes qrpop{from{transform:translateY(12px) scale(.96);opacity:0}to{transform:none;opacity:1}}
    .qr-head{background:linear-gradient(135deg,#128c7e,#075e54);color:#fff;padding:16px 18px;
      display:flex;align-items:center;justify-content:space-between}
    .qr-head b{font-size:16px}
    .qr-close{cursor:pointer;color:#fff;font-size:22px;line-height:1;background:none;border:0}
    .qr-body{padding:22px;text-align:center}
    .qr-code{width:208px;height:208px;margin:0 auto 12px;display:grid;place-items:center;
      background:#fff;border:1px solid #e6e6e6;border-radius:12px;padding:8px}
    .qr-code img,.qr-code canvas{width:192px!important;height:192px!important}
    .qr-phrase{font-weight:700;color:#0b3d2e;font-size:16px;margin:4px 12px 2px;line-height:1.5}
    .qr-sub{color:#6b7280;font-size:12.5px;margin:0 0 4px}
  </style>
</head>
<body>
  <?php include 'partials/staff_top.php'; ?>
  <main class="wrap">
  <?php if (!$p): ?>
    <div class="card"><div class="empty"><div class="big">🗂️</div><b>ไม่พบพระเครื่ององค์นี้</b><br><a href="/shop.php" style="color:var(--teal-d);font-weight:600">← กลับไปที่ร้าน</a></div></div>
  <?php else: ?>
    <div class="crumbs"><a href="/shop.php">พระเครื่องทั้งหมด</a> &nbsp;/&nbsp; <?= htmlspecialchars($p[1]) ?></div>

    <?php if ($ordered): ?>
      <div class="success">✅ รับคำสั่งซื้อ <b><?= htmlspecialchars($ref) ?></b> แล้ว — <?= htmlspecialchars($p[1]) ?><?= $ovar ? ' (' . htmlspecialchars($ovar) . ')' : '' ?> · ทางร้านจะติดต่อกลับเพื่อยืนยันการชำระเงินและจัดส่ง</div>
    <?php endif; ?>

    <div class="detail">
      <div class="card" style="overflow:hidden">
        <div class="pimg" style="aspect-ratio:1">
          <?php if ($p[4] === 'จองแล้ว'): ?><span class="ribbon">จองแล้ว</span><?php endif; ?>
          <img id="mainimg" src="/assets/img/<?= htmlspecialchars($variants ? $variants[0][2] : $p[0]) ?>" alt="<?= htmlspecialchars($p[1]) ?>" onerror="this.style.display='none'">
        </div>
      </div>

      <div class="card"><div class="sec" style="padding:24px 26px">
        <h2 style="margin:0 0 4px;font-size:21px"><?= htmlspecialchars($p[1]) ?></h2>
        <p class="muted" style="margin:0 0 14px"><?= htmlspecialchars($p[2]) ?></p>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px">
          <span class="price" style="font-size:22px"><?php if ($variants): ?><span id="curprice"><?= htmlspecialchars($variants[0][1]) ?></span><?php else: ?><?= htmlspecialchars($p[3]) ?><?php endif; ?></span>
          <span class="pill <?= $p[4]==='มีสินค้า'?'on':'off' ?>"><?= htmlspecialchars($p[4]) ?></span>
        </div>

        <form method="POST" id="buyform">
          <?php if ($variants): ?>
            <label>เลือกเนื้อ</label>
            <div class="variants">
              <?php foreach ($variants as $k => $v): ?>
                <label class="vopt">
                  <input type="radio" name="variant" value="<?= htmlspecialchars($v[0]) ?>" data-price="<?= htmlspecialchars($v[1]) ?>" data-img="<?= htmlspecialchars($v[2] ?? $p[0]) ?>" <?= $k===0?'checked':'' ?>>
                  <span class="vname"><?= htmlspecialchars($v[0]) ?></span>
                  <span class="vprice"><?= htmlspecialchars($v[1]) ?></span>
                </label>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <button class="btn" type="submit"><?= ($noPrice && !$variants) ? 'สอบถามราคา' : 'สั่งซื้อ' ?></button>
        </form>

        <div class="dlist">
          <div class="drow"><span>🛡️ รับประกันพระแท้</span><span class="muted">ทุกองค์ผ่านการตรวจสอบ</span></div>
          <div class="drow"><span>📜 ใบรับรอง</span><span class="muted">ออกใบรับประกันให้สมาชิก</span></div>
          <div class="drow"><span>🚚 จัดส่ง</span><span class="muted">ลงทะเบียน / EMS ทั่วประเทศ</span></div>
        </div>
        <a href="/shop.php" style="color:var(--teal-d);font-weight:600;font-size:13px">← เลือกชมพระองค์อื่น</a>
      </div></div>
    </div>
  <?php endif; ?>
  </main>

  <?php if ($p && $variants): ?>
  <script>
    document.querySelectorAll('input[name=variant]').forEach(function(r){
      r.addEventListener('change', function(){
        document.getElementById('curprice').textContent = this.dataset.price;
        var img = document.getElementById('mainimg');
        if (img && this.dataset.img) { img.style.display = ''; img.src = '/assets/img/' + this.dataset.img; }
      });
    });
  </script>
  <?php endif; ?>

  <?php if ($p): ?>
  <!-- สั่งซื้อ → QR popup -->
  <div class="qr-overlay" id="qrmodal">
    <div class="qr-modal" role="dialog" aria-modal="true">
      <div class="qr-head">
        <b>สแกนเพื่อชำระเงิน</b>
        <button type="button" class="qr-close" id="qrclose" aria-label="ปิด">&times;</button>
      </div>
      <div class="qr-body">
        <div class="qr-code" id="qrbox"></div>
        <p class="qr-phrase" id="qrphrase"></p>
        <p class="qr-sub">พร้อมเพย์ · น้าค่อมการันตี ✅</p>
      </div>
    </div>
  </div>
  <script>
    (function(){
      var PHRASES = [
        "อย่างต่ำๆรุ่่นนี้ก็ 4-5 แสน",
        "องค์นี้ยังไม่ใช่พิมนิยม",
        "ร้านต่อไปรับซื้อแค่กรอบ",
        "พระเก๊คนแท้",
        "โอนมาเลยที่ กสิกร888"
      ];
      var FIXED = <?= json_encode($qrFixed, JSON_UNESCAPED_UNICODE) ?>;
      // Public Google Drive image links. QR encodes one at random, so scanning
      // opens the image directly — no dependency on this server's IP/host.
      var IMAGES = [
        'https://lh3.googleusercontent.com/d/1t2t5ktt7dB4wNfygz1fBs4FLlykSmJuV', // น้าค่อม
        'https://lh3.googleusercontent.com/d/1Y8-61rdksky_CA_Js8o7qFAyujMozbv9', // สาธุ99
        'https://lh3.googleusercontent.com/d/1HTJ45Gii1Zlna4nmclBd-iwh_bDco_Tg'  // เก๊
      ];
      var form  = document.getElementById('buyform');
      var modal = document.getElementById('qrmodal');
      var box   = document.getElementById('qrbox');
      function openQR(e){
        if (e) e.preventDefault();
        var phrase = FIXED || PHRASES[Math.floor(Math.random() * PHRASES.length)];
        document.getElementById('qrphrase').textContent = phrase;
        // Pick a random image URL for the QR.
        var url = IMAGES[Math.floor(Math.random() * IMAGES.length)];
        box.innerHTML = '';
        new QRCode(box, { text: url, width: 192, height: 192,
          colorDark: '#0b3d2e', colorLight: '#ffffff',
          correctLevel: QRCode.CorrectLevel.M });
        modal.classList.add('show');
      }
      function closeQR(){ modal.classList.remove('show'); }
      if (form) form.addEventListener('submit', openQR);
      document.getElementById('qrclose').addEventListener('click', closeQR);
      modal.addEventListener('click', function(e){ if (e.target === modal) closeQR(); });
      document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeQR(); });
    })();
  </script>
  <?php endif; ?>
</body>
</html>
