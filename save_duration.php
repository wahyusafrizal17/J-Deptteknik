<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    date_default_timezone_set('Asia/Jakarta'); // Set timezone ke WIB
    $lampu = isset($_POST['lampu']) ? intval($_POST['lampu']) : 0;
    $jam = isset($_POST['jam']) ? intval($_POST['jam']) : 0;
    $menit = isset($_POST['menit']) ? intval($_POST['menit']) : 0;
    $detik = isset($_POST['detik']) ? intval($_POST['detik']) : 0;
    $waktu = date('Y-m-d H:i:s');
    if ($lampu >= 1 && $lampu <= 8) {
        $row = [$lampu, $jam, $menit, $detik, $waktu];
        $fp = fopen('durasi.csv', 'a');
        fputcsv($fp, $row);
        fclose($fp);
        http_response_code(200);
        exit('OK');
    }
}
http_response_code(400);
echo 'Invalid'; 