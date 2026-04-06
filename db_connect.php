<?php
$host     = "localhost";
$user     = "root";
$password = "";
$database = "lab4_db";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("<div style='background:#fff3cd;color:#856404;border:1px solid #ffc107;
         padding:20px;font-family:Arial;border-radius:8px;margin:20px;'>
         <strong>⚠ Connection Failed:</strong> " . mysqli_connect_error() . "
         </div>");
}
?>
