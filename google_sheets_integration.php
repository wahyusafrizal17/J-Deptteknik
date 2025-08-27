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
    
    // Simpan data durasi lampu ke Google Sheets
    public function saveDuration($lampu, $jam, $menit, $detik, $waktuCatat) {
        $range = 'Durasi Lampu!A:E';
        $values = [[
            $lampu,
            $jam,
            $menit,
            $detik,
            $waktuCatat
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
