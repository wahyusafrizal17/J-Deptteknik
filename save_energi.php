<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    date_default_timezone_set('Asia/Jakarta'); // Set timezone ke WIB
    $mesin = isset($_POST['mesin']) ? intval($_POST['mesin']) : 0;
    $energi = isset($_POST['energi']) ? floatval($_POST['energi']) : 0;
    $tanggal = date('Y-m-d');
    if ($mesin >= 1 && $mesin <= 2 && $energi > 0) {
        $row = [$tanggal, $mesin, $energi];
        $fp = fopen('energi.csv', 'a');
        fputcsv($fp, $row);
        fclose($fp);
        header('Location: index.php');
        exit;
    }
}
header('Location: index.php');
exit; 