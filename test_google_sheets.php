<?php
// Test file untuk memverifikasi integrasi Google Sheets
require_once 'google_sheets_integration.php';

echo "<h1>Test Google Sheets Integration</h1>";

// Test 1: Cek apakah credentials.json ada
if (file_exists('credentials.json')) {
    echo "✅ File credentials.json ditemukan<br>";
} else {
    echo "❌ File credentials.json tidak ditemukan<br>";
    echo "Silakan download credentials dari Google Cloud Console<br>";
}

// Test 2: Cek apakah vendor/autoload.php ada
if (file_exists('vendor/autoload.php')) {
    echo "✅ Composer dependencies terinstall<br>";
} else {
    echo "❌ Composer dependencies belum terinstall<br>";
    echo "Jalankan: composer install<br>";
}

// Test 3: Cek apakah Google API Client bisa di-load
try {
    require_once 'vendor/autoload.php';
    echo "✅ Google API Client berhasil di-load<br>";
} catch (Exception $e) {
    echo "❌ Error loading Google API Client: " . $e->getMessage() . "<br>";
}

// Test 4: Cek koneksi ke Google Sheets (jika credentials ada)
if (file_exists('credentials.json')) {
    try {
        $sheets = new GoogleSheetsIntegration('credentials.json', '1GxNsW37zFCmLfV27aqiJKm2PgBrohNg4i8oA1L4NccE');
        echo "✅ Google Sheets Integration berhasil diinisialisasi<br>";
        echo "✅ Spreadsheet ID sudah dikonfigurasi: 1GxNsW37zFCmLfV27aqiJKm2PgBrohNg4i8oA1L4NccE<br>";
    } catch (Exception $e) {
        echo "❌ Error inisialisasi Google Sheets: " . $e->getMessage() . "<br>";
    }
}

echo "<br><h2>Langkah selanjutnya:</h2>";
echo "1. Download credentials.json dari Google Cloud Console<br>";
echo "2. Buat Google Spreadsheet dengan 2 sheet: 'Durasi Lampu' dan 'Energi Mesin'<br>";
echo "3. Share spreadsheet dengan service account email<br>";
echo "4. Update spreadsheet ID di google_sheets_integration.php<br>";
echo "5. Test dengan menyalakan dan mematikan lampu<br>";
?>
