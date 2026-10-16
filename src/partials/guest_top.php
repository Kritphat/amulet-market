<?php
// Public (guest) top bar for visitors who are NOT logged in.
$active = $active ?? '';
function g_active($n, $a) { return $n === $a ? 'active' : ''; }
?>
<header class="topbar">
  <div class="inner">
    <a class="brand" href="/shop.php">
      <span class="logo"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c-1.3 1.9-1.9 3.8-1.3 5.7C9 6.9 7.3 6.6 5.4 7c.3 1.9 1.4 3.5 3 4.6-1.9.2-3.6 1-5 2.4 2.3 2.6 5.3 3.9 8.6 3.9s6.3-1.3 8.6-3.9c-1.4-1.4-3.1-2.2-5-2.4 1.6-1.1 2.7-2.7 3-4.6-1.9-.4-3.6-.1-5.3.7.6-1.9 0-3.8-1.3-5.7z"/></svg></span>
      <div>ตลาดพระ <small>ศูนย์กลางพระเครื่องออนไลน์</small></div>
    </a>
    <form class="search" method="GET" action="/shop.php" role="search">
      <span class="ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg></span>
      <input type="text" name="q" placeholder="ค้นหาพระเครื่อง, วัด, รุ่น..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
    </form>
    <nav class="navlinks">
      <a class="<?= g_active('shop', $active) ?>" href="/shop.php">พระเครื่องทั้งหมด</a>
      <a href="/help.php">ช่วยเหลือ</a>
    </nav>
    <a class="btn ghost sm" style="margin-top:0" href="/">เข้าสู่ระบบ</a>
    <a class="btn sm" style="margin-top:0;width:auto" href="/register.php">สมัครสมาชิก</a>
  </div>
</header>
