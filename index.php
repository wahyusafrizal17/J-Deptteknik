<?php
session_start();
if (!isset($_SESSION['operator'])) {
    header('Location: login.php');
    exit;
}
$operator = $_SESSION['operator'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Monitoring Beban Listrik</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .lampu-form { border: 1px solid #ccc; padding: 20px; margin-bottom: 20px; display: inline-block; width: 320px; vertical-align: top; }
        /* .timer { font-size: 1.5em; margin: 10px 0; } */
        table { border-collapse: collapse; width: 100%; margin-top: 30px; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: center; }
        th { background: #f0f0f0; }
        button { padding: 8px 16px; margin: 2px; }
        .operator-info { display:none; }
        .logout-btn { display:none; }
    </style>
</head>
<body>
<?php include 'menu.php'; ?>
    <h1>Monitoring Beban Listrik</h1>
    <div id="forms-container">
        <!-- Form Lampu 1-8 -->
        <?php for ($i = 1; $i <= 8; $i++): ?>
        <div class="lampu-form" id="form-lampu-<?= $i ?>">
            <h3>Lampu <?= $i ?></h3>
            <!-- <div class="timer" id="timer-<?= $i ?>">00:00:00</div> -->
            <button onclick="nyalakan(<?= $i ?>)" id="nyala-<?= $i ?>">Nyala</button>
            <button onclick="matikan(<?= $i ?>)" id="mati-<?= $i ?>" disabled>Mati</button>
        </div>
        <?php endfor; ?>
    </div>
    <h2>Input Energi Harian Mesin</h2>
    <form method="post" action="save_energi.php" style="display:inline-block;margin-right:30px;">
        <input type="hidden" name="mesin" value="1">
        <label>Mesin 1: </label>
        <input type="number" name="energi" step="0.01" min="0" required style="width:100px;"> kWh
        <button type="submit">Submit</button>
    </form>
    <form method="post" action="save_energi.php" style="display:inline-block;">
        <input type="hidden" name="mesin" value="2">
        <label>Mesin 2: </label>
        <input type="number" name="energi" step="0.01" min="0" required style="width:100px;"> kWh
        <button type="submit">Submit</button>
    </form>
    <h3>Riwayat Energi Harian</h3>
    <a href="download_energi.php" style="display:inline-block;margin-bottom:10px;padding:8px 16px;background:#4caf50;color:#fff;text-decoration:none;border-radius:4px;">Download Excel Energi</a>
    <table>
        <tr>
            <th>Tanggal</th>
            <th>Mesin</th>
            <th>Jumlah Energi (kWh)</th>
        </tr>
        <?php
        if (file_exists('energi.csv')) {
            $rows = array_reverse(file('energi.csv'));
            foreach ($rows as $row) {
                $data = str_getcsv(trim($row));
                if (count($data) === 3) {
                    echo '<tr>';
                    foreach ($data as $cell) {
                        echo '<td>' . htmlspecialchars($cell) . '</td>';
                    }
                    echo '</tr>';
                }
            }
        }
        ?>
    </table>
    <h2>Riwayat Durasi Lampu</h2>
    <a href="download_excel.php" style="display:inline-block;margin-bottom:10px;padding:8px 16px;background:#4caf50;color:#fff;text-decoration:none;border-radius:4px;">Download Excel</a>
    <table>
        <tr>
            <th>Lampu</th>
            <th>Jam</th>
            <th>Menit</th>
            <th>Detik</th>
            <th>Waktu Catat</th>
        </tr>
        <?php
        if (file_exists('durasi.csv')) {
            $rows = array_reverse(file('durasi.csv'));
            foreach ($rows as $row) {
                $data = str_getcsv(trim($row));
                if (count($data) === 5) {
                    echo '<tr>';
                    foreach ($data as $cell) {
                        echo '<td>' . htmlspecialchars($cell) . '</td>';
                    }
                    echo '</tr>';
                }
            }
        }
        ?>
    </table>
    <script>
    // Timer logic per lampu
    // Pastikan array cukup panjang untuk 8 lampu (index 1-8)
    let timers = Array(9).fill(null); // index 1-8
    let startTimes = Array(9).fill(null);
    let elapsed = Array(9).fill(0);

    function pad(n) { return n.toString().padStart(2, '0'); }

    function nyalakan(idx) {
        console.log('nyalakan', idx);
        if (idx < 1 || idx > 8) return;
        if (timers[idx]) return;
        startTimes[idx] = Date.now();
        timers[idx] = setInterval(() => {}, 500); // dummy interval
        let nyalaBtn = document.getElementById('nyala-' + idx);
        let matiBtn = document.getElementById('mati-' + idx);
        if (nyalaBtn && matiBtn) {
            nyalaBtn.disabled = true;
            matiBtn.disabled = false;
        }
    }

    function matikan(idx) {
        console.log('matikan', idx);
        if (idx < 1 || idx > 8) return;
        if (!timers[idx]) return;
        clearInterval(timers[idx]);
        timers[idx] = null;

        // Hitung durasi
        let durationMs = Date.now() - startTimes[idx];
        let totalSeconds = Math.floor(durationMs / 1000);
        let jam = Math.floor(totalSeconds / 3600);
        let menit = Math.floor((totalSeconds % 3600) / 60);
        let detik = totalSeconds % 60;

        startTimes[idx] = null;
        elapsed[idx] = 0;
        let nyalaBtn = document.getElementById('nyala-' + idx);
        let matiBtn = document.getElementById('mati-' + idx);
        if (nyalaBtn && matiBtn) {
            nyalaBtn.disabled = false;
            matiBtn.disabled = true;
        }
        // Kirim ke backend dengan durasi yang benar
        fetch('save_duration.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `lampu=${idx}&jam=${jam}&menit=${menit}&detik=${detik}`
        }).then(() => window.location.reload());
    }
    </script>
</body>
</html>