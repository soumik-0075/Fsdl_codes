<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: user_login.php"); exit; }
require_once 'db_connect.php';
$uid  = (int)$_SESSION['user_id'];
$res  = mysqli_query($conn, "SELECT * FROM users WHERE id=$uid");
$u    = ($res && mysqli_num_rows($res) > 0) ? mysqli_fetch_assoc($res) : null;
if (!$u) { session_destroy(); header("Location: user_login.php"); exit; }
$init   = strtoupper(substr($u['full_name'], 0, 1));
$first  = htmlspecialchars(explode(' ', $u['full_name'])[0]);
$joined = date("d M Y", strtotime($u['created_at']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Dashboard – Student Portal</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

:root {
    --bg: #050815;
    --surface: rgba(255,255,255,0.04);
    --border: rgba(255,255,255,0.08);
    --text: #e8eaf0;
    --muted: #5a6480;
    --accent: #6366f1;
}

body {
    font-family: 'Inter', sans-serif;
    background: var(--bg);
    min-height: 100vh;
    color: var(--text);
    position: relative;
}

/* Ambient bg */
body::before {
    content: '';
    position: fixed;
    inset: 0;
    background:
        radial-gradient(ellipse 70% 50% at 15% 15%, rgba(99,102,241,0.18) 0%, transparent 60%),
        radial-gradient(ellipse 55% 45% at 85% 85%, rgba(6,182,212,0.13) 0%, transparent 55%),
        radial-gradient(ellipse 45% 55% at 60% 30%, rgba(139,92,246,0.1) 0%, transparent 50%);
    pointer-events: none;
    z-index: 0;
    animation: ambient-shift 16s ease-in-out infinite alternate;
}
@keyframes ambient-shift {
    0% { opacity: 0.8; }
    100% { opacity: 1; transform: scale(1.03); }
}
body::after {
    content: '';
    position: fixed;
    inset: 0;
    background-image: radial-gradient(circle, rgba(255,255,255,0.04) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
    z-index: 0;
}

/* Navbar */
.navbar {
    position: fixed;
    top: 0; left: 0; right: 0;
    height: 64px;
    background: rgba(5,8,21,0.8);
    backdrop-filter: blur(18px);
    border-bottom: 1px solid rgba(255,255,255,0.07);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 28px;
    z-index: 100;
}
.nav-brand {
    display: flex;
    align-items: center;
    gap: 11px;
}
.nav-icon {
    width: 38px; height: 38px;
    border-radius: 11px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    box-shadow: 0 0 18px rgba(99,102,241,0.38);
}
.nav-brand span {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--text);
}

.nav-right { display: flex; align-items: center; gap: 12px; }

.nav-user {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 6px 12px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 600;
    color: rgba(255,255,255,0.65);
}
.nav-avatar {
    width: 26px; height: 26px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.72rem;
    font-weight: 800;
    color: #fff;
}

.btn-logout {
    padding: 8px 16px;
    background: rgba(244,63,94,0.1);
    color: #fb7185;
    border: 1px solid rgba(244,63,94,0.22);
    border-radius: 9px;
    font-size: 0.8rem;
    font-weight: 700;
    font-family: 'Inter', sans-serif;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-logout:hover { background: #f43f5e; color: #fff; border-color: #f43f5e; }

/* Main */
.main {
    position: relative;
    z-index: 1;
    max-width: 860px;
    margin: 0 auto;
    padding: 94px 24px 50px;
}

/* Hero banner */
.hero {
    background: linear-gradient(135deg, rgba(99,102,241,0.12) 0%, rgba(139,92,246,0.08) 50%, rgba(6,182,212,0.06) 100%);
    border: 1px solid rgba(99,102,241,0.22);
    border-radius: 24px;
    padding: 32px 30px;
    display: flex;
    align-items: center;
    gap: 22px;
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute;
    right: -60px; top: -60px;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(99,102,241,0.2) 0%, transparent 70%);
    pointer-events: none;
}
.hero::after {
    content: '';
    position: absolute;
    left: 35%; bottom: -50px;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(6,182,212,0.12) 0%, transparent 70%);
    pointer-events: none;
}

.hero-avatar {
    width: 76px; height: 76px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6, #06b6d4);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 800;
    color: #fff;
    flex-shrink: 0;
    box-shadow: 0 0 0 4px rgba(99,102,241,0.25), 0 0 24px rgba(99,102,241,0.35);
    position: relative; z-index: 1;
}

.hero-info { flex: 1; position: relative; z-index: 1; }
.hero-info .label {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #818cf8;
    margin-bottom: 6px;
}
.hero-info h1 {
    font-size: 1.7rem;
    font-weight: 800;
    color: var(--text);
    letter-spacing: -0.4px;
    margin-bottom: 5px;
}
.hero-info p { font-size: 0.83rem; color: var(--muted); }

.hero-id {
    position: relative; z-index: 1;
    background: rgba(99,102,241,0.12);
    border: 1px solid rgba(99,102,241,0.25);
    border-radius: 14px;
    padding: 12px 18px;
    text-align: center;
    flex-shrink: 0;
}
.hero-id .id-num { font-size: 1.4rem; font-weight: 800; color: #a5b4fc; }
.hero-id .id-lbl { font-size: 0.68rem; color: var(--muted); margin-top: 3px; }

/* Info grid */
.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px; }

.info-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 22px 20px;
    transition: border-color 0.22s, box-shadow 0.22s;
}
.info-card:hover { border-color: rgba(99,102,241,0.28); box-shadow: 0 4px 20px rgba(99,102,241,0.08); }

.card-label {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.9px;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 11px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.card-value {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 4px;
    word-break: break-word;
}
.card-sub { font-size: 0.75rem; color: var(--muted); }

/* Status badge */
.status-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    background: rgba(16,185,129,0.1);
    border: 1px solid rgba(16,185,129,0.2);
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 700;
    color: #34d399;
}
.status-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #34d399;
    animation: pulse-dot 2s ease-in-out infinite;
}
@keyframes pulse-dot {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
}

