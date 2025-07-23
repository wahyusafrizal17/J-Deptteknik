<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$nama = isset($_SESSION['operator']['nama']) ? $_SESSION['operator']['nama'] : '-';
?>
<div style="background:#2196f3;padding:12px 0 10px 0;margin-bottom:30px;">
  <div style="max-width:900px;margin:auto;display:flex;align-items:center;gap:18px;">
    <a href="index.php" style="color:#fff;text-decoration:none;font-weight:bold;">Home</a>
    <a href="operator.php" style="color:#fff;text-decoration:none;">Data Operator</a>
    <span style="flex:1"></span>
    <span style="color:#fff;">👤 <?= htmlspecialchars($nama) ?></span>
    <a href="logout.php" style="color:#fff;text-decoration:none;margin-left:16px;">Logout</a>
  </div>
</div> 