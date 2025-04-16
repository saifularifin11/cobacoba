<?php
session_start();

// Kalau belum login, kembalikan ke halaman login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: indexlogin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Aplikasi Survei Kepuasan Pelayanan Pelanggan">
    <title>Aplikasi Survei Kepuasan Pelayanan</title>
    <link rel="shortcut icon" href="assets/img/review.png" type="image/png">
    
    <!-- Load jQuery lebih awal untuk menghindari error -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Signika+Negative:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="d-flex flex-column h-100">
    <main class="flex-shrink-0">
        <nav class="navbar cust-nav shadow-sm">
            <div class="container">
                <div class="d-flex align-items-center" style="cursor: pointer;" onclick="document.location='admin.php'">
                    <img src="assets/img/BPS.png" alt="Logo BPS Kota Surabaya" height="50">
                    <h1 class="h5 mb-0 ms-3">BPS Kota Surabaya</h1>
                </div>
            </div>
        </nav>
        <div class="container pt-4 text-center">
            <h2 class="fw-bold text-title mb-4">Survei Kepuasan Pelayanan</h2>
            <div class="container vh-75 d-flex align-items-center justify-content-center">
                <div class="row w-100">
                <div class="col-12 col-md-4 mb-3">
                        <button id="btn-tidak-puas" type="button" class="btn-emoji w-100 h-100" onclick="showPopup('TIDAK PUAS')">
                            <img src="/assets/img/tidak-puas.png" alt="TIDAK PUAS"><br>
                            <p class="fw-bold text-black mt-3">TIDAK PUAS</p>
                        </button>
                    </div>
                    <div class="col-12 col-md-4 mb-3">
                        <button id="btn-puas" type="button" class="btn-emoji w-100 h-100" onclick="showPopup('PUAS')">
                            <img src="/assets/img/puas.png" alt="PUAS"><br>
                            <p class="fw-bold text-black mt-3">PUAS</p>
                        </button>
                    </div>
                    <div class="col-12 col-md-4 mb-3">
                        <button id="btn-sangat-puas" type="button" class="btn-emoji w-100 h-100" onclick="showPopup('SANGAT PUAS')">
                            <img src="/assets/img/sangat-puas.png" alt="SANGAT PUAS"><br>
                            <p class="fw-bold text-black mt-3">SANGAT PUAS</p>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

<footer class="footer mt-auto py-4">
    <div class="container">
        <div class="copyright text-center mb-2 mb-md-0">
            <a href="logout.php" style="color: inherit; text-decoration: none; cursor: pointer;">
                &copy; 2025 - MAGANG B3DU2K. - All rights reserved.
            </a>
        </div>
    </div>
</footer>


    <video id="video" autoplay style="display: none;"></video>
    <canvas id="canvas" style="display: none;"></canvas>

    <script>
    let videoStream = null;

    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                videoStream = stream;
                document.getElementById("video").srcObject = stream;
            })
            .catch(err => {
                console.error("Gagal mengakses kamera:", err);
            });
    }

    function stopCamera() {
        if (videoStream) {
            videoStream.getTracks().forEach(track => track.stop());
            videoStream = null;
        }
    }

    async function ambilFoto() {
        const video = document.getElementById("video");
        const canvas = document.getElementById("canvas");
        const context = canvas.getContext("2d");

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        return canvas.toDataURL("image/png");
    }

    function showPopup(kategori) {
    startCamera();

    const rekomendasiUlasan = {
        "SANGAT PUAS": ["Pelayanan sangat baik.", "Datanya lengkap."],
        "PUAS": ["Fasilitas cukup nyaman.", "Pelayanan baik, namun masih bisa lebih cepat.", "Cukup puas, tapi ada beberapa hal yang perlu diperbaiki."],
        "TIDAK PUAS": ["Pelayanan lambat, mohon perbaikan ke depannya.", "Kurangnya informasi yang jelas.", "Staf kurang ramah dan tidak responsif."]
    };

    let options = rekomendasiUlasan[kategori].map(ulasan => `
        <button type="button" class="ulasan-btn" onclick="document.getElementById('kritikSaran').value = '${ulasan}'">
            ${ulasan}
        </button>
    `).join('');

    Swal.fire({
        title: "Berikan Ulasan",
        html: `
            <p>Anda memilih kategori: <strong>${kategori}</strong></p>
            <div class="ulasan-container">${options}</div>
            <textarea id="kritikSaran" class="swal2-textarea large-textarea" placeholder="Atau tulis ulasan Anda..."></textarea>
            <p id="errorText" style="color: red; display: none;">Ulasan tidak boleh kosong!</p>
        `,
        showCancelButton: true,
        confirmButtonText: "✔ Kirim Ulasan",
        cancelButtonText: "✖ Batal",
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#dc3545",
        width: "50vw",
        customClass:{
            popup: 'large-popup'
        },
        willClose: () => {
            stopCamera();
        },
        preConfirm: async () => {
            const ulasan = document.getElementById("kritikSaran").value.trim();
            if (!ulasan) {
                document.getElementById("errorText").style.display = "block";
                return false;
            }
            const foto = await ambilFoto();
            kirimData(kategori, ulasan, foto);
        }
    });
}
    function kirimData(kategori, ulasan, foto) {
        if (typeof $ !== "function") {
            Swal.fire("Error!", "jQuery tidak terdeteksi.", "error");
            return;
        }

        $.post("submit_survei.php", { kategori, ulasan, foto }, function (response) {
            Swal.fire({
            icon: "success",
            title: "Terkirim!",
            text: "Terima kasih atas ulasan Anda!",
            showConfirmButton: false, // Tidak perlu tombol OK
            timer: 5000 // Pop-up otomatis hilang setelah 5 detik
            });
        }).fail(() => {
            Swal.fire("Gagal!", "Terjadi kesalahan saat mengirim ulasan.", "error");
        });

        setTimeout(() => {
        let audio;
        if (kategori === "SANGAT PUAS" || kategori === "PUAS") {
            audio = new Audio("assets/Suara/Terima kasih banyak atas kunjungannya.mp3");
        } else if (kategori === "TIDAK PUAS") {
            audio = new Audio("assets/Suara/mohon maaf atas kekurangan kami.mp3");
        }
        audio.play();
    }, 1000);
}
</script>

</body>
</html>
