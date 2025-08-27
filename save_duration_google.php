<?php
session_start();
if (!isset($_SESSION['operator'])) {
    http_response_code(403);
    exit('Unauthorized');
}

require_once 'google_sheets_integration.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lampu = $_POST['lampu'] ?? '';
    $jam = $_POST['jam'] ?? 0;
    $menit = $_POST['menit'] ?? 0;
    $detik = $_POST['detik'] ?? 0;
    $waktuCatat = date('Y-m-d H:i:s');
    
    // Simpan ke file CSV lokal (backup)
    $csvData = "$lampu,$jam,$menit,$detik,$waktuCatat\n";
    file_put_contents('durasi.csv', $csvData, FILE_APPEND | LOCK_EX);
    
    // Simpan ke Google Sheets
    try {
        $sheets = new GoogleSheetsIntegration('credentials.json', '1GxNsW37zFCmLfV27aqiJKm2PgBrohNg4i8oA1L4NccE');
        $success = $sheets->saveDuration($lampu, $jam, $menit, $detik, $waktuCatat);
        
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
