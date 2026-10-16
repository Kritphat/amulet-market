<?php
require 'config.php';

// Self-contained password-reset flow (request -> set new password -> done).
// NOTE: intentionally a MOCK — it never writes to the database, so it cannot
// be used to reset the admin account and shortcut the challenge.
$view  = 'request';
$error = '';
$acct  = trim($_POST['account'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $step = $_POST['step'] ?? 'request';
    if ($step === 'request') {
        $view = 'reset';                       // simulate clicking the emailed link
    } elseif ($step === 'reset') {
        $p1 = $_POST['pw'] ?? '';
        $p2 = $_POST['pw2'] ?? '';
        if (strlen($p1) < 8) {
            $error = 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร'; $view = 'reset';
        } elseif ($p1 !== $p2) {
            $error = 'รหัสผ่านไม่ตรงกัน'; $view = 'reset';
        } else {
            $view = 'done';                    // success (not persisted)
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>รีเซ็ตรหัสผ่าน | ตลาดพระออนไลน์</title>
  <link rel="stylesheet" href="/assets/app.css">
  <style>body{background:var(--bg);display:grid;place-items:center;min-height:100vh}</style>
</head>
<body>
  <div style="width:100%;max-width:400px;padding:24px">
    <div class="card"><div class="sec" style="padding:32px">
      <div class="brand">
        <span class="logo"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c-1.3 1.9-1.9 3.8-1.3 5.7C9 6.9 7.3 6.6 5.4 7c.3 1.9 1.4 3.5 3 4.6-1.9.2-3.6 1-5 2.4 2.3 2.6 5.3 3.9 8.6 3.9s6.3-1.3 8.6-3.9c-1.4-1.4-3.1-2.2-5-2.4 1.6-1.1 2.7-2.7 3-4.6-1.9-.4-3.6-.1-5.3.7.6-1.9 0-3.8-1.3-5.7z"/></svg></span>
        <div>ตลาดพระ <small>เข้าสู่ระบบสมาชิก</small></div>
      </div>

      <?php if ($view === 'request'): ?>
        <h1 style="font-size:21px;margin:18px 0 6px">รีเซ็ตรหัสผ่าน</h1>
        <p class="muted" style="font-size:14px;margin:0 0 22px">กรอกชื่อผู้ใช้สมาชิกของคุณ ระบบจะส่งขั้นตอนการรีเซ็ตไปยังอีเมลที่ลงทะเบียนไว้</p>
        <form method="POST">
          <input type="hidden" name="step" value="request">
          <label>ชื่อผู้ใช้สมาชิก</label>
          <div class="field">
            <span class="ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 4-6 8-6s8 2 8 6"/></svg></span>
            <input type="text" name="account" placeholder="เช่น firstname.lastname" value="<?= htmlspecialchars($acct) ?>">
          </div>
          <button class="btn" type="submit">ดำเนินการต่อ</button>
        </form>
        <div style="text-align:center;margin-top:18px"><a href="/" style="color:var(--teal-d);font-size:13px;font-weight:600">← กลับไปหน้าเข้าสู่ระบบ</a></div>

      <?php elseif ($view === 'reset'): ?>
        <h1 style="font-size:21px;margin:18px 0 6px">ตั้งรหัสผ่านใหม่</h1>
        <p class="muted" style="font-size:14px;margin:0 0 20px">ยืนยันตัวตนเรียบร้อย<?= $acct !== '' ? ' สำหรับ <b>'.htmlspecialchars($acct).'</b>' : '' ?> โปรดตั้งรหัสผ่านใหม่ด้านล่าง</p>
        <div class="alert err <?= $error ? 'show' : '' ?>">⚠️ <?= htmlspecialchars($error) ?></div>
        <form method="POST">
          <input type="hidden" name="step" value="reset">
          <input type="hidden" name="account" value="<?= htmlspecialchars($acct) ?>">
          <label>รหัสผ่านใหม่</label>
          <div class="field">
            <span class="ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="11" width="16" height="9" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg></span>
            <input type="password" name="pw" placeholder="อย่างน้อย 8 ตัวอักษร">
          </div>
          <label>ยืนยันรหัสผ่านใหม่</label>
          <div class="field">
            <span class="ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="11" width="16" height="9" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg></span>
            <input type="password" name="pw2" placeholder="กรอกรหัสผ่านอีกครั้ง">
          </div>
          <button class="btn" type="submit">อัปเดตรหัสผ่าน</button>
        </form>
        <div style="text-align:center;margin-top:18px"><a href="/" style="color:var(--teal-d);font-size:13px;font-weight:600">← กลับไปหน้าเข้าสู่ระบบ</a></div>

      <?php else: /* done */ ?>
        <h1 style="font-size:21px;margin:18px 0 6px">เปลี่ยนรหัสผ่านสำเร็จ</h1>
        <div class="success">✅ เปลี่ยนรหัสผ่านเรียบร้อยแล้ว คุณสามารถเข้าสู่ระบบด้วยรหัสผ่านใหม่ได้ทันที</div>
        <a class="btn" href="/" style="display:block;text-align:center;box-sizing:border-box;text-decoration:none">กลับไปหน้าเข้าสู่ระบบ</a>
      <?php endif; ?>
    </div></div>
  </div>
</body>
</html>
