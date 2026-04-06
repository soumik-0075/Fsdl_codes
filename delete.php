<?php
require_once 'db_connect.php';

function showConfirm($s,$from='view'){
    $cancel=$from==='view'?'view.php':'delete.php';
    echo "<div class='conf'>
        <div class='cl'>⚠️ Confirm Deletion</div>
        <div class='fr'><span class='fk'>Name</span><span class='fv'>".htmlspecialchars($s['first_name'])." ".htmlspecialchars($s['last_name'])."</span></div>
        <div class='fr'><span class='fk'>Roll No.</span><span class='fv'>".htmlspecialchars($s['roll_no'])."</span></div>
        <div class='fr'><span class='fk'>Contact</span><span class='fv'>".htmlspecialchars($s['contact_number'])."</span></div>
        <div class='warn'>This action is permanent and cannot be undone.</div>
        <div class='brow'>
            <a href='$cancel' class='b-cancel'>✖ Cancel</a>
            <a href='delete.php?roll_no=".urlencode($s['roll_no'])."&confirm=1' class='b-del'>🗑️ Delete Permanently</a>
        </div>
    </div>";
}

if($_SERVER['REQUEST_METHOD']==='GET'&&isset($_GET['roll_no'])&&isset($_GET['confirm'])&&$_GET['confirm']==='1'){
    $rn=trim(mysqli_real_escape_string($conn,$_GET['roll_no']));
    if(mysqli_query($conn,"DELETE FROM students WHERE roll_no='$rn'")){header("Location: view.php?deleted=1");exit;}
}
elseif($_SERVER['REQUEST_METHOD']==='GET'&&isset($_GET['roll_no'])&&!isset($_GET['confirm'])){
    $rn=trim(mysqli_real_escape_string($conn,$_GET['roll_no']));
    $res=mysqli_query($conn,"SELECT * FROM students WHERE roll_no='$rn'");
    $found=($res&&mysqli_num_rows($res)>0)?mysqli_fetch_assoc($res):null;
}
elseif($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['search_roll'])){
    $rn=trim(mysqli_real_escape_string($conn,$_POST['search_roll']));
    $res=mysqli_query($conn,"SELECT * FROM students WHERE roll_no='$rn'");
    $found=($res&&mysqli_num_rows($res)>0)?mysqli_fetch_assoc($res):null;
    $notFound=!$found;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Delete Student</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#0a0f1e;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:36px 16px;
background-image:radial-gradient(ellipse at 90% 10%,rgba(239,68,68,.16) 0,transparent 50%),radial-gradient(ellipse at 10% 90%,rgba(124,58,237,.10) 0,transparent 50%);}
.card{background:rgba(255,255,255,.03);border:1px solid rgba(239,68,68,.28);border-radius:24px;padding:36px 34px;width:100%;max-width:480px;backdrop-filter:blur(12px)}
.hd{display:flex;align-items:center;gap:14px;margin-bottom:22px}
.ico{width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#ef4444,#dc2626);display:flex;align-items:center;justify-content:center;font-size:1.3rem;box-shadow:0 0 20px rgba(239,68,68,.4)}
h2{font-size:1.45rem;font-weight:800;color:#f1f5f9}
.bc{font-size:.78rem;color:#475569;margin-top:2px}.bc a{color:#ef4444;text-decoration:none}
.div{height:1px;background:rgba(255,255,255,.07);margin:20px 0}
label{display:block;font-size:.78rem;font-weight:700;letter-spacing:.4px;text-transform:uppercase;color:#64748b;margin-bottom:8px}
.srow{display:flex;gap:10px}
input[type=text]{flex:1;padding:11px 14px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:10px;color:#e2e8f0;font-size:.93rem;font-family:inherit;outline:none;transition:border .2s}
input:focus{border-color:#ef4444;box-shadow:0 0 0 3px rgba(239,68,68,.18);background:rgba(239,68,68,.05)}
input::placeholder{color:#334155}
.btn{padding:11px 22px;background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;border:none;border-radius:10px;font-size:.93rem;font-weight:700;font-family:inherit;cursor:pointer;box-shadow:0 4px 16px rgba(239,68,68,.35);transition:transform .2s;white-space:nowrap}
.btn:hover{transform:translateY(-2px)}
.er{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171;padding:12px 14px;border-radius:10px;font-size:.87rem;font-weight:600;margin-top:16px}
/* Confirm box */
.conf{background:rgba(239,68,68,.07);border:1px solid rgba(239,68,68,.25);border-radius:16px;padding:22px;margin-top:22px}
.cl{font-size:.72rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#f87171;margin-bottom:14px}
.fr{display:flex;margin-bottom:8px}
.fk{flex:0 0 38%;font-size:.8rem;font-weight:700;color:#64748b}
.fv{font-size:.88rem;color:#cbd5e1}
.warn{background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.25);color:#fbbf24;padding:10px 13px;border-radius:9px;font-size:.8rem;font-weight:600;margin-top:14px}
.brow{display:flex;gap:10px;margin-top:16px}
.b-cancel{flex:1;text-align:center;padding:12px;background:rgba(255,255,255,.05);color:#94a3b8;border:1px solid rgba(255,255,255,.1);border-radius:10px;font-size:.88rem;font-weight:700;text-decoration:none;transition:background .2s}
.b-cancel:hover{background:rgba(255,255,255,.09)}
.b-del{flex:1;text-align:center;padding:12px;background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;border-radius:10px;font-size:.88rem;font-weight:700;text-decoration:none;box-shadow:0 4px 14px rgba(239,68,68,.35);transition:box-shadow .2s}
.b-del:hover{box-shadow:0 6px 22px rgba(239,68,68,.5)}
.back{display:block;text-align:center;margin-top:18px;color:#475569;font-size:.83rem;text-decoration:none;transition:color .2s}.back:hover{color:#ef4444}
</style>
</head>
<body>
<div class="card">
  <div class="hd"><div class="ico">🗑️</div><div><h2>Delete Student</h2><p class="bc"><a href="index.php">Home</a> › Delete</p></div></div>
  <div class="div"></div>

  <?php if(isset($found)&&$found){showConfirm($found,isset($_POST['search_roll'])?'manual':'view');}
  elseif(isset($notFound)&&$notFound){echo"<div class='er'>❌ No student found with Roll No. <strong>".htmlspecialchars($rn)."</strong>.</div>";}
  ?>

  <?php if(!isset($_GET['roll_no'])&&!isset($_POST['search_roll'])):?>
  <form method="POST"><label>Roll Number to Delete</label>
    <div class="srow">
      <input type="text" name="search_roll" placeholder="e.g. CS2024001">
      <button type="submit" class="btn">🔍 Find</button>
    </div>
  </form>
  <?php endif;?>
  <a href="index.php" class="back">← Back to Home</a>
</div>
</body></html>
