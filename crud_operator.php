<?php
require 'db.php';
session_start();
if (!isset($_SESSION['operator'])) {
    header('Location: login.php');
    exit;
}
$msg = '';
$edit = false;
$id_edit = '';
$nama_edit = '';

// Hapus operator
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $conn->prepare('DELETE FROM operator WHERE id = ?');
    $stmt->bind_param('s', $id);
    if ($stmt->execute()) {
        $msg = 'Operator berhasil dihapus!';
    } else {
        $msg = 'Gagal menghapus operator!';
    }
    $stmt->close();
}

// Proses tambah
if (isset($_POST['tambah'])) {
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

// Proses edit (tampilkan form)
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $stmt = $conn->prepare('SELECT * FROM operator WHERE id = ?');
    $stmt->bind_param('s', $id_edit);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $edit = true;
        $nama_edit = $row['nama'];
    }
    $stmt->close();
}

// Proses update
if (isset($_POST['update'])) {
    $id = trim($_POST['id'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if ($id && $nama) {
        if ($password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('UPDATE operator SET nama = ?, password = ? WHERE id = ?');
            $stmt->bind_param('sss', $nama, $hash, $id);
        } else {
            $stmt = $conn->prepare('UPDATE operator SET nama = ? WHERE id = ?');
            $stmt->bind_param('ss', $nama, $id);
        }
        if ($stmt->execute()) {
            $msg = 'Operator berhasil diupdate!';
        } else {
            $msg = 'Gagal update operator!';
        }
        $stmt->close();
        $edit = false;
        $id_edit = '';
        $nama_edit = '';
    } else {
        $msg = 'Semua field harus diisi!';
    }
}

// Ambil semua data operator
$operators = [];
$res = $conn->query('SELECT * FROM operator ORDER BY id');
while ($row = $res->fetch_assoc()) {
    $operators[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CRUD Operator</title>
    <style>
        body { font-family: Arial; background: #f7f7f7; }
        .box { background: #fff; padding: 30px; margin: 40px auto; width: 420px; border-radius: 8px; box-shadow: 0 2px 8px #aaa; }
        input { width: 100%; padding: 8px; margin: 8px 0; }
        button, .btn { padding: 8px 16px; background: #2196f3; color: #fff; border: none; border-radius: 4px; text-decoration: none; }
        .btn-danger { background: #e53935; }
        .msg { color: green; margin-bottom: 10px; }
        .err { color: red; margin-bottom: 10px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: center; }
        th { background: #f0f0f0; }
        .actions { display: flex; gap: 8px; justify-content: center; }
    </style>
</head>
<body>
<?php include 'menu.php'; ?>
    <div class="box">
        <h2><?= $edit ? 'Edit' : 'Tambah' ?> Operator</h2>
        <?php if ($msg): ?>
            <div class="<?= strpos($msg, 'berhasil') !== false ? 'msg' : 'err' ?>"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        <form method="post">
            <label>ID</label>
            <input type="text" name="id" required value="<?= htmlspecialchars($edit ? $id_edit : '') ?>" <?= $edit ? 'readonly' : '' ?>>
            <label>Nama</label>
            <input type="text" name="nama" required value="<?= htmlspecialchars($edit ? $nama_edit : '') ?>">
            <label>Password <?= $edit ? '(kosongkan jika tidak ganti)' : '' ?></label>
            <input type="password" name="password" <?= $edit ? '' : 'required' ?>>
            <?php if ($edit): ?>
                <button type="submit" name="update">Update</button>
                <a href="crud_operator.php" class="btn">Batal</a>
            <?php else: ?>
                <button type="submit" name="tambah">Tambah</button>
            <?php endif; ?>
        </form>
        <h3>Daftar Operator</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($operators as $op): ?>
            <tr>
                <td><?= htmlspecialchars($op['id']) ?></td>
                <td><?= htmlspecialchars($op['nama']) ?></td>
                <td class="actions">
                    <a href="crud_operator.php?edit=<?= urlencode($op['id']) ?>" class="btn">Edit</a>
                    <a href="crud_operator.php?hapus=<?= urlencode($op['id']) ?>" class="btn btn-danger" onclick="return confirm('Yakin hapus operator ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <a href="login.php" class="btn" style="margin-top:16px;">Kembali ke Login</a>
    </div>
</body>
</html> 