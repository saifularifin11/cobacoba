<?php
session_start();

// Proteksi halaman admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_survei";

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname, 3306);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Filter tanggal
$filterStart = isset($_GET['start']) ? $_GET['start'] : '';
$filterEnd = isset($_GET['end']) ? $_GET['end'] : '';

$whereConditions = [];

// Cek apakah rentang tanggal dipilih, jika tidak, ambil semua data
if (!empty($filterStart) && !empty($filterEnd)) {
    $whereConditions[] = "DATE(waktu) BETWEEN '$filterStart' AND '$filterEnd'";
} elseif (!empty($filterStart)) {
    $whereConditions[] = "DATE(waktu) >= '$filterStart'";
} elseif (!empty($filterEnd)) {
    $whereConditions[] = "DATE(waktu) <= '$filterEnd'";
}

$whereClause = !empty($whereConditions) ? "WHERE " . implode(" AND ", $whereConditions) : ""; // Jika tidak ada filter, WHERE dihilangkan

// Query total responden
$queryTotal = "SELECT COUNT(*) AS total FROM tbl_survei $whereClause";
$resultTotal = $conn->query($queryTotal);
$rowTotal = $resultTotal->fetch_assoc();
$totalResponden = max($rowTotal['total'], 1);

// Query data survei
$query = "SELECT nilai, ulasan, foto, waktu FROM tbl_survei $whereClause ORDER BY id DESC";
$result = $conn->query($query);

// Query untuk chart
$queryChart = "SELECT nilai, COUNT(*) as jumlah FROM tbl_survei $whereClause GROUP BY nilai ORDER BY FIELD(nilai, 'SANGAT PUAS', 'PUAS', 'TIDAK PUAS')";
$resultChart = $conn->query($queryChart);

$labels = ['SANGAT PUAS', 'PUAS', 'TIDAK PUAS']; // Pastikan urutan tetap
$dataCounts = ['SANGAT PUAS' => 0, 'PUAS' => 0, 'TIDAK PUAS' => 0]; 

if ($resultChart) {
    while ($row = $resultChart->fetch_assoc()) {
        $dataCounts[$row['nilai']] = $row['jumlah'];
    }
}

// Hitung persentase untuk chart
$data = [];
foreach ($labels as $label) {
    $data[] = round(($dataCounts[$label] / $totalResponden) * 100, 2);
}

$colors = ["#28a745", "#ffc107", "#dc3545"]; // Warna untuk SANGAT PUAS, PUAS, TIDAK PUAS
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Hasil Survei</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="row text-center mb-3">
        <div class="col-md-4 offset-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Total Responden</h5>
                    <h3 class="text-primary"><?= $totalResponden ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-success text-white text-center">
            <h4 class="mb-0">Hasil Survei Kepuasan Pelanggan</h4>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label>Pilih Tipe Chart:</label>
                <select id="chartType" class="form-select" onchange="updateChart()">
                    <option value="bar">Bar Chart</option>
                    <option value="pie">Pie Chart</option>
                </select>
            </div>
            <canvas id="surveyChart"></canvas>
        </div>
        <div class="d-flex justify-content-end mb-3">
            <a href="export_excel.php" class="btn btn-success me-2">📊 Ekspor Excel</a>
            <button class="btn btn-primary" onclick="downloadChart()">🖼 Simpan Chart</button>
        </div>
    </div>

    <div class="card mt-4 shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Daftar Ulasan Responden</h5>
    </div>
    <div class="card-body">
        <!-- Form Filter Rentang Tanggal (Auto-Submit) -->
        <form id="filterForm" method="GET" action="">
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label for="start" class="form-label">Dari Tanggal:</label>
                    <input type="date" id="start" name="start" class="form-control date-filter" 
                           value="<?= isset($_GET['start']) ? $_GET['start'] : '' ?>">
                </div>
                <div class="col-md-6">
                    <label for="end" class="form-label">Sampai Tanggal:</label>
                    <input type="date" id="end" name="end" class="form-control date-filter" 
                           value="<?= isset($_GET['end']) ? $_GET['end'] : '' ?>">
                </div>
            </div>
        </form>

        <!-- Tabel Data Survei -->
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nilai</th>
                    <th>Ulasan</th>
                    <th>Gambar</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($result->num_rows > 0) {
                    $no = 1;
                    while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <?php
                                $badgeClass = "bg-secondary";
                                if ($row['nilai'] === "TIDAK PUAS") $badgeClass = "bg-danger";
                                if ($row['nilai'] === "PUAS") $badgeClass = "bg-warning text-dark";
                                if ($row['nilai'] === "SANGAT PUAS") $badgeClass = "bg-success";
                                ?>
                                <span class='badge <?= $badgeClass ?>'><?= htmlspecialchars($row['nilai']) ?></span>
                            </td>
                            <td class='text-wrap' style='max-width: 300px;'><?= !empty($row['ulasan']) ? htmlspecialchars($row['ulasan']) : "-" ?></td>
                            <td>
                                <?php if (!empty($row['foto'])) { ?>
                                    <img src='<?= htmlspecialchars($row['foto']) ?>' class='img-thumbnail' width='100' onclick='previewImage(this)'>
                                <?php } else { echo "-"; } ?>
                            </td>
                            <td><?= htmlspecialchars($row['waktu']) ?></td>
                        </tr>
                    <?php } 
                } else { ?>
                    <tr><td colspan='5' class='text-center text-muted'>Belum ada ulasan.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Auto-submit form when date inputs change
    document.querySelectorAll('.date-filter').forEach(input => {
        input.addEventListener('change', () => {
            // Check if both dates are filled
            const startDate = document.getElementById('start').value;
            const endDate = document.getElementById('end').value;
            
            // Auto-submit when both dates are filled or if we're clearing a filter
            if ((startDate && endDate) || (!startDate && !endDate) || 
                (startDate && input.id === 'start') || 
                (endDate && input.id === 'end')) {
                document.getElementById('filterForm').submit();
            }
        });
    });

    Chart.register(ChartDataLabels);
    var ctx = document.getElementById('surveyChart').getContext('2d');
    var surveyChart = new Chart(ctx, {
        type: "bar",
        data: {
            labels: <?= json_encode($labels); ?>,
            datasets: [{
                label: 'Persentase Responden',
                data: <?= json_encode($data); ?>,
                backgroundColor: <?= json_encode($colors); ?>
            }]
        },
        options: {
            plugins: {
                datalabels: {
                    color: '#fff',
                    anchor: 'center',
                    align: 'center',
                    formatter: (value) => value + '%',
                    font: {
                        weight: 'bold',
                        size: 30 // Ukuran angka persentase diperbesar
                    }
                }
            },
            layout: {
                padding: 10
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });

    function updateChart() {
        var selectedType = document.getElementById("chartType").value;
        surveyChart.destroy();
        surveyChart = new Chart(ctx, {
            type: selectedType,
            data: {
                labels: <?= json_encode($labels); ?>,
                datasets: [{
                    label: 'Persentase Responden',
                    data: <?= json_encode($data); ?>,
                    backgroundColor: <?= json_encode($colors); ?>
                }]
            },
            options: {
                plugins: {
                    datalabels: {
                        color: '#fff',
                        anchor: 'center',
                        align: 'center',
                        formatter: (value) => value + '%',
                        font: {
                            weight: 'bold',
                            size: 30 // Ukuran angka persentase diperbesar
                        }
                    }
                },
                layout: {
                    padding: 10
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    }

    function previewImage(img) {
        var overlay = document.createElement("div");
        overlay.innerHTML = `<div style="display:flex;justify-content:center;align-items:center;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);">
                                <img src="${img.src}" style="max-width:90%;max-height:90%;border-radius:10px;">
                            </div>`;
        overlay.onclick = function() { document.body.removeChild(overlay); };
        document.body.appendChild(overlay);
    }

    function downloadChart() {
        var canvas = document.getElementById('surveyChart');
        var link = document.createElement('a');
        link.href = canvas.toDataURL('image/jpeg', 1.0);
        link.download = 'chart_survei.jpg';
        link.click();
    }
</script>

</body>
</html>
<?php $conn->close();