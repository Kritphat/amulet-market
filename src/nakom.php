<?php
// Public landing page that a scanned QR opens: shows a blessing image
// (น้าค่อม or สาธุ99) plus the (optional) phrase passed as ?msg=.
// No login required so phones can load it.
$msg  = $_GET['msg'] ?? 'น้าค่อมการันตี';
$imgs = ['nakom' => 'น้าค่อม.jpeg', 'sathu' => 'สาธุ99.jpeg'];
$key  = $_GET['img'] ?? '';
if (!isset($imgs[$key])) { $key = array_rand($imgs); }  // random if missing/unknown
$file = $imgs[$key];
$tag  = $key === 'sathu' ? '🙏 สาธุ 99' : '✅ น้าค่อมการันตี';
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>น้าค่อมการันตี</title>
  <style>
    :root{ --bg:#0b3d2e; --card:#ffffff; --ink:#0b3d2e; --muted:#6b7280; --gold:#e3bf6c; }
    *{box-sizing:border-box}
    body{margin:0;min-height:100vh;display:grid;place-items:center;padding:20px;
      background:radial-gradient(circle at 50% 0%, #128c7e, #075e54 60%, #053b34);
      font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Noto Sans Thai",sans-serif;color:var(--ink)}
    .card{width:100%;max-width:380px;background:var(--card);border-radius:22px;overflow:hidden;
      box-shadow:0 24px 70px rgba(0,0,0,.45);text-align:center}
    .pic{display:block;width:100%;aspect-ratio:1;object-fit:cover;background:#fef3dd}
    .body{padding:22px 22px 26px}
    .msg{font-size:20px;font-weight:800;color:var(--ink);line-height:1.5;margin:0 0 10px}
    .sub{color:var(--muted);font-size:13px;margin:0}
    .tag{display:inline-block;margin-top:14px;padding:6px 14px;border-radius:999px;
      background:#075e54;color:#fff;font-size:12.5px;font-weight:700}
  </style>
</head>
<body>
  <div class="card">
    <img class="pic" src="/assets/img/<?= htmlspecialchars($file) ?>" alt="<?= htmlspecialchars($key) ?>"
         onerror="this.style.aspectRatio='auto';this.style.padding='40px';">
    <div class="body">
      <p class="msg"><?= htmlspecialchars($msg) ?></p>
      <p class="sub">สแกนสำเร็จ · ท่านได้รับพรแล้ว</p>
      <span class="tag"><?= htmlspecialchars($tag) ?></span>
    </div>
  </div>
</body>
</html>
