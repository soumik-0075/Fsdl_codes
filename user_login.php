<?php
session_start();
require_once 'db_connect.php';
if (isset($_SESSION['user_id'])) { header("Location: user_dashboard.php"); exit; }
$error = ''; $msg = '';
if (isset($_GET['registered']) && $_GET['registered']==='1') $msg = 'Account created! You can now log in.';
if (isset($_GET['logout'])     && $_GET['logout']==='1')     $msg = 'You have been logged out successfully.';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $pw    = $_POST['password'];
    if (!$email || !$pw) { $error = 'Both fields are required.'; }
    else {
        $res = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if ($res && mysqli_num_rows($res) === 1) {
            $u = mysqli_fetch_assoc($res);
            if (password_verify($pw, $u['password'])) {
                $_SESSION['user_id']    = $u['id'];
                $_SESSION['user_name']  = $u['full_name'];
                $_SESSION['user_email'] = $u['email'];
                header("Location: user_dashboard.php"); exit;
            } else { $error = 'Incorrect password. Please try again.'; }
        } else { $error = 'No account found with that email.'; }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Log In – Student Portal</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

:root {
    --bg: #050815;
    --surface: rgba(255,255,255,0.04);
    --border: rgba(255,255,255,0.09);
    --text: #e8eaf0;
    --muted: #5a6480;
    --accent: #6366f1;
    --accent2: #06b6d4;
}

body {
    font-family: 'Inter', sans-serif;
    background: var(--bg);
    min-height: 100vh;
    display: flex;
    align-items: stretch;
    position: relative;
    overflow: hidden;
}

/* Animated aurora */
.aurora {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
}
.aurora::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 70% 55% at 10% 20%, rgba(99,102,241,0.25) 0%, transparent 60%),
        radial-gradient(ellipse 55% 45% at 90% 80%, rgba(6,182,212,0.18) 0%, transparent 55%),
        radial-gradient(ellipse 40% 55% at 55% 10%, rgba(139,92,246,0.14) 0%, transparent 50%);
    animation: aurora-shift 14s ease-in-out infinite alternate;
}
@keyframes aurora-shift {
    0% { opacity: 0.8; }
    100% { opacity: 1; transform: scale(1.04); }
}
.aurora::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle, rgba(255,255,255,0.05) 1px, transparent 1px);
    background-size: 36px 36px;
}

/* Split layout */
.split {
    display: flex;
    flex: 1;
    position: relative;
    z-index: 1;
}

/* Left: Form panel */
.panel-form {
    flex: 0 0 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 40px;
    background: rgba(5,8,21,0.75);
    backdrop-filter: blur(24px);
    overflow-y: auto;
}

.form-box {
    width: 100%;
    max-width: 360px;
}

/* Back to home pill */
.back-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--muted);
    text-decoration: none;
    letter-spacing: 0.3px;
    margin-bottom: 36px;
    transition: all 0.2s;
}
.back-pill:hover { border-color: rgba(255,255,255,0.15); color: var(--text); }

.form-logo {
    width: 52px; height: 52px;
    border-radius: 16px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    box-shadow: 0 0 30px rgba(99,102,241,0.45);
    margin-bottom: 22px;
}

.form-box h1 {
    font-size: 1.7rem;
    font-weight: 800;
    color: var(--text);
    letter-spacing: -0.5px;
    margin-bottom: 6px;
}
.form-box .subtitle {
    font-size: 0.83rem;
    color: var(--muted);
    margin-bottom: 28px;
}
.form-box .subtitle a {
    color: #818cf8;
    font-weight: 600;
    text-decoration: none;
}
.form-box .subtitle a:hover { text-decoration: underline; }

