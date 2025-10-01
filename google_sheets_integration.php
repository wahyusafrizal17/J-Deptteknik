<?php
// Google Sheets Integration
// Pastikan sudah menginstall Google API Client: composer require google/apiclient

require_once 'vendor/autoload.php';

class GoogleSheetsIntegration {
    private $service;
    private $spreadsheetId;
    
    public function __construct($credentialsPath, $spreadsheetId) {
        $this->spreadsheetId = $spreadsheetId;
        $this->service = $this->getGoogleSheetsService($credentialsPath);
    }
    
    private function getGoogleSheetsService($credentialsPath) {
        $client = new Google_Client();
        $client->setAuthConfig($credentialsPath);
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        
        return new Google_Service_Sheets($client);
    }
    
    // Simpan data durasi lampu ke Google Sheets (semua lampu dalam 1 sheet, kolom terpisah)
    public function saveDuration($lampu, $tanggal, $waktuDinyalakan, $waktuDimatikan, $waktuPenggunaan) {
        // Semua lampu dalam sheet "New Durasi Lampu"
        $sheetName = 'New Durasi Lampu';
        
        // Hitung kolom berdasarkan nomor lampu
        // Lampu 1: A-D, Lampu 2: F-H (skip E), Lampu 3: J-L (skip I), dst
        // Pola: kolom awal = (lampu-1) * 4
        $startCol = ($lampu - 1) * 4;
        $columnLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 
                         'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF'];
        
        // Lampu 1: A-D (Tanggal, Waktu Dinyalakan, Waktu Dimatikan, Durasi Penggunaan)
        // Lampu 2: F-H (Waktu Dinyalakan, Waktu Dimatikan, Durasi Penggunaan) - tanpa Tanggal
        
        if ($lampu == 1) {
            // Lampu 1 dengan kolom Tanggal
            $range = $sheetName . '!' . $columnLetters[$startCol] . ':' . $columnLetters[$startCol + 3];
            $values = [[
                $tanggal,
                $waktuDinyalakan,
                $waktuDimatikan,
                $waktuPenggunaan
            ]];
        } else {
            // Lampu 2-8 tanpa kolom Tanggal, mulai dari kolom yang sesuai
            // Lampu 2: F-H (index 5-7)
            $colStart = ($lampu - 1) * 4 + 1; // Skip 1 kolom kosong
            $range = $sheetName . '!' . $columnLetters[$colStart] . ':' . $columnLetters[$colStart + 2];
            $values = [[
                $waktuDinyalakan,
                $waktuDimatikan,
                $waktuPenggunaan
            ]];
        }
        
        $body = new Google_Service_Sheets_ValueRange([
            'values' => $values
        ]);
        
        $params = [
            'valueInputOption' => 'USER_ENTERED' // Agar format waktu otomatis terdeteksi
        ];
        
        try {
            $this->service->spreadsheets_values->append(
                $this->spreadsheetId,
                $range,
                $body,
                $params
            );
            return true;
        } catch (Exception $e) {
            error_log('Error saving to Google Sheets: ' . $e->getMessage());
            return false;
        }
    }
    
    // Simpan data energi ke Google Sheets
    public function saveEnergy($tanggal, $mesin, $energi) {
        $range = 'Energi Mesin!A:C';
        $values = [[
            $tanggal,
            $mesin,
            $energi
        ]];
        
        $body = new Google_Service_Sheets_ValueRange([
            'values' => $values
        ]);
        
        $params = [
            'valueInputOption' => 'RAW'
        ];
        
        try {
            $this->service->spreadsheets_values->append(
                $this->spreadsheetId,
                $range,
                $body,
                $params
            );
            return true;
        } catch (Exception $e) {
            error_log('Error saving to Google Sheets: ' . $e->getMessage());
            return false;
        }
    }
    
    // Baca data dari Google Sheets
    public function readData($range) {
        try {
            $response = $this->service->spreadsheets_values->get(
                $this->spreadsheetId,
                $range
            );
            return $response->getValues();
        } catch (Exception $e) {
            error_log('Error reading from Google Sheets: ' . $e->getMessage());
            return [];
        }
    }
}

// Konfigurasi
$credentialsPath = 'credentials.json'; // File credentials dari Google Cloud Console
$spreadsheetId = '1GxNsW37zFCmLfV27aqiJKm2PgBrohNg4i8oA1L4NccE'; // ID spreadsheet dari URL Google Sheets

// Inisialisasi
$sheets = new GoogleSheetsIntegration($credentialsPath, $spreadsheetId);
?>
