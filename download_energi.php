<?php
$PIN = '123456';
$error = '';
$downloaded = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_pin = $_POST['pin'] ?? '';
    if ($input_pin === $PIN) {
        $filename = 'energi.csv';
        $downloadName = 'energi_harian.csv';
        $header = ['Tanggal', 'Mesin', 'Jumlah Energi (kWh)'];
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $downloadName . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        $output = fopen('php://output', 'w');
        fputcsv($output, $header, ';');
        if (file_exists($filename)) {
            $rows = file($filename);
            foreach ($rows as $row) {
                $data = str_getcsv(trim($row));
                if (count($data) === 3) {
                    fputcsv($output, $data, ';');
                }
            }
        }
        fclose($output);
        // Setelah download, redirect ke index.php (meta refresh)
        echo '<meta http-equiv="refresh" content="0;url=index.php">';
        exit;
    } else {
        $error = 'PIN salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>PIN Download Excel Energi</title>
    <style>
        body { font-family: Arial; background: #f7f7f7; }
        .box { background: #fff; padding: 30px; margin: 80px auto; width: 320px; border-radius: 8px; box-shadow: 0 2px 8px #aaa; }
        input { width: 100%; padding: 8px; margin: 8px 0; }
        button { width: 100%; padding: 10px; background: #4caf50; color: #fff; border: none; border-radius: 4px; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Masukkan PIN untuk Download Excel Energi</h2>
        <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post">
            <input type="password" name="pin" placeholder="PIN" required autofocus>
            <button type="submit">Download</button>
        </form>
        <a href="index.php">Kembali</a>
    </div>
</body>
</html> 