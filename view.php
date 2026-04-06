<?php require_once 'db_connect.php';
$result=mysqli_query($conn,"SELECT * FROM students ORDER BY id ASC");
$count=$result?mysqli_num_rows($result):0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>View Students</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#0a0f1e;min-height:100vh;padding:34px 20px;
background-image:radial-gradient(ellipse at 60% 0%,rgba(6,182,212,.13) 0,transparent 55%),radial-gradient(ellipse at 30% 100%,rgba(124,58,237,.10) 0,transparent 55%);}
.wrapper{max-width:1050px;margin:0 auto}
.top{display:flex;align-items:center;justify-content:space-between;margin-bottom:26px;flex-wrap:wrap;gap:14px}
.tl{display:flex;align-items:center;gap:14px}
.ico{width:50px;height:50px;border-radius:14px;background:linear-gradient(135deg,#06b6d4,#0284c7);display:flex;align-items:center;justify-content:center;font-size:1.4rem;box-shadow:0 0 20px rgba(6,182,212,.4)}
h2{font-size:1.55rem;font-weight:800;color:#f1f5f9}
.bc{font-size:.78rem;color:#475569;margin-top:2px}.bc a{color:#06b6d4;text-decoration:none}
.pill{background:rgba(6,182,212,.12);border:1px solid rgba(6,182,212,.3);color:#06b6d4;font-size:.78rem;font-weight:700;padding:6px 14px;border-radius:999px}
.ok{background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);color:#34d399;padding:11px 16px;border-radius:10px;font-size:.87rem;font-weight:600;margin-bottom:18px}
.tbl-wrap{background:rgba(255,255,255,.025);border:1px solid rgba(6,182,212,.2);border-radius:20px;overflow:hidden;backdrop-filter:blur(8px)}
table{width:100%;border-collapse:collapse}
thead th{background:rgba(6,182,212,.08);color:#06b6d4;padding:14px 16px;font-size:.73rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;text-align:left}
tbody td{padding:13px 16px;font-size:.88rem;color:#cbd5e1;border-top:1px solid rgba(255,255,255,.05)}
tbody tr:hover td{background:rgba(6,182,212,.04)}
.badge{display:inline-block;background:rgba(6,182,212,.12);border:1px solid rgba(6,182,212,.25);color:#22d3ee;padding:3px 10px;border-radius:7px;font-size:.78rem;font-weight:700}
.btn-e,.btn-d{display:inline-flex;align-items:center;gap:4px;padding:5px 12px;border-radius:8px;font-size:.77rem;font-weight:700;text-decoration:none;transition:all .2s;margin-right:4px}
.btn-e{background:rgba(99,102,241,.12);color:#818cf8;border:1px solid rgba(99,102,241,.25)}.btn-e:hover{background:#6366f1;color:#fff}
.btn-d{background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.25)}.btn-d:hover{background:#ef4444;color:#fff}
.empty{text-align:center;padding:50px;color:#334155}
.back{display:inline-block;margin-top:22px;color:#475569;font-size:.85rem;text-decoration:none;transition:color .2s}.back:hover{color:#06b6d4}
</style>
</head>
<body>
<div class="wrapper">
  <div class="top">
    <div class="tl">
      <div class="ico">📋</div>
      <div><h2>All Students</h2><p class="bc"><a href="index.php">Home</a> › View</p></div>
    </div>
    <div class="pill"><?=$count?> Record<?=$count!=1?'s':''?></div>
  </div>
  <?php if(isset($_GET['deleted'])&&$_GET['deleted']==='1')echo"<div class='ok'>✅ Student deleted successfully!</div>";?>
  <div class="tbl-wrap">
    <table>
      <thead><tr><th>#</th><th>First Name</th><th>Last Name</th><th>Roll No.</th><th>Contact</th><th>Actions</th></tr></thead>
      <tbody>
      <?php if($result&&mysqli_num_rows($result)>0):while($r=mysqli_fetch_assoc($result)):?>
        <tr>
          <td><?=$r['id']?></td>
          <td><?=htmlspecialchars($r['first_name'])?></td>
          <td><?=htmlspecialchars($r['last_name'])?></td>
          <td><span class="badge"><?=htmlspecialchars($r['roll_no'])?></span></td>
          <td><?=htmlspecialchars($r['contact_number'])?></td>
          <td>
            <a href="update.php?roll_no=<?=urlencode($r['roll_no'])?>" class="btn-e">✏️ Edit</a>
            <a href="delete.php?roll_no=<?=urlencode($r['roll_no'])?>" class="btn-d" onclick="return confirm('Delete <?=htmlspecialchars(addslashes($r['first_name']))?> ?')">🗑️ Del</a>
          </td>
        </tr>
      <?php endwhile;else:?>
        <tr><td colspan="6" class="empty">No records yet. <a href="insert.php" style="color:#06b6d4">Add one?</a></td></tr>
      <?php endif;?>
      </tbody>
    </table>
  </div>
  <a href="index.php" class="back">← Back to Home</a>
</div>
</body></html>
