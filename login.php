<?php
session_start();
require 'db.php';
if (isset($_SESSION['operator'])) {
    header('Location: index.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if ($nama && $password) {
        $stmt = $conn->prepare('SELECT * FROM operator WHERE nama = ?');
        $stmt->bind_param('s', $nama);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['operator'] = ['id' => $row['id'], 'nama' => $row['nama']];
                header('Location: index.php');
                exit;
            } else {
                $error = 'Password salah!';
            }
        } else {
            $error = 'Nama operator tidak ditemukan!';
        }
        $stmt->close();
    } else {
        $error = 'Semua field harus diisi!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Operator</title>
    <style>
        body { font-family: Arial; background: #f7f7f7; }
        .login-box { background: #fff; padding: 30px; margin: 80px auto; width: 320px; border-radius: 8px; box-shadow: 0 2px 8px #aaa; }
        input { width: 100%; padding: 8px; margin: 8px 0; }
        button { width: 100%; padding: 10px; background: #4caf50; color: #fff; border: none; border-radius: 4px; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login Operator</h2>
        <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post">
            <label>Nama</label>
            <input type="text" name="nama" required autofocus>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <!-- <a href="tambah_operator.php">Tambah Operator Baru</a> -->
    </div>
</body>
</html> 