<?php
require_once 'db_connect.php';
$student = null;
$success = $error = '';
$search_roll = '';
if (isset($_GET['roll_no']))
    $search_roll = trim(mysqli_real_escape_string($conn, $_GET['roll_no']));
elseif (isset($_POST['search_roll']))
    $search_roll = trim(mysqli_real_escape_string($conn, $_POST['search_roll']));
if ($search_roll !== '') {
    $res = mysqli_query($conn, "SELECT * FROM students WHERE roll_no='$search_roll'");
    if ($res && mysqli_num_rows($res) > 0)
        $student = mysqli_fetch_assoc($res);
    else
        $error = "No student found with Roll No. <strong>" . htmlspecialchars($search_roll) . "</strong>.";
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_roll'])) {
    $ur = trim(mysqli_real_escape_string($conn, $_POST['update_roll']));
    $fn = trim(mysqli_real_escape_string($conn, $_POST['first_name']));
    $ln = trim(mysqli_real_escape_string($conn, $_POST['last_name']));
    $pw = $_POST['password'];
    $cp = $_POST['confirm_password'];
    $cn = trim(mysqli_real_escape_string($conn, $_POST['contact_number']));
    if (!$fn || !$ln || !$cn) {
        $error = 'First name, last name and contact are required.';
    } elseif (!preg_match('/^\d{10}$/', $cn)) {
        $error = 'Contact must be exactly 10 digits.';
    } elseif ($pw && $pw !== $cp) {
        $error = 'Passwords do not match.';
    } else {
        $sql = $pw ? "UPDATE students SET first_name='$fn',last_name='$ln',password='" . password_hash($pw, PASSWORD_DEFAULT) . "',confirm_password='" . password_hash($cp, PASSWORD_DEFAULT) . "',contact_number='$cn' WHERE roll_no='$ur'" : "UPDATE students SET first_name='$fn',last_name='$ln',contact_number='$cn' WHERE roll_no='$ur'";
        if (mysqli_query($conn, $sql)) {
            $success = 'Record updated successfully!';
            $res = mysqli_query($conn, "SELECT * FROM students WHERE roll_no='$ur'");
            $student = mysqli_fetch_assoc($res);
            $search_roll = $ur;
        } else
            $error = 'DB Error: ' . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Update Student</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0a0f1e;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 36px 16px;
            background-image: radial-gradient(ellipse at 20% 10%, rgba(236, 72, 153, .15) 0, transparent 55%), radial-gradient(ellipse at 80% 90%, rgba(124, 58, 237, .10) 0, transparent 55%);
        }

        .card {
            background: rgba(255, 255, 255, .03);
            border: 1px solid rgba(236, 72, 153, .28);
            border-radius: 24px;
            padding: 36px 34px;
            width: 100%;
            max-width: 480px;
            backdrop-filter: blur(12px)
        }

        .hd {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 22px
        }

        .ico {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, #ec4899, #db2777);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            box-shadow: 0 0 20px rgba(236, 72, 153, .4)
        }

        h2 {
            font-size: 1.45rem;
            font-weight: 800;
            color: #f1f5f9
        }

        .bc {
            font-size: .78rem;
            color: #475569;
            margin-top: 2px
        }

        .bc a {
            color: #ec4899;
            text-decoration: none
        }

        .div {
            height: 1px;
            background: rgba(255, 255, 255, .07);
            margin: 20px 0
        }

        label {
            display: block;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .4px;
            text-transform: uppercase;
            color: #64748b;
            margin: 14px 0 6px
        }

        .note {
            font-size: .72rem;
            font-weight: 400;
            text-transform: none;
            letter-spacing: 0;
            color: #334155;
            margin-left: 6px
        }

        input[type=text],
        input[type=password] {
            width: 100%;
            padding: 11px 14px;
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 10px;
            color: #e2e8f0;
            font-size: .93rem;
            font-family: inherit;
            outline: none;
            transition: border .2s, box-shadow .2s
        }

        input:not(:disabled):focus {
            border-color: #ec4899;
            box-shadow: 0 0 0 3px rgba(236, 72, 153, .18);
            background: rgba(236, 72, 153, .06)
        }

        input:disabled {
            opacity: .45;
            cursor: not-allowed
        }

        input::placeholder {
            color: #334155
        }

        .err {
            color: #f87171;
            font-size: .76rem;
            margin-top: 4px;
            display: none
        }

        .btn-s {
            width: 100%;
            padding: 13px;
            margin-top: 4px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: .95rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            box-shadow: 0 4px 18px rgba(99, 102, 241, .4);
            transition: transform .2s, box-shadow .2s
        }

        .btn-s:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(99, 102, 241, .55)
        }

        .btn-f {
            width: 100%;
            padding: 13px;
            margin-top: 10px;
            background: linear-gradient(135deg, #ec4899, #db2777);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: .95rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            box-shadow: 0 4px 18px rgba(236, 72, 153, .4);
            transition: transform .2s, box-shadow .2s
        }

        .btn-f:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(236, 72, 153, .55)
        }

        .ok {
            background: rgba(16, 185, 129, .1);
            border: 1px solid rgba(16, 185, 129, .3);
            color: #34d399;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: .87rem;
            font-weight: 600;
            margin-bottom: 16px
        }

        .er {
            background: rgba(239, 68, 68, .1);
            border: 1px solid rgba(239, 68, 68, .25);
            color: #f87171;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: .87rem;
            font-weight: 600;
            margin-bottom: 16px
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 16px;
            color: #475569;
            font-size: .83rem;
            text-decoration: none;
            transition: color .2s
        }

        .back:hover {
            color: #ec4899
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="hd">
            <div class="ico">✏️</div>
            <div>
                <h2>Update Student</h2>
                <p class="bc"><a href="index.php">Home</a> › Update</p>
            </div>
        </div>
        <div class="div"></div>
        <?php if ($success)
            echo "<div class='ok'>✅ $success</div>";
        if ($error)
            echo "<div class='er'>❌ $error</div>"; ?>

        <?php if (!$student): ?>
            <form method="POST">
                <label>Roll Number to Search</label>
                <input type="text" name="search_roll" placeholder="e.g. CS2024001"
                    value="<?= htmlspecialchars($search_roll) ?>">
                <button type="submit" class="btn-s">🔍 Find Student</button>
            </form>
        <?php else: ?>
            <form method="POST" id="UF" novalidate>
                <input type="hidden" name="update_roll" value="<?= htmlspecialchars($student['roll_no']) ?>">
                <label>Roll No. (read-only)</label>
                <input type="text" value="<?= htmlspecialchars($student['roll_no']) ?>" disabled>
                <label>First Name</label>
                <input type="text" name="first_name" id="fn" value="<?= htmlspecialchars($student['first_name']) ?>">
                <span class="err" id="e_fn">Required.</span>
                <label>Last Name</label>
                <input type="text" name="last_name" id="ln" value="<?= htmlspecialchars($student['last_name']) ?>">
                <span class="err" id="e_ln">Required.</span>
                <label>New Password <span class="note">(leave blank to keep current)</span></label>
                <input type="password" name="password" id="pw" placeholder="New password">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" id="cp" placeholder="Repeat new password">
                <span class="err" id="e_cp">Passwords do not match.</span>
                <label>Contact Number</label>
                <input type="text" name="contact_number" id="cn" value="<?= htmlspecialchars($student['contact_number']) ?>"
                    maxlength="10">
                <span class="err" id="e_cn">Must be exactly 10 digits.</span>
                <button type="submit" class="btn-f">💾 Save Changes</button>
            </form>
            <script>
                document.getElementById('UF').addEventListener('submit', function (e) {
                    let ok = true; const s = (id, b) => document.getElementById(id).style.display = b ? 'block' : 'none'; const g = id => document.getElementById(id).value;
                    s('e_fn', !g('fn').trim()); if (!g('fn').trim()) ok = false;
                    s('e_ln', !g('ln').trim()); if (!g('ln').trim()) ok = false;
                    const pm = (g('pw') || g('cp')) && g('pw') !== g('cp'); s('e_cp', pm); if (pm) ok = false;
                    const cb = !/^\d{10}$/.test(g('cn').trim()); s('e_cn', cb); if (cb) ok = false;
                    if (!ok) e.preventDefault();
                });
            </script>
        <?php endif; ?>
        <a href="index.php" class="back">← Back to Home</a>
    </div>
</body>

</html>