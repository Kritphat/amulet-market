<?php
require 'config.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// --- "WAF" (intentionally weak, Stage 1 intermediate bump) -------------------
// Blocks the obvious OR/AND keywords and SQL comments, so a plain
// `' OR 1=1 -- ` payload fails. Bypass with logical operators (|| / &&)
// and a `#` comment.  (Vulnerability: blacklist-based filtering.)
$blocked = ['/\bor\b/i', '/\band\b/i', '/--/', '/\bunion\b/i'];
foreach ($blocked as $re) {
    if (preg_match($re, $username)) {
        header('Location: index.php?e=' . urlencode('Illegal characters detected'));
        exit;
    }
}

// --- VULNERABLE QUERY --------------------------------------------------------
// Password is md5'd, but the username is concatenated raw => SQL injection.
// Note: this query ONLY touches the `staff` table, so injection can never
// return the admin account. The first matching row is a low-priv staff user.
$sql = "SELECT id, username, fullname, role FROM staff
        WHERE username = '$username' AND password = '" . md5($password) . "'
        LIMIT 1";

$res = $mysqli->query($sql);

if ($res && $row = $res->fetch_assoc()) {
    $_SESSION['staff'] = $row['username'];
    $_SESSION['role']  = $row['role'];
    // Route by role: GOAT → final-flag vault, customers → storefront, staff → portal.
    if ($row['role'] === 'GOAT')          $dest = 'vault.php';
    elseif ($row['role'] === 'customer')  $dest = 'shop.php';
    else                                  $dest = 'dashboard.php';
    header('Location: ' . $dest);
    exit;
}

header('Location: index.php?e=' . urlencode('Invalid credentials'));
