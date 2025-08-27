# Integrasi Google Sheets untuk Monitoring Beban Listrik

Aplikasi ini sekarang dapat menyimpan data ke Google Sheets secara otomatis. Data yang tersimpan meliputi:
- Durasi lampu menyala (jam, menit, detik)
- Konsumsi energi harian mesin

## Fitur Baru

✅ **Auto-sync ke Google Sheets** - Data langsung tersimpan ke spreadsheet online  
✅ **Backup lokal** - Data tetap tersimpan di file CSV sebagai backup  
✅ **Real-time** - Data langsung muncul di Google Sheets saat tombol ditekan  
✅ **Multi-user** - Bisa diakses dari mana saja dengan Google Sheets  

## File yang Ditambahkan

1. `google_sheets_integration.php` - Class untuk integrasi Google Sheets
2. `save_duration_google.php` - Handler untuk menyimpan durasi lampu ke Google Sheets
3. `save_energi_google.php` - Handler untuk menyimpan energi mesin ke Google Sheets
4. `composer.json` - Dependencies untuk Google API Client
5. `setup_google_sheets.md` - Panduan setup lengkap

## Cara Setup (Step by Step)

### 1. Install Composer Dependencies
```bash
composer install
```

### 2. Setup Google Cloud Project
1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Buat project baru
3. Aktifkan Google Sheets API

### 3. Buat Service Account
1. Buka "APIs & Services" > "Credentials"
2. Klik "Create Credentials" > "Service Account"
3. Download file JSON credentials
4. Rename menjadi `credentials.json`
5. Pindahkan ke folder aplikasi

### 4. Buat Google Spreadsheet
1. Buat spreadsheet baru di Google Sheets
2. Buat 2 sheet:
   - **Durasi Lampu**: Kolom A-E (Lampu, Jam, Menit, Detik, Waktu Catat)
   - **Energi Mesin**: Kolom A-C (Tanggal, Mesin, Energi)
3. Copy Spreadsheet ID dari URL

### 5. Share Spreadsheet
1. Klik "Share" di spreadsheet
2. Tambahkan email service account (dari credentials.json)
3. Berikan permission "Editor"

### 6. Update Konfigurasi
Edit file `google_sheets_integration.php`:
```php
$spreadsheetId = 'YOUR_ACTUAL_SPREADSHEET_ID';
```

## Cara Kerja

### Saat Lampu Dimatikan
1. JavaScript menghitung durasi lampu menyala
2. Data dikirim ke `save_duration_google.php`
3. Data disimpan ke file CSV lokal (backup)
4. Data dikirim ke Google Sheets via API
5. Halaman di-refresh untuk menampilkan data terbaru

### Saat Input Energi
1. Form dikirim ke `save_energi_google.php`
2. Data disimpan ke file CSV lokal (backup)
3. Data dikirim ke Google Sheets via API
4. Redirect kembali ke halaman utama

## Struktur Data di Google Sheets

### Sheet: Durasi Lampu
| Lampu | Jam | Menit | Detik | Waktu Catat |
|-------|-----|-------|-------|-------------|
| 1     | 0   | 5     | 30    | 2024-01-15 10:30:00 |
| 2     | 1   | 0     | 15    | 2024-01-15 11:45:00 |

### Sheet: Energi Mesin
| Tanggal    | Mesin | Energi |
|------------|-------|--------|
| 2024-01-15 | 1     | 25.5   |
| 2024-01-15 | 2     | 30.2   |

## Keuntungan

1. **Akses Online** - Data bisa diakses dari mana saja
2. **Backup Otomatis** - Data tersimpan di cloud dan lokal
3. **Kolaborasi** - Bisa diakses oleh multiple user
4. **Analisis** - Bisa dibuat grafik dan laporan di Google Sheets
5. **Notifikasi** - Bisa setup notifikasi email/WhatsApp

## Troubleshooting

### Error: "Google_Client not found"
```bash
composer install
```

### Error: "Invalid credentials"
- Pastikan file `credentials.json` benar
- Pastikan service account sudah di-share ke spreadsheet

### Error: "Permission denied"
- Pastikan service account memiliki akses "Editor"
- Pastikan Google Sheets API sudah diaktifkan

### Data tidak tersimpan
- Cek error log PHP
- Pastikan spreadsheet ID benar
- Pastikan nama sheet sesuai

## Backup dan Restore

Data tetap tersimpan di file lokal:
- `durasi.csv` - Backup data durasi lampu
- `energi.csv` - Backup data energi mesin

Jika Google Sheets bermasalah, aplikasi tetap bisa berjalan dengan data lokal.

## Keamanan

- File `credentials.json` berisi kunci API, jangan di-share
- Tambahkan `credentials.json` ke `.gitignore`
- Gunakan HTTPS untuk production
- Batasi akses service account hanya ke spreadsheet yang diperlukan
