# Panduan Setup Google Sheets Integration

## Langkah 1: Setup Google Cloud Project

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Buat project baru atau pilih project yang sudah ada
3. Aktifkan Google Sheets API:
   - Buka "APIs & Services" > "Library"
   - Cari "Google Sheets API"
   - Klik dan aktifkan

## Langkah 2: Buat Service Account

1. Buka "APIs & Services" > "Credentials"
2. Klik "Create Credentials" > "Service Account"
3. Isi nama service account (misal: "monitoring-app")
4. Klik "Create and Continue"
5. Skip role assignment, klik "Continue"
6. Klik "Done"

## Langkah 3: Download Credentials

1. Klik service account yang baru dibuat
2. Buka tab "Keys"
3. Klik "Add Key" > "Create new key"
4. Pilih "JSON"
5. Download file JSON
6. Rename file menjadi `credentials.json`
7. Pindahkan ke folder aplikasi

## Langkah 4: Buat Google Spreadsheet

1. Buka [Google Sheets](https://sheets.google.com/)
2. Buat spreadsheet baru
3. Buat 2 sheet:
   - Sheet 1: "Durasi Lampu" (kolom: Lampu, Jam, Menit, Detik, Waktu Catat)
   - Sheet 2: "Energi Mesin" (kolom: Tanggal, Mesin, Energi)
4. Copy URL spreadsheet
5. Extract Spreadsheet ID dari URL:
   ```
   https://docs.google.com/spreadsheets/d/SPREADSHEET_ID/edit
   ```

## Langkah 5: Share Spreadsheet

1. Klik "Share" di spreadsheet
2. Tambahkan email service account (dari credentials.json)
3. Berikan permission "Editor"

## Langkah 6: Install Dependencies

```bash
composer install
```

## Langkah 7: Update Konfigurasi

1. Edit `google_sheets_integration.php`
2. Ganti `YOUR_SPREADSHEET_ID` dengan ID spreadsheet yang sudah dibuat
3. Pastikan file `credentials.json` ada di folder aplikasi

## Langkah 8: Update Form Actions

1. Edit `index.php`:
   - Ganti action form energi dari `save_energi.php` ke `save_energi_google.php`
   - Ganti fetch URL di JavaScript dari `save_duration.php` ke `save_duration_google.php`

## Struktur Spreadsheet

### Sheet: Durasi Lampu
| A (Lampu) | B (Jam) | C (Menit) | D (Detik) | E (Waktu Catat) |
|-----------|---------|-----------|-----------|-----------------|
| 1         | 0       | 5         | 30        | 2024-01-15 10:30:00 |

### Sheet: Energi Mesin
| A (Tanggal) | B (Mesin) | C (Energi) |
|-------------|-----------|------------|
| 2024-01-15  | 1         | 25.5       |

## Troubleshooting

1. **Error: "Google_Client not found"**
   - Pastikan composer install sudah dijalankan
   - Pastikan `vendor/autoload.php` ada

2. **Error: "Invalid credentials"**
   - Pastikan file credentials.json benar
   - Pastikan service account sudah di-share ke spreadsheet

3. **Error: "Permission denied"**
   - Pastikan service account memiliki akses "Editor" ke spreadsheet
   - Pastikan Google Sheets API sudah diaktifkan

4. **Data tidak tersimpan**
   - Cek error log PHP
   - Pastikan spreadsheet ID benar
   - Pastikan nama sheet sesuai ("Durasi Lampu" dan "Energi Mesin")
