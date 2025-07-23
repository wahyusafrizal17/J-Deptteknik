<?php
require 'db.php';
session_start();
if (!isset($_SESSION['operator'])) {
    header('Location: login.php');
    exit;
}
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['id'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if ($id && $nama && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO operator (id, nama, password) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $id, $nama, $hash);
        if ($stmt->execute()) {
            $msg = 'Operator berhasil ditambahkan!';
        } else {
            $msg = 'Gagal menambah operator (ID sudah ada?)';
        }
        $stmt->close();
    } else {
        $msg = 'Semua field harus diisi!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Operator</title>
    <style>
        body { font-family: Arial; background: #f7f7f7; }
        .box { background: #fff; padding: 30px; margin: 80px auto; width: 340px; border-radius: 8px; box-shadow: 0 2px 8px #aaa; }
        input { width: 100%; padding: 8px; margin: 8px 0; }
        button { width: 100%; padding: 10px; background: #2196f3; color: #fff; border: none; border-radius: 4px; }
        .msg { color: green; margin-bottom: 10px; }
        .err { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
<?php include 'menu.php'; ?>
    <div class="box">
        <h2>Tambah Operator</h2>
        <?php if ($msg): ?>
            <div class="<?= strpos($msg, 'berhasil') !== false ? 'msg' : 'err' ?>"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        <form method="post">
            <label>ID</label>
            <input type="text" name="id" required autofocus>
            <label>Nama</label>
            <input type="text" name="nama" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit">Tambah</button>
        </form>
        <a href="login.php">Kembali ke Login</a>
    </div>
</body>
</html> 