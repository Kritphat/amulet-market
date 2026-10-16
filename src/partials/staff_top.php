<?php
// Shared marketplace top bar. Expects: session with 'staff', and optional $active.
$__u    = $_SESSION['staff'] ?? '';
$__role = $_SESSION['role'] ?? 'สมาชิก';
$__ini  = strtoupper(substr($__u, 0, 1) . (strpos($__u, '.') !== false ? substr($__u, strpos($__u, '.') + 1, 1) : ''));
$active  = $active ?? '';
$__isCustomer = ($__role === 'customer');
$__homeHref   = $__isCustomer ? '/shop.php' : '/dashboard.php';
function nav_active($n, $a) { return $n === $a ? 'active' : ''; }
?>
<header class="topbar">
  <div class="inner">
    <a class="brand" href="<?= $__homeHref ?>">
      <span class="logo"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c-1.3 1.9-1.9 3.8-1.3 5.7C9 6.9 7.3 6.6 5.4 7c.3 1.9 1.4 3.5 3 4.6-1.9.2-3.6 1-5 2.4 2.3 2.6 5.3 3.9 8.6 3.9s6.3-1.3 8.6-3.9c-1.4-1.4-3.1-2.2-5-2.4 1.6-1.1 2.7-2.7 3-4.6-1.9-.4-3.6-.1-5.3.7.6-1.9 0-3.8-1.3-5.7z"/></svg></span>
      <div>ตลาดพระ <small>ศูนย์กลางพระเครื่องออนไลน์</small></div>
    </a>
    <form class="search" method="GET" action="<?= $__isCustomer ? '/shop.php' : '/search.php' ?>" role="search">
      <span class="ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg></span>
      <input type="text" name="q" placeholder="ค้นหาพระเครื่อง, ร้านค้า, หมวดหมู่..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
    </form>
    <nav class="navlinks">
      <?php if ($__isCustomer): ?>
        <a class="<?= nav_active('shop', $active) ?>" href="/shop.php">พระเครื่อง</a>
      <?php else: ?>
        <a class="<?= nav_active('home', $active) ?>" href="/dashboard.php">หน้าแรก</a>
        <a class="<?= nav_active('docs', $active) ?>" href="/documents.php">พระเครื่อง</a>
        <a class="<?= nav_active('dir',  $active) ?>" href="/directory.php">ร้านค้า</a>
        <a class="<?= nav_active('req',  $active) ?>" href="/requests.php">คำสั่งซื้อ</a>
      <?php endif; ?>
    </nav>
    <a class="userchip" href="<?= $__isCustomer ? '/shop.php' : '/directory.php' ?>" title="โปรไฟล์ของฉัน">
      <span><?= htmlspecialchars(ucwords(str_replace('.', ' ', $__u))) ?></span>
      <span class="badge"><?= htmlspecialchars($__role) ?></span>
      <span class="av"><?= htmlspecialchars($__ini) ?></span>
    </a>
    <a class="iconbtn" href="/logout.php" title="ออกจากระบบ">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
    </a>
  </div>
</header>
