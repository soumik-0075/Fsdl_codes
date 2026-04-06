<?php require_once 'db_connect.php';
$row=null;$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $rn=trim(mysqli_real_escape_string($conn,$_POST['roll_no']));
    if(!$rn){$error='Please enter a roll number.';}
    else{
        $res=mysqli_query($conn,"SELECT * FROM students WHERE roll_no='$rn'");
        if($res&&mysqli_num_rows($res)>0)$row=mysqli_fetch_assoc($res);
        else $error="No student found with Roll No. <strong>".htmlspecialchars($rn)."</strong>.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Search Student</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#0a0f1e;min-height:100vh;display:flex;align-items:flex-start;justify-content:center;padding:36px 16px;
background-image:radial-gradient(ellipse at 80% 10%,rgba(245,158,11,.14) 0,transparent 50%),radial-gradient(ellipse at 10% 90%,rgba(124,58,237,.10) 0,transparent 50%);}
.card{background:rgba(255,255,255,.03);border:1px solid rgba(245,158,11,.25);border-radius:24px;padding:36px 34px;width:100%;max-width:580px;backdrop-filter:blur(12px)}
.hd{display:flex;align-items:center;gap:14px;margin-bottom:22px}
.ico{width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;font-size:1.3rem;box-shadow:0 0 20px rgba(245,158,11,.4)}
h2{font-size:1.45rem;font-weight:800;color:#f1f5f9}
.bc{font-size:.78rem;color:#475569;margin-top:2px}.bc a{color:#f59e0b;text-decoration:none}
.div{height:1px;background:rgba(255,255,255,.07);margin:20px 0}
label{display:block;font-size:.78rem;font-weight:700;letter-spacing:.4px;text-transform:uppercase;color:#64748b;margin-bottom:8px}
.row{display:flex;gap:10px}
input[type=text]{flex:1;padding:11px 14px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:10px;color:#e2e8f0;font-size:.93rem;font-family:inherit;outline:none;transition:border .2s,box-shadow .2s}
input:focus{border-color:#f59e0b;box-shadow:0 0 0 3px rgba(245,158,11,.18);background:rgba(245,158,11,.06)}
input::placeholder{color:#334155}
.btn{padding:11px 22px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border:none;border-radius:10px;font-size:.93rem;font-weight:700;font-family:inherit;cursor:pointer;box-shadow:0 4px 16px rgba(245,158,11,.35);transition:transform .2s,box-shadow .2s;white-space:nowrap}
.btn:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(245,158,11,.5)}
.er{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171;padding:12px 14px;border-radius:10px;font-size:.87rem;font-weight:600;margin-top:18px}
.result{margin-top:26px}
.rl{font-size:.72rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#64748b;margin-bottom:14px}
.frow{display:flex;border:1px solid rgba(255,255,255,.07);border-radius:10px;overflow:hidden;margin-bottom:8px}
.frow:hover{border-color:rgba(245,158,11,.22)}
.fk{flex:0 0 38%;padding:12px 14px;background:rgba(245,158,11,.07);font-size:.8rem;font-weight:700;color:#92400e;border-right:1px solid rgba(255,255,255,.06);color:#fbbf24}
.fv{padding:12px 14px;font-size:.88rem;color:#cbd5e1}
.back{display:inline-block;margin-top:22px;color:#475569;font-size:.83rem;text-decoration:none;transition:color .2s}.back:hover{color:#f59e0b}
</style>
</head>
<body>
<div class="card">
  <div class="hd"><div class="ico">🔍</div><div><h2>Search Student</h2><p class="bc"><a href="index.php">Home</a> › Search</p></div></div>
  <div class="div"></div>
  <form method="POST">
    <label>Roll Number</label>
    <div class="row">
      <input type="text" name="roll_no" placeholder="e.g. CS2024001" value="<?=isset($_POST['roll_no'])?htmlspecialchars($_POST['roll_no']):''?>">
      <button type="submit" class="btn">Search</button>
    </div>
  </form>
  <?php if($error)echo"<div class='er'>❌ $error</div>";?>
  <?php if($row):?>
  <div class="result">
    <div class="rl">🎓 Record Found</div>
    <?php foreach(['ID'=>$row['id'],'First Name'=>$row['first_name'],'Last Name'=>$row['last_name'],'Roll No.'=>$row['roll_no'],'Contact'=>$row['contact_number']] as $k=>$v):?>
      <div class="frow"><div class="fk"><?=$k?></div><div class="fv"><?=htmlspecialchars($v)?></div></div>
    <?php endforeach;?>
  </div>
  <?php endif;?>
  <a href="index.php" class="back">← Back to Home</a>
</div>
</body></html>
