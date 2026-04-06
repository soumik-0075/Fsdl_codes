<?php
require_once 'db_connect.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim(mysqli_real_escape_string($conn, $_POST['full_name']));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $pw    = $_POST['password'];
    $cp    = $_POST['confirm_password'];
    if (!$name || !$email || !$pw || !$cp)              { $error = 'All fields are required.'; }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $error = 'Enter a valid email address.'; }
    elseif (strlen($pw) < 6)                            { $error = 'Password must be at least 6 characters.'; }
    elseif ($pw !== $cp)                                { $error = 'Passwords do not match.'; }
    else {
        $chk = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
        if ($chk && mysqli_num_rows($chk) > 0) { $error = 'An account with this email already exists.'; }
        else {
            $hp = password_hash($pw, PASSWORD_DEFAULT);
            if (mysqli_query($conn, "INSERT INTO users(full_name,email,password) VALUES('$name','$email','$hp')")) {
                header("Location: user_login.php?registered=1"); exit;
            } else { $error = 'DB Error: ' . mysqli_error($conn); }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Sign Up – Student Portal</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

:root {
    --bg: #050815;
    --text: #e8eaf0;
    --muted: #5a6480;
    --accent: #10b981;
    --accent2: #6366f1;
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
        radial-gradient(ellipse 60% 50% at 80% 15%, rgba(16,185,129,0.22) 0%, transparent 60%),
        radial-gradient(ellipse 55% 45% at 15% 85%, rgba(99,102,241,0.18) 0%, transparent 55%),
        radial-gradient(ellipse 40% 50% at 45% 50%, rgba(139,92,246,0.1) 0%, transparent 50%);
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
    background-image: radial-gradient(circle, rgba(255,255,255,0.04) 1px, transparent 1px);
    background-size: 36px 36px;
}

.split { display: flex; flex: 1; position: relative; z-index: 1; }

/* Left: decorative */
.panel-deco {
    flex: 0 0 44%;
    background: linear-gradient(145deg, #080f1a 0%, #071524 40%, #091222 70%, #050815 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px;
    position: relative;
    overflow: hidden;
}

.deco-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(55px);
    animation: float-orb 9s ease-in-out infinite;
}
.orb1 { width: 280px; height: 280px; background: rgba(16,185,129,0.25); top: -90px; left: -70px; animation-delay: 0s; }
.orb2 { width: 220px; height: 220px; background: rgba(99,102,241,0.2);  bottom: -70px; right: -50px; animation-delay: 3s; }
.orb3 { width: 150px; height: 150px; background: rgba(139,92,246,0.15); top: 50%; right: 30%; animation-delay: 1.5s; }
@keyframes float-orb {
    0%, 100% { transform: translate(0, 0); }
    50% { transform: translate(12px, -18px); }
}

.deco-content { position: relative; z-index: 1; text-align: center; max-width: 320px; }

.deco-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 14px;
    background: rgba(16,185,129,0.12);
    border: 1px solid rgba(16,185,129,0.28);
    border-radius: 999px;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #34d399;
    margin-bottom: 28px;
}
.deco-icon {
    font-size: 4.5rem;
    display: block;
    margin-bottom: 22px;
    filter: drop-shadow(0 8px 24px rgba(0,0,0,0.5));
    animation: float-icon 4s ease-in-out infinite;
}
@keyframes float-icon {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
.deco-content h2 {
    font-size: 2rem;
    font-weight: 800;
    color: #e8eaf0;
    line-height: 1.2;
    letter-spacing: -0.5px;
    margin-bottom: 14px;
}
.deco-content h2 em {
    font-style: normal;
    background: linear-gradient(90deg, #34d399, #06b6d4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.deco-content p {
    font-size: 0.87rem;
    color: rgba(255,255,255,0.4);
    line-height: 1.7;
    margin-bottom: 32px;
}

.step-row { display: flex; gap: 10px; justify-content: center; align-items: center; }
.step-item {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 0.78rem;
    font-weight: 600;
    color: rgba(255,255,255,0.55);
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Right: form */
.panel-form {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 44px;
    background: rgba(5,8,21,0.72);
    backdrop-filter: blur(22px);
    overflow-y: auto;
}

.form-box { width: 100%; max-width: 380px; }

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
    margin-bottom: 32px;
    transition: all 0.2s;
}
.back-pill:hover { border-color: rgba(255,255,255,0.15); color: var(--text); }

.form-logo {
    width: 52px; height: 52px;
    border-radius: 16px;
    background: linear-gradient(135deg, #10b981, #06b6d4);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    box-shadow: 0 0 30px rgba(16,185,129,0.4);
    margin-bottom: 20px;
}

.form-box h1 {
    font-size: 1.65rem;
    font-weight: 800;
    color: var(--text);
    letter-spacing: -0.5px;
    margin-bottom: 6px;
}
.form-box .subtitle {
    font-size: 0.83rem;
    color: var(--muted);
    margin-bottom: 26px;
}
.form-box .subtitle a {
    color: #34d399;
    font-weight: 600;
    text-decoration: none;
}
.form-box .subtitle a:hover { text-decoration: underline; }

.alert {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 11px 14px;
    border-radius: 12px;
    font-size: 0.82rem;
    font-weight: 600;
    margin-bottom: 18px;
    animation: slide-in 0.3s ease;
}
@keyframes slide-in {
    from { opacity: 0; transform: translateY(-6px); }
    to { opacity: 1; transform: translateY(0); }
}
.alert-err { background: rgba(239,68,68,0.1); border: 1.5px solid rgba(239,68,68,0.22); color: #f87171; }

.field { margin-bottom: 16px; }
.field label {
    display: block;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 8px;
}
.label-note { font-weight: 400; text-transform: none; letter-spacing: 0; opacity: 0.7; }
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
    border-color: #10b981;
    background: rgba(16,185,129,0.06);
    box-shadow: 0 0 0 3px rgba(16,185,129,0.14);
}

.err-inline { color: #f87171; font-size: 0.73rem; margin-top: 5px; display: none; font-weight: 500; }

.btn-submit {
    width: 100%;
    padding: 13px;
    margin-top: 10px;
    background: linear-gradient(135deg, #10b981, #06b6d4);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 0.93rem;
    font-weight: 700;
    font-family: 'Inter', sans-serif;
    cursor: pointer;
    letter-spacing: 0.2px;
    box-shadow: 0 4px 20px rgba(16,185,129,0.38);
    transition: all 0.22s;
}
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(16,185,129,0.52); }
.btn-submit:active { transform: translateY(0); }

.divider { display: flex; align-items: center; gap: 12px; margin: 20px 0 0; }
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
.link-secondary:hover { color: #10b981; }

@media (max-width: 700px) {
    .panel-deco { display: none; }
    .panel-form { padding: 36px 24px; }
}
</style>
</head>
<body>

<div class="aurora"></div>

<div class="split">
    <!-- Left: decorative -->
    <div class="panel-deco">
        <div class="deco-orb orb1"></div>
        <div class="deco-orb orb2"></div>
        <div class="deco-orb orb3"></div>
        <div class="deco-content">
            <div class="deco-badge">🎓 Student Portal</div>
            <span class="deco-icon">📝</span>
            <h2>Join the<br><em>Community</em></h2>
            <p>Create your account and get instant access to your personalized student dashboard.</p>
            <div class="step-row">
                <div class="step-item">1️⃣ Register</div>
                <div class="step-item">→</div>
                <div class="step-item">2️⃣ Login</div>
                <div class="step-item">→</div>
                <div class="step-item">3️⃣ Access</div>
            </div>
        </div>
    </div>

    <!-- Right: form -->
    <div class="panel-form">
        <div class="form-box">
            <a href="index.php" class="back-pill">← Back to Home</a>
            <div class="form-logo">✨</div>
            <h1>Create Account</h1>
            <p class="subtitle">Already have an account? <a href="user_login.php">Log In</a></p>

            <?php if ($error) echo "<div class='alert alert-err'>⚠️ $error</div>"; ?>

            <form method="POST" id="SF" novalidate>
                <div class="field">
                    <label>Full Name</label>
                    <div class="inp-wrap">
                        <span class="ico">👤</span>
                        <input type="text" name="full_name" id="nm" placeholder="John Doe"
                               value="<?= isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : '' ?>">
                    </div>
                    <span class="err-inline" id="e_nm">Full name is required.</span>
                </div>

                <div class="field">
                    <label>Email Address</label>
                    <div class="inp-wrap">
                        <span class="ico">✉️</span>
                        <input type="text" name="email" id="em" placeholder="you@example.com"
                               value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                    </div>
                    <span class="err-inline" id="e_em">Enter a valid email address.</span>
                </div>

                <div class="field">
                    <label>Password <span class="label-note">(min 6 chars)</span></label>
                    <div class="inp-wrap">
                        <span class="ico">🔑</span>
                        <input type="password" name="password" id="pw" placeholder="Create a password">
                    </div>
                    <span class="err-inline" id="e_pw">At least 6 characters required.</span>
                </div>

                <div class="field">
                    <label>Confirm Password</label>
                    <div class="inp-wrap">
                        <span class="ico">🔒</span>
                        <input type="password" name="confirm_password" id="cp" placeholder="Repeat your password">
                    </div>
                    <span class="err-inline" id="e_cp">Passwords do not match.</span>
                </div>

                <button type="submit" class="btn-submit">Create Account →</button>
            </form>

            <div class="divider"><hr><span>or</span><hr></div>
            <a href="index.php" class="link-secondary">Go to Admin Panel</a>
        </div>
    </div>
</div>

<script>
document.getElementById('SF').addEventListener('submit', function(e) {
    let ok = true;
    const s = (id, b) => document.getElementById(id).style.display = b ? 'block' : 'none';
    const g = id => document.getElementById(id).value;
    s('e_nm', !g('nm').trim()); if (!g('nm').trim()) ok = false;
    const ev = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(g('em').trim());
    s('e_em', !ev); if (!ev) ok = false;
    const pv = g('pw').length >= 6; s('e_pw', !pv); if (!pv) ok = false;
    const cv = g('pw') !== g('cp'); s('e_cp', cv); if (cv) ok = false;
    if (!ok) e.preventDefault();
});
</script>
</body>
</html>