/* Alerts */
.alert {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 11px 14px;
    border-radius: 12px;
    font-size: 0.82rem;
    font-weight: 600;
    margin-bottom: 20px;
    animation: slide-in 0.3s ease;
}
@keyframes slide-in {
    from { opacity: 0; transform: translateY(-6px); }
    to { opacity: 1; transform: translateY(0); }
}
.alert-ok  { background: rgba(16,185,129,0.1); border: 1.5px solid rgba(16,185,129,0.22); color: #34d399; }
.alert-err { background: rgba(239,68,68,0.1);  border: 1.5px solid rgba(239,68,68,0.22);  color: #f87171; }

/* Form fields */
.field { margin-bottom: 18px; }
.field label {
    display: block;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 8px;
}
.inp-wrap { position: relative; }
.inp-wrap .ico {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.95rem;
    pointer-events: none;
    opacity: 0.6;
}
.inp-wrap input {
    width: 100%;
    padding: 12px 14px 12px 42px;
    background: rgba(255,255,255,0.05);
    border: 1.5px solid rgba(255,255,255,0.09);
    border-radius: 12px;
    color: var(--text);
    font-size: 0.9rem;
    font-family: 'Inter', sans-serif;
    outline: none;
    transition: all 0.22s;
}
.inp-wrap input::placeholder { color: #2e3554; }
.inp-wrap input:focus {
    border-color: #6366f1;
    background: rgba(99,102,241,0.07);
    box-shadow: 0 0 0 3px rgba(99,102,241,0.16);
}

/* Submit button */
.btn-submit {
    width: 100%;
    padding: 13px;
    margin-top: 8px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 0.93rem;
    font-weight: 700;
    font-family: 'Inter', sans-serif;
    cursor: pointer;
    letter-spacing: 0.2px;
    box-shadow: 0 4px 20px rgba(99,102,241,0.42);
    transition: all 0.22s;
    position: relative;
    overflow: hidden;
}
.btn-submit::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
    opacity: 0;
    transition: opacity 0.22s;
}
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(99,102,241,0.55); }
.btn-submit:hover::after { opacity: 1; }
.btn-submit:active { transform: translateY(0); }

/* Divider */
.divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 22px 0 0;
}
.divider hr { flex: 1; border: none; border-top: 1px solid rgba(255,255,255,0.07); }
.divider span { font-size: 0.72rem; color: #2e3554; font-weight: 500; }

.link-secondary {
    display: block;
    text-align: center;
    margin-top: 14px;
    font-size: 0.78rem;
    color: #2e3554;
    text-decoration: none;
    transition: color 0.2s;
}
.link-secondary:hover { color: #6366f1; }

/* Right: decorative panel */
.panel-deco {
    flex: 1;
    background: linear-gradient(145deg, #0a0d2e 0%, #0d1540 30%, #07182d 65%, #050e1f 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px;
    position: relative;
    overflow: hidden;
}

/* Deco orbs */
.deco-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(60px);
    animation: float-orb 8s ease-in-out infinite;
}
.orb1 { width: 300px; height: 300px; background: rgba(99,102,241,0.3); top: -100px; right: -80px; animation-delay: 0s; }
.orb2 { width: 250px; height: 250px; background: rgba(6,182,212,0.2);  bottom: -80px; left: -60px; animation-delay: 3s; }
.orb3 { width: 180px; height: 180px; background: rgba(139,92,246,0.15); top: 40%; left: 40%; animation-delay: 1.5s; }
@keyframes float-orb {
    0%, 100% { transform: translate(0, 0); }
    50% { transform: translate(15px, -20px); }
}

.deco-content { position: relative; z-index: 1; text-align: center; max-width: 340px; }

.deco-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 14px;
    background: rgba(99,102,241,0.15);
    border: 1px solid rgba(99,102,241,0.3);
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #818cf8;
    margin-bottom: 28px;
}

.deco-icon {
    font-size: 4.5rem;
    display: block;
    margin-bottom: 24px;
    filter: drop-shadow(0 8px 24px rgba(0,0,0,0.5));
    animation: float-icon 4s ease-in-out infinite;
}
@keyframes float-icon {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.deco-content h2 {
    font-size: 2.1rem;
    font-weight: 800;
    color: #e8eaf0;
    line-height: 1.2;
    letter-spacing: -0.5px;
    margin-bottom: 14px;
}
.deco-content h2 em {
    font-style: normal;
    background: linear-gradient(90deg, #818cf8, #06b6d4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.deco-content p {
    font-size: 0.87rem;
    color: rgba(255,255,255,0.45);
    line-height: 1.7;
    margin-bottom: 36px;
}

.stat-row {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}
.stat-chip {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px;
    padding: 12px 18px;
    text-align: center;
    min-width: 80px;
}
.stat-chip .val { font-size: 1.3rem; font-weight: 800; color: #818cf8; }
.stat-chip .key { font-size: 0.68rem; color: rgba(255,255,255,0.35); margin-top: 3px; }

@media (max-width: 700px) {
    .panel-deco { display: none; }
    .panel-form { flex: 1; padding: 36px 24px; }
}
</style>
</head>
<body>

<div class="aurora"></div>

<div class="split">
    <!-- Form side (left) -->
    <div class="panel-form">
        <div class="form-box">
            <a href="index.php" class="back-pill">← Back to Home</a>
            <div class="form-logo">🔐</div>
            <h1>Welcome back</h1>
            <p class="subtitle">Don't have an account? <a href="user_signup.php">Sign Up</a></p>

            <?php if ($msg)   echo "<div class='alert alert-ok'>✅ $msg</div>"; ?>
            <?php if ($error) echo "<div class='alert alert-err'>⚠️ $error</div>"; ?>

            <form method="POST">
                <div class="field">
                    <label>Email Address</label>
                    <div class="inp-wrap">
                        <span class="ico">✉️</span>
                        <input type="text" name="email" placeholder="you@example.com"
                               value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                    </div>
                </div>
                <div class="field">
                    <label>Password</label>
                    <div class="inp-wrap">
                        <span class="ico">🔑</span>
                        <input type="password" name="password" placeholder="Your password">
                    </div>
                </div>
                <button type="submit" class="btn-submit">Sign In →</button>
            </form>

            <div class="divider"><hr><span>or</span><hr></div>
            <a href="index.php" class="link-secondary">Go to Admin Panel</a>
        </div>
    </div>

    <!-- Decorative side (right) -->
    <div class="panel-deco">
        <div class="deco-orb orb1"></div>
        <div class="deco-orb orb2"></div>
        <div class="deco-orb orb3"></div>
        <div class="deco-content">
            <div class="deco-badge">✨ Student Portal</div>
            <span class="deco-icon">🎓</span>
            <h2>Your Academic<br><em>Gateway</em></h2>
            <p>Securely access your personalized dashboard to manage your profile and track your academic journey.</p>
            <div class="stat-row">
                <div class="stat-chip"><div class="val">100%</div><div class="key">Secure</div></div>
                <div class="stat-chip"><div class="val">24/7</div><div class="key">Access</div></div>
                <div class="stat-chip"><div class="val">Fast</div><div class="key">Login</div></div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
