<?php
session_start();
if (!isset($_SESSION['operator'])) {
    header('Location: login.php');
    exit;
}

require_once 'google_sheets_integration.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mesin = $_POST['mesin'] ?? '';
    $energi = $_POST['energi'] ?? '';
    $tanggal = date('Y-m-d');
    
    if (empty($mesin) || empty($energi)) {
        header('Location: index.php?error=Data tidak lengkap');
        exit;
    }
    
    // Simpan ke file CSV lokal (backup)
    $csvData = "$tanggal,$mesin,$energi\n";
    file_put_contents('energi.csv', $csvData, FILE_APPEND | LOCK_EX);
    
    // Simpan ke Google Sheets
    try {
        $sheets = new GoogleSheetsIntegration('credentials.json', '1GxNsW37zFCmLfV27aqiJKm2PgBrohNg4i8oA1L4NccE');
        $success = $sheets->saveEnergy($tanggal, $mesin, $energi);
        
        if ($success) {
            header('Location: index.php?success=Data energi berhasil disimpan ke Google Sheets');
        } else {
            header('Location: index.php?error=Gagal menyimpan ke Google Sheets');
        }
    } catch (Exception $e) {
        header('Location: index.php?error=Error: ' . $e->getMessage());
    }
} else {
    header('Location: index.php');
}
?>
