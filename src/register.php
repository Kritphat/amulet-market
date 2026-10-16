<?php
require 'config.php';

// Real sign-up flow: creates a low-priv `staff` account that can then log in
// via login.php. Passwords are stored as md5(password) to match the existing
// login check. Username is validated and inserted via a prepared statement.
$done  = false;
$error = '';
$name  = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$user  = trim($_POST['username'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p1 = $_POST['pw'] ?? '';
    $p2 = $_POST['pw2'] ?? '';
    if ($name === '' || $email === '' || $user === '') {
        $error = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'รูปแบบอีเมลไม่ถูกต้อง';
    } elseif (!preg_match('/^[A-Za-z0-9._-]{3,50}$/', $user)) {
        $error = 'ชื่อผู้ใช้ใช้ได้เฉพาะ a-z, 0-9, . _ - และยาว 3-50 ตัวอักษร';
    } elseif (strlen($p1) < 8) {
        $error = 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร';
    } elseif ($p1 !== $p2) {
        $error = 'รหัสผ่านไม่ตรงกัน';
    } else {
        // Reject duplicate usernames.
        $chk = $mysqli->prepare('SELECT id FROM staff WHERE username = ? LIMIT 1');
        $chk->bind_param('s', $user);
        $chk->execute();
        $chk->store_result();
        if ($chk->num_rows > 0) {
            $error = 'ชื่อผู้ใช้นี้ถูกใช้งานแล้ว';
        } else {
            $hash = md5($p1);                 // match login.php's password check
            $role = 'customer';
            $ins = $mysqli->prepare(
                'INSERT INTO staff (username, password, fullname, role) VALUES (?, ?, ?, ?)'
            );
            $ins->bind_param('ssss', $user, $hash, $name, $role);
            if ($ins->execute()) {
                $done = true;
            } else {
                $error = 'เกิดข้อผิดพลาดในการสมัครสมาชิก กรุณาลองใหม่';
            }
            $ins->close();
        }
        $chk->close();
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>สมัครสมาชิก | ตลาดพระออนไลน์</title>
  <link rel="stylesheet" href="/assets/app.css">
  <style>body{background:var(--bg);display:grid;place-items:center;min-height:100vh}</style>
</head>
<body>
  <div style="width:100%;max-width:420px;padding:24px">
    <div class="card"><div class="sec" style="padding:32px">
      <div class="brand">
        <span class="logo"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c-1.3 1.9-1.9 3.8-1.3 5.7C9 6.9 7.3 6.6 5.4 7c.3 1.9 1.4 3.5 3 4.6-1.9.2-3.6 1-5 2.4 2.3 2.6 5.3 3.9 8.6 3.9s6.3-1.3 8.6-3.9c-1.4-1.4-3.1-2.2-5-2.4 1.6-1.1 2.7-2.7 3-4.6-1.9-.4-3.6-.1-5.3.7.6-1.9 0-3.8-1.3-5.7z"/></svg></span>
        <div>ตลาดพระ <small>สมัครสมาชิกใหม่</small></div>
      </div>

      <?php if ($done): ?>
        <h1 style="font-size:21px;margin:18px 0 6px">สมัครสมาชิกสำเร็จ</h1>
        <div class="success">✅ สร้างบัญชีสำหรับ <b><?= htmlspecialchars($user) ?></b> เรียบร้อยแล้ว เข้าสู่ระบบเพื่อเริ่มใช้งานได้ทันที</div>
        <p class="muted" style="font-size:13.5px;margin:0 0 6px">เลือกชมพระเครื่องและสั่งซื้อได้เลย</p>
        <a class="btn" href="/shop.php" style="display:block;text-align:center;box-sizing:border-box;text-decoration:none">ดูพระเครื่องทั้งหมด</a>
        <div style="text-align:center;margin-top:16px"><a href="/" style="color:var(--teal-d);font-size:13px;font-weight:600">ไปหน้าเข้าสู่ระบบ →</a></div>

      <?php else: ?>
        <h1 style="font-size:21px;margin:18px 0 6px">สร้างบัญชีสมาชิก</h1>
        <p class="muted" style="font-size:14px;margin:0 0 20px">สมัครฟรี เพื่อสั่งซื้อพระเครื่องและขอใบรับประกันพระแท้</p>
        <div class="alert err <?= $error ? 'show' : '' ?>">⚠️ <?= htmlspecialchars($error) ?></div>
        <form method="POST">
          <label>ชื่อ-นามสกุล</label>
          <div class="field">
            <span class="ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 4-6 8-6s8 2 8 6"/></svg></span>
            <input type="text" name="fullname" placeholder="เช่น สมชาย ใจดี" value="<?= htmlspecialchars($name) ?>">
          </div>
          <label>อีเมล</label>
          <div class="field">
            <span class="ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg></span>
            <input type="text" name="email" placeholder="you@example.com" value="<?= htmlspecialchars($email) ?>">
          </div>
          <label>ชื่อผู้ใช้</label>
          <div class="field">
            <span class="ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 4-6 8-6s8 2 8 6"/></svg></span>
            <input type="text" name="username" placeholder="เช่น firstname.lastname" value="<?= htmlspecialchars($user) ?>">
          </div>
          <label>รหัสผ่าน</label>
          <div class="field">
            <span class="ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="11" width="16" height="9" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg></span>
            <input type="password" name="pw" placeholder="อย่างน้อย 8 ตัวอักษร">
          </div>
          <label>ยืนยันรหัสผ่าน</label>
          <div class="field">
            <span class="ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="11" width="16" height="9" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg></span>
            <input type="password" name="pw2" placeholder="กรอกรหัสผ่านอีกครั้ง">
          </div>
          <button class="btn" type="submit">สมัครสมาชิก</button>
        </form>
        <div class="authfoot" style="margin-top:20px">
          มีบัญชีอยู่แล้ว? <a href="/" style="color:var(--teal-d);font-weight:600">เข้าสู่ระบบ</a><br>
          <a href="/shop.php" style="color:var(--teal-d);font-weight:600">← เลือกชมพระเครื่องก่อน</a>
        </div>
      <?php endif; ?>
    </div></div>
  </div>
</body>
</html>
