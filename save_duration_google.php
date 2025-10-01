<?php
session_start();
if (!isset($_SESSION['operator'])) {
    http_response_code(403);
    exit('Unauthorized');
}

require_once 'google_sheets_integration.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    date_default_timezone_set('Asia/Jakarta'); // Set timezone ke WIB
    $lampu = $_POST['lampu'] ?? '';
    $jam = $_POST['jam'] ?? 0;
    $menit = $_POST['menit'] ?? 0;
    $detik = $_POST['detik'] ?? 0;
    
    // Data baru untuk Google Sheets
    $waktuDinyalakan = $_POST['waktu_dinyalakan'] ?? '';
    $waktuDimatikan = $_POST['waktu_dimatikan'] ?? '';
    $waktuPenggunaan = $_POST['waktu_penggunaan'] ?? '';
    $tanggal = date('Y-m-d'); // Tanggal untuk kolom Tanggal di Lampu 1
    
    // Simpan ke file CSV lokal (backup) - format lama
    $waktuCatat = date('Y-m-d H:i:s');
    $csvData = "$lampu,$jam,$menit,$detik,$waktuCatat\n";
    file_put_contents('durasi.csv', $csvData, FILE_APPEND | LOCK_EX);
    
    // Simpan ke Google Sheets dengan format baru (1 sheet, kolom terpisah per lampu)
    try {
        $sheets = new GoogleSheetsIntegration('credentials.json', '1GxNsW37zFCmLfV27aqiJKm2PgBrohNg4i8oA1L4NccE');
        $success = $sheets->saveDuration($lampu, $tanggal, $waktuDinyalakan, $waktuDimatikan, $waktuPenggunaan);
        
        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan ke Google Sheets']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan ke Google Sheets']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    exit('Method not allowed');
}
?>