/* Action row */
.actions { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

.btn-action {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    padding: 15px;
    border-radius: 14px;
    font-size: 0.88rem;
    font-weight: 700;
    text-decoration: none;
    border: 1.5px solid transparent;
    transition: all 0.22s;
    font-family: 'Inter', sans-serif;
}
.btn-home {
    background: rgba(99,102,241,0.1);
    color: #a5b4fc;
    border-color: rgba(99,102,241,0.22);
}
.btn-home:hover { background: #6366f1; color: #fff; box-shadow: 0 6px 22px rgba(99,102,241,0.4); }
.btn-out {
    background: rgba(244,63,94,0.08);
    color: #fb7185;
    border-color: rgba(244,63,94,0.2);
}
.btn-out:hover { background: #f43f5e; color: #fff; box-shadow: 0 6px 22px rgba(244,63,94,0.38); }

@media (max-width: 600px) {
    .info-grid, .actions { grid-template-columns: 1fr; }
    .hero { flex-wrap: wrap; }
    .hero-id { margin-left: 0; }
    .navbar { padding: 0 16px; }
    .nav-user { display: none; }
}
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="nav-brand">
        <div class="nav-icon">🎓</div>
        <span>Student Portal</span>
    </div>
    <div class="nav-right">
        <div class="nav-user">
            <div class="nav-avatar"><?= $init ?></div>
            <?= $first ?>
        </div>
        <a href="user_logout.php" class="btn-logout">Log Out</a>
    </div>
</nav>

<!-- Main content -->
<div class="main">

    <!-- Hero -->
    <div class="hero">
        <div class="hero-avatar"><?= $init ?></div>
        <div class="hero-info">
            <div class="label">Student Dashboard</div>
            <h1>Hello, <?= $first ?>! 👋</h1>
            <p>Here's your account overview and profile information.</p>
        </div>
        <div class="hero-id">
            <div class="id-num">#<?= $u['id'] ?></div>
            <div class="id-lbl">User ID</div>
        </div>
    </div>

    <!-- Info cards -->
    <div class="info-grid">
        <div class="info-card">
            <div class="card-label">👤 Full Name</div>
            <div class="card-value"><?= htmlspecialchars($u['full_name']) ?></div>
            <div class="card-sub">Registered name</div>
        </div>
        <div class="info-card">
            <div class="card-label">✉️ Email Address</div>
            <div class="card-value" style="font-size:0.9rem"><?= htmlspecialchars($u['email']) ?></div>
            <div class="card-sub">Login credential</div>
        </div>
        <div class="info-card">
            <div class="card-label">📅 Member Since</div>
            <div class="card-value"><?= $joined ?></div>
            <div class="card-sub">Account created</div>
        </div>
        <div class="info-card">
            <div class="card-label">🔒 Account Status</div>
            <div class="card-value">
                <div class="status-chip">
                    <div class="status-dot"></div>
                    Active
                </div>
            </div>
            <div class="card-sub">Verified account</div>
        </div>
    </div>

    <!-- Actions -->
    <div class="actions">
        <a href="index.php" class="btn-action btn-home">🏠 Admin Panel</a>
        <a href="user_logout.php" class="btn-action btn-out">🚪 Log Out</a>
    </div>

</div>
</body>
</html>
