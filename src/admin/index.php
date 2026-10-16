<?php
// Reaching this file means Apache Basic Auth (via ../private/.htpasswd) already
// passed, so the visitor holds valid administrator credentials.
require __DIR__ . '/../config.php';
$admin = $_SERVER['PHP_AUTH_USER'] ?? 'admin';

// --- Account role management (admin-only tool) -------------------------------
// Lets an administrator set any account's role. Promote your own registered
// account to 'GOAT', then log in with it to claim the final flag.
$msg = ''; $err = '';
$ROLES = ['customer', 'staff', 'GOAT'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tuser = trim($_POST['username'] ?? '');
    $trole = $_POST['role'] ?? '';
    if ($tuser === '' || !in_array($trole, $ROLES, true)) {
        $err = 'กรุณาเลือกบัญชีและสิทธิ์ให้ถูกต้อง';
    } else {
        $st = $mysqli->prepare('UPDATE staff SET role = ? WHERE username = ?');
        $st->bind_param('ss', $trole, $tuser);
        $st->execute();
        if ($st->affected_rows > 0) {
            $msg = "อัปเดตสิทธิ์ของ \"$tuser\" เป็น \"$trole\" เรียบร้อยแล้ว";
        } else {
            $err = "ไม่พบบัญชี \"$tuser\" หรือสิทธิ์เดิมเป็น \"$trole\" อยู่แล้ว";
        }
        $st->close();
    }
}
// Live account list (most recent first) so the admin can confirm the change.
$accounts = [];
if ($res = $mysqli->query('SELECT username, fullname, role FROM staff ORDER BY id DESC')) {
    while ($r = $res->fetch_assoc()) { $accounts[] = $r; }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>คอนโซลผู้ดูแลระบบ | ตลาดพระออนไลน์</title>
  <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
  <header class="adminbar">
    <div class="inner">
      <a class="brand" href="/admin/" style="color:#efe6d2">
        <span class="logo" style="background:linear-gradient(135deg,#9a2c22,#6b1414)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="9" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
        </span>
        <div>ตลาดพระ <small>คอนโซลผู้ดูแลระบบ</small></div>
      </a>
      <div class="userchip" style="margin-left:auto;border-color:#4e3c20;color:#efe6d2">
        <span><?= htmlspecialchars($admin) ?></span>
        <span class="badge admin">ผู้ดูแลสูงสุด</span>
        <span class="av" style="background:linear-gradient(135deg,#9a2c22,#6b1414)"><?= strtoupper(substr($admin,0,1)) ?></span>
      </div>
    </div>
  </header>

  <main class="wrap">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
      <div>
        <h2 style="margin:0 0 2px">ระบบจัดการผู้ดูแล</h2>
        <p class="muted" style="margin:0">แผงควบคุมเต็มรูปแบบ · เข้าสู่ระบบในชื่อ <b><?= htmlspecialchars($admin) ?></b> ผ่าน Basic Auth</p>
      </div>
      <span class="pill on">● ระบบทำงานปกติทั้งหมด</span>
    </div>

    <div class="grid stats">
      <div class="card stat"><div class="k b"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 4-6 8-6s8 2 8 6"/></svg></div><div><div class="n">312</div><div class="l">บัญชีสมาชิก/ร้านค้า</div></div></div>
      <div class="card stat"><div class="k t"><svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c-1.3 1.9-1.9 3.8-1.3 5.7C9 6.9 7.3 6.6 5.4 7c.3 1.9 1.4 3.5 3 4.6-1.9.2-3.6 1-5 2.4 2.3 2.6 5.3 3.9 8.6 3.9s6.3-1.3 8.6-3.9c-1.4-1.4-3.1-2.2-5-2.4 1.6-1.1 2.7-2.7 3-4.6-1.9-.4-3.6-.1-5.3.7.6-1.9 0-3.8-1.3-5.7z"/></svg></div><div><div class="n">1,284</div><div class="l">พระเครื่องในระบบ</div></div></div>
      <div class="card stat"><div class="k o"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l9 4v6c0 5-3.8 8.5-9 10-5.2-1.5-9-5-9-10V6z"/></svg></div><div><div class="n">Basic Auth</div><div class="l">การป้องกันคอนโซล</div></div></div>
    </div>

    <div class="card" style="margin-top:22px">
      <div class="sechead"><h3>🛡️ จัดการสิทธิ์บัญชี</h3><span class="pill off">เฉพาะผู้ดูแล</span></div>
      <div class="sec">
        <p class="muted" style="margin:0 0 14px">กำหนดระดับสิทธิ์ให้บัญชีสมาชิกใด ๆ ในระบบ (customer / staff / GOAT) — บัญชีระดับ <b>GOAT</b> คือสิทธิ์พิเศษสูงสุดของสมาชิก</p>
        <?php if ($msg): ?><div class="success">✅ <?= htmlspecialchars($msg) ?></div><?php endif; ?>
        <?php if ($err): ?><div class="alert err show">⚠️ <?= htmlspecialchars($err) ?></div><?php endif; ?>
        <form method="POST" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
          <div style="flex:1;min-width:180px">
            <label>ชื่อผู้ใช้</label>
            <div class="field"><input type="text" name="username" placeholder="เช่น บัญชีที่คุณสมัครไว้"></div>
          </div>
          <div style="min-width:150px">
            <label>สิทธิ์</label>
            <div class="field">
              <select name="role" style="width:100%;border:0;background:transparent;font:inherit;outline:none">
                <?php foreach ($ROLES as $r): ?><option value="<?= $r ?>"<?= $r==='GOAT'?' selected':'' ?>><?= $r ?></option><?php endforeach; ?>
              </select>
            </div>
          </div>
          <button class="btn" type="submit" style="width:auto;margin:0">บันทึกสิทธิ์</button>
        </form>
      </div>
    </div>

    <div class="card" style="margin-top:22px">
      <div class="sechead"><h3>👥 บัญชีทั้งหมด</h3><span class="muted" style="font-size:12.5px">แสดง <?= count($accounts) ?> บัญชี</span></div>
      <table class="tbl">
        <thead><tr><th>ผู้ใช้</th><th>ชื่อ</th><th>สิทธิ์</th></tr></thead>
        <tbody>
          <tr><td>admin</td><td>ผู้ดูแลระบบ</td><td><span class="badge admin">ผู้ดูแลสูงสุด</span></td></tr>
          <?php foreach ($accounts as $a): ?>
          <tr>
            <td><?= htmlspecialchars($a['username']) ?></td>
            <td><?= htmlspecialchars($a['fullname'] ?? '') ?></td>
            <td><span class="badge<?= $a['role']==='GOAT'?' admin':'' ?>"><?= htmlspecialchars($a['role']) ?></span></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>
