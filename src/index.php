<?php require 'config.php'; $err = $_GET['e'] ?? ''; ?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>เข้าสู่ระบบ | ตลาดพระออนไลน์</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
<div class="auth">
  <div class="hero">
    <div class="brand" style="color:#fff">
      <span class="logo"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c-1.3 1.9-1.9 3.8-1.3 5.7C9 6.9 7.3 6.6 5.4 7c.3 1.9 1.4 3.5 3 4.6-1.9.2-3.6 1-5 2.4 2.3 2.6 5.3 3.9 8.6 3.9s6.3-1.3 8.6-3.9c-1.4-1.4-3.1-2.2-5-2.4 1.6-1.1 2.7-2.7 3-4.6-1.9-.4-3.6-.1-5.3.7.6-1.9 0-3.8-1.3-5.7z"/></svg></span>
      <div>ตลาดพระ <small style="color:#e3bf6c">ศูนย์กลางพระเครื่องออนไลน์</small></div>
    </div>
    <div>
      <h2>ตลาดกลางซื้อ-ขายพระเครื่อง พระบูชา ที่เชื่อถือได้</h2>
      <p>แหล่งรวมเซียนพระและร้านค้าพระเครื่องแท้ ทุกองค์ผ่านการตรวจสอบ พร้อมใบรับประกันพระแท้ ซื้อขายปลอดภัยในที่เดียว</p>
      <ul>
        <li><span class="dot">🛡️</span> รับประกันพระแท้ทุกองค์</li>
        <li><span class="dot">🪷</span> รวมพระเครื่องยอดนิยมจากทั่วประเทศ</li>
        <li><span class="dot">⚡</span> ระบบสมาชิกเดียว ซื้อ-ขายครบวงจร</li>
      </ul>
    </div>
    <div class="foot">© 2569 ตลาดพระออนไลน์ · สงวนลิขสิทธิ์ · v2.4.1</div>
  </div>

  <div class="panel">
    <div class="loginbox">
      <div class="brand">
        <span class="logo"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c-1.3 1.9-1.9 3.8-1.3 5.7C9 6.9 7.3 6.6 5.4 7c.3 1.9 1.4 3.5 3 4.6-1.9.2-3.6 1-5 2.4 2.3 2.6 5.3 3.9 8.6 3.9s6.3-1.3 8.6-3.9c-1.4-1.4-3.1-2.2-5-2.4 1.6-1.1 2.7-2.7 3-4.6-1.9-.4-3.6-.1-5.3.7.6-1.9 0-3.8-1.3-5.7z"/></svg></span>
        <div>ตลาดพระ <small>เข้าสู่ระบบสมาชิก</small></div>
      </div>
      <h1>ยินดีต้อนรับกลับมา</h1>
      <div class="sub">เข้าสู่ระบบด้วยบัญชีสมาชิกของคุณเพื่อเริ่มซื้อ-ขาย</div>

      <div class="alert err <?= $err ? 'show' : '' ?>">⚠️ <?= htmlspecialchars($err) ?></div>

      <form method="POST" action="login.php" autocomplete="off">
        <label for="u">ชื่อผู้ใช้สมาชิก</label>
        <div class="field">
          <span class="ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 4-6 8-6s8 2 8 6"/></svg></span>
          <input id="u" type="text" name="username" placeholder="เช่น firstname.lastname">
        </div>
        <label for="p">รหัสผ่าน</label>
        <div class="field">
          <span class="ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="11" width="16" height="9" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg></span>
          <input id="p" type="password" name="password" placeholder="••••••••">
        </div>
        <div class="row-between">
          <label style="display:flex;align-items:center;gap:7px;font-weight:500;margin:0;color:var(--muted)">
            <input type="checkbox" style="width:auto"> จดจำการเข้าสู่ระบบ
          </label>
          <a href="/forgot.php" style="color:var(--teal-d);font-weight:600">ลืมรหัสผ่าน?</a>
        </div>
        <button class="btn" type="submit">เข้าสู่ระบบ</button>
      </form>

      <div style="text-align:center;margin-top:16px;font-size:13px;color:var(--muted)">
        ยังไม่มีบัญชี? <a href="/register.php" style="color:var(--teal-d);font-weight:600">สมัครสมาชิกฟรี</a>
        &nbsp;·&nbsp; <a href="/shop.php" style="color:var(--teal-d);font-weight:600">ดูพระเครื่องทั้งหมด</a>
      </div>

      <div class="authfoot">
        พบปัญหาการเข้าสู่ระบบ? ติดต่อ <a href="/help.php" style="color:var(--teal-d)">ฝ่ายช่วยเหลือสมาชิก</a> · โทร. 02-000-4357
      </div>
      <a href="/admin/" style="display:flex;align-items:center;justify-content:center;gap:8px;
         margin-top:14px;padding:11px 14px;border:1px solid var(--teal-d);border-radius:10px;
         color:var(--teal-d);font-weight:700;font-size:13.5px;text-decoration:none">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="11" width="16" height="9" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
        สำหรับผู้ดูแลระบบ — เข้าสู่คอนโซลผู้ดูแล →
      </a>
    </div>
  </div>
</div>
</body>
</html>
