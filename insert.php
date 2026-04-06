<?php
require_once 'db_connect.php';
$success=$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $fn=trim(mysqli_real_escape_string($conn,$_POST['first_name']));
    $ln=trim(mysqli_real_escape_string($conn,$_POST['last_name']));
    $rn=trim(mysqli_real_escape_string($conn,$_POST['roll_no']));
    $pw=$_POST['password'];
    $cp=$_POST['confirm_password'];
    $cn=trim(mysqli_real_escape_string($conn,$_POST['contact_number']));
    if(!$fn||!$ln||!$rn||!$pw||!$cp||!$cn){$error='All fields are required.';}
    elseif($pw!==$cp){$error='Passwords do not match.';}
    elseif(!preg_match('/^\d{10}$/',$cn)){$error='Contact must be exactly 10 digits.';}
    else{
        $chk=mysqli_query($conn,"SELECT id FROM students WHERE roll_no='$rn'");
        if(mysqli_num_rows($chk)>0){$error='Roll No. already exists.';}
        else{
            $hp=password_hash($pw,PASSWORD_DEFAULT);
            $hc=password_hash($cp,PASSWORD_DEFAULT);
            $sql="INSERT INTO students(first_name,last_name,roll_no,password,confirm_password,contact_number)VALUES('$fn','$ln','$rn','$hp','$hc','$cn')";
            if(mysqli_query($conn,$sql))$success='Student inserted successfully!';
            else $error='DB Error: '.mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Insert Student</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#0a0f1e;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:30px 16px;
background-image:radial-gradient(ellipse at 0% 0%,rgba(124,58,237,.18) 0,transparent 55%),radial-gradient(ellipse at 100% 100%,rgba(6,182,212,.12) 0,transparent 55%);}
.card{background:rgba(255,255,255,.03);border:1px solid rgba(124,58,237,.3);border-radius:24px;padding:36px 34px;width:100%;max-width:480px;backdrop-filter:blur(12px);}
.hd{display:flex;align-items:center;gap:14px;margin-bottom:24px}
.hd-icon{width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#7c3aed,#6d28d9);display:flex;align-items:center;justify-content:center;font-size:1.3rem;box-shadow:0 0 20px rgba(124,58,237,.4)}
.hd h2{font-size:1.45rem;font-weight:800;color:#f1f5f9}
.bc{font-size:.78rem;color:#475569;margin-top:2px}.bc a{color:#7c3aed;text-decoration:none}
.div{height:1px;background:rgba(255,255,255,.07);margin:20px 0}
label{display:block;font-size:.78rem;font-weight:700;letter-spacing:.4px;text-transform:uppercase;color:#64748b;margin:14px 0 6px}
input[type=text],input[type=password]{width:100%;padding:11px 14px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:10px;color:#e2e8f0;font-size:.93rem;font-family:inherit;outline:none;transition:border .2s,box-shadow .2s}
input:focus{border-color:#7c3aed;box-shadow:0 0 0 3px rgba(124,58,237,.2);background:rgba(124,58,237,.07)}
input::placeholder{color:#334155}
.err{color:#f87171;font-size:.76rem;margin-top:4px;display:none}
.btn{width:100%;padding:13px;margin-top:24px;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;border:none;border-radius:12px;font-size:.95rem;font-weight:700;font-family:inherit;cursor:pointer;letter-spacing:.3px;box-shadow:0 4px 18px rgba(124,58,237,.4);transition:transform .2s,box-shadow .2s}
.btn:hover{transform:translateY(-3px);box-shadow:0 8px 28px rgba(124,58,237,.55)}
.ok{background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.35);color:#34d399;padding:12px 14px;border-radius:10px;font-size:.88rem;font-weight:600;margin-bottom:16px}
.er{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#f87171;padding:12px 14px;border-radius:10px;font-size:.88rem;font-weight:600;margin-bottom:16px}
.back{display:block;text-align:center;margin-top:16px;color:#475569;font-size:.83rem;text-decoration:none;transition:color .2s}.back:hover{color:#7c3aed}
</style>
</head>
<body>
<div class="card">
  <div class="hd">
    <div class="hd-icon">➕</div>
    <div><h2>Insert Student</h2><p class="bc"><a href="index.php">Home</a> › Insert</p></div>
  </div>
  <div class="div"></div>
  <?php if($success)echo"<div class='ok'>✅ $success</div>";if($error)echo"<div class='er'>❌ $error</div>";?>
  <form method="POST" id="F" novalidate>
    <label>First Name</label>
    <input type="text" name="first_name" id="fn" placeholder="Rahul" value="<?=isset($_POST['first_name'])?htmlspecialchars($_POST['first_name']):''?>">
    <span class="err" id="e_fn">Required.</span>

    <label>Last Name</label>
    <input type="text" name="last_name" id="ln" placeholder="Sharma" value="<?=isset($_POST['last_name'])?htmlspecialchars($_POST['last_name']):''?>">
    <span class="err" id="e_ln">Required.</span>

    <label>Roll Number</label>
    <input type="text" name="roll_no" id="rn" placeholder="CS2024001" value="<?=isset($_POST['roll_no'])?htmlspecialchars($_POST['roll_no']):''?>">
    <span class="err" id="e_rn">Required.</span>

    <label>Password</label>
    <input type="password" name="password" id="pw" placeholder="Create a password">
    <span class="err" id="e_pw">Required.</span>

    <label>Confirm Password</label>
    <input type="password" name="confirm_password" id="cp" placeholder="Repeat password">
    <span class="err" id="e_cp">Passwords do not match.</span>

    <label>Contact Number</label>
    <input type="text" name="contact_number" id="cn" placeholder="10-digit number" maxlength="10" value="<?=isset($_POST['contact_number'])?htmlspecialchars($_POST['contact_number']):''?>">
    <span class="err" id="e_cn">Must be exactly 10 digits.</span>

    <button type="submit" class="btn">Insert Student →</button>
  </form>
  <a href="index.php" class="back">← Back to Home</a>
</div>
<script>
document.getElementById('F').addEventListener('submit',function(e){
  let ok=true;const s=(id,b)=>document.getElementById(id).style.display=b?'block':'none';
  const g=id=>document.getElementById(id).value;
  s('e_fn',!g('fn').trim());if(!g('fn').trim())ok=false;
  s('e_ln',!g('ln').trim());if(!g('ln').trim())ok=false;
  s('e_rn',!g('rn').trim());if(!g('rn').trim())ok=false;
  s('e_pw',!g('pw'));if(!g('pw'))ok=false;
  const cm=g('pw')&&g('cp')&&g('pw')!==g('cp');s('e_cp',cm);if(cm)ok=false;
  const cnb=!/^\d{10}$/.test(g('cn').trim());s('e_cn',cnb);if(cnb)ok=false;
  if(!ok)e.preventDefault();
});
</script>
</body></html>
