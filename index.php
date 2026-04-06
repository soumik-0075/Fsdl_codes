<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --bg: #050815;
            --surface: rgba(255,255,255,0.04);
            --border: rgba(255,255,255,0.08);
            --text: #e8eaf0;
            --muted: #5a6480;
            --accent1: #6366f1;
            --accent2: #8b5cf6;
            --accent3: #06b6d4;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
            overflow: hidden;
        }

        /* Animated aurora background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 50% at 20% 10%, rgba(99,102,241,0.2) 0%, transparent 60%),
                radial-gradient(ellipse 60% 40% at 80% 80%, rgba(6,182,212,0.15) 0%, transparent 55%),
                radial-gradient(ellipse 50% 60% at 60% 30%, rgba(139,92,246,0.12) 0%, transparent 50%);
            animation: aurora 12s ease-in-out infinite alternate;
            pointer-events: none;
        }
        @keyframes aurora {
            0% { opacity: 0.8; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.05); }
            100% { opacity: 0.8; transform: scale(1.02); }
        }

        /* Floating dots */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        .container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 520px;
        }

        /* Logo / Brand */
        .brand {
            text-align: center;
            margin-bottom: 36px;
        }
        .brand-icon {
            width: 72px; height: 72px;
            border-radius: 22px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6, #06b6d4);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 18px;
            box-shadow: 0 0 0 1px rgba(255,255,255,0.1), 0 0 40px rgba(99,102,241,0.5), 0 0 80px rgba(99,102,241,0.2);
            animation: pulse-glow 3s ease-in-out infinite;
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 1px rgba(255,255,255,0.1), 0 0 40px rgba(99,102,241,0.5), 0 0 80px rgba(99,102,241,0.2); }
            50% { box-shadow: 0 0 0 1px rgba(255,255,255,0.15), 0 0 55px rgba(99,102,241,0.65), 0 0 100px rgba(99,102,241,0.3); }
        }
        .brand h1 {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.5px;
        }
        .brand h1 span {
            background: linear-gradient(90deg, #6366f1, #8b5cf6, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .brand p {
            font-size: 0.82rem;
            color: var(--muted);
            margin-top: 6px;
            letter-spacing: 0.3px;
        }

        /* Glass card */
        .glass-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.09);
            border-radius: 28px;
            padding: 12px;
            backdrop-filter: blur(20px);
            box-shadow: 0 0 0 1px rgba(255,255,255,0.03), 0 32px 64px rgba(0,0,0,0.4);
        }

        .section-heading {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px 8px;
        }
        .section-heading .label {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--muted);
            white-space: nowrap;
        }
        .section-heading hr {
            flex: 1;
            border: none;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .section-heading .dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* Nav grid */
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            padding: 0 2px 4px;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 18px 16px;
            border-radius: 18px;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .nav-item::after {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 0.22s;
            border-radius: inherit;
        }
        .nav-item:hover { transform: translateY(-3px); }
        .nav-item:hover::after { opacity: 1; }
        .nav-item.wide { grid-column: span 2; }

        .nav-item .icon {
            font-size: 1.4rem;
            line-height: 1;
            position: relative;
            z-index: 1;
        }
        .nav-item .title {
            font-size: 0.88rem;
            font-weight: 700;
            color: #dde1ef;
            position: relative;
            z-index: 1;
        }
        .nav-item .hint {
            font-size: 0.72rem;
            font-weight: 500;
            color: var(--muted);
            position: relative;
            z-index: 1;
        }

        /* Color themes for cards */
        .card-violet {
            background: rgba(99,102,241,0.1);
            border-color: rgba(99,102,241,0.22);
        }
        .card-violet:hover { border-color: rgba(99,102,241,0.55); box-shadow: 0 8px 30px rgba(99,102,241,0.2); }

        .card-cyan {
            background: rgba(6,182,212,0.09);
            border-color: rgba(6,182,212,0.2);
        }
        .card-cyan:hover { border-color: rgba(6,182,212,0.5); box-shadow: 0 8px 30px rgba(6,182,212,0.18); }

        .card-amber {
            background: rgba(245,158,11,0.09);
            border-color: rgba(245,158,11,0.18);
        }
        .card-amber:hover { border-color: rgba(245,158,11,0.48); box-shadow: 0 8px 30px rgba(245,158,11,0.16); }

        .card-fuchsia {
            background: rgba(217,70,239,0.09);
            border-color: rgba(217,70,239,0.18);
        }
        .card-fuchsia:hover { border-color: rgba(217,70,239,0.48); box-shadow: 0 8px 30px rgba(217,70,239,0.16); }

        .card-rose {
            background: rgba(244,63,94,0.09);
            border-color: rgba(244,63,94,0.18);
        }
        .card-rose:hover { border-color: rgba(244,63,94,0.48); box-shadow: 0 8px 30px rgba(244,63,94,0.18); }

        .card-emerald {
            background: rgba(16,185,129,0.09);
            border-color: rgba(16,185,129,0.18);
        }
        .card-emerald:hover { border-color: rgba(16,185,129,0.48); box-shadow: 0 8px 30px rgba(16,185,129,0.18); }

        .card-sky {
            background: rgba(14,165,233,0.09);
            border-color: rgba(14,165,233,0.18);
        }
        .card-sky:hover { border-color: rgba(14,165,233,0.48); box-shadow: 0 8px 30px rgba(14,165,233,0.18); }

        .divider-space { height: 4px; }

        footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.72rem;
            color: #2d3555;
            letter-spacing: 0.4px;
        }

        @media (max-width: 480px) {
            .brand h1 { font-size: 1.5rem; }
            .glass-card { padding: 8px; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="brand">
        <div class="brand-icon">🎓</div>
        <h1>Student <span>Registration</span></h1>
        <p>PHP + MySQL · Lab Assignment · Full Stack</p>
    </div>

    <div class="glass-card">
        <!-- Admin Section -->
        <div class="section-heading">
            <div class="dot" style="background:#6366f1;"></div>
            <span class="label">Admin Panel</span>
            <hr>
        </div>
        <div class="grid">
            <a href="insert.php" class="nav-item card-violet">
                <span class="icon">➕</span>
                <span class="title">Insert Student</span>
                <span class="hint">Add a new record</span>
            </a>
            <a href="view.php" class="nav-item card-cyan">
                <span class="icon">📋</span>
                <span class="title">View Students</span>
                <span class="hint">All records</span>
            </a>
            <a href="search.php" class="nav-item card-amber">
                <span class="icon">🔍</span>
                <span class="title">Search Student</span>
                <span class="hint">Find by roll no.</span>
            </a>
            <a href="update.php" class="nav-item card-fuchsia">
                <span class="icon">✏️</span>
                <span class="title">Update Student</span>
                <span class="hint">Edit a record</span>
            </a>
            <a href="delete.php" class="nav-item card-rose wide">
                <span class="icon">🗑️</span>
                <span class="title">Delete Student</span>
                <span class="hint">Remove a record permanently</span>
            </a>
        </div>

        <div class="divider-space"></div>

        <!-- User Portal Section -->
        <div class="section-heading" style="margin-top:6px">
            <div class="dot" style="background:#10b981;"></div>
            <span class="label">User Portal</span>
            <hr>
        </div>
        <div class="grid">
            <a href="user_signup.php" class="nav-item card-emerald">
                <span class="icon">📝</span>
                <span class="title">Sign Up</span>
                <span class="hint">Create an account</span>
            </a>
            <a href="user_login.php" class="nav-item card-sky">
                <span class="icon">🔐</span>
                <span class="title">Log In</span>
                <span class="hint">Access dashboard</span>
            </a>
        </div>
    </div>

    <footer>lab4_db &middot; students &amp; users tables &middot; localhost/lab4</footer>
</div>

</body>
</html>
