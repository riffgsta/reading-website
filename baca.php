<?php
include 'inc/inc_koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) die("ID tidak ditemukan.");

$query = "SELECT * FROM halaman WHERE id = '$id'";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) === 0) die("Bacaan tidak ditemukan.");

$row = mysqli_fetch_assoc($result);

// Proses komentar jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['komentar'])) {
    $komentar = trim($_POST['komentar']);
    if (!empty($komentar)) {
        $stmt = $conn->prepare("INSERT INTO komentar (halaman_id, isi) VALUES (?, ?)");
        $stmt->bind_param("is", $id, $komentar);
        $stmt->execute();
        $stmt->close();
    }
}

// Ambil komentar dari DB
$komentar_query = mysqli_query($conn, "SELECT * FROM komentar WHERE halaman_id = '$id' ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($row['judul']) ?> - Rifbook</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .content-wrapper {
            display: flex;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 30px;
        }

        .content-wrapper img {
            width: 100%;
            max-width: 400px;
            height: auto;
            object-fit: cover;
            border-radius: 10px;
            flex-shrink: 0;
        }

        .content-wrapper .text-content {
            flex: 1;
            min-width: 250px;
        }

        @media (max-width: 768px) {
            .content-wrapper {
                flex-direction: column;
                align-items: center;
            }

            .content-wrapper img {
                max-width: 100%;
            }

            .text-content {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container py-5">
       <a href="index.php" class="btn btn-outline-primary mb-4 d-inline-flex align-items-center">
    <i class="bi bi-arrow-left me-2"></i> Kembali ke Halaman Utama
</a>

        <div class="content-wrapper">
            <img src="uploads/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['judul']) ?>">
            <div class="text-content">
                <h1><?= htmlspecialchars($row['judul']) ?></h1>
                <p class="text-muted fst-italic"><?= nl2br(htmlspecialchars($row['kutipan'])) ?></p>
                <p><?= nl2br(htmlspecialchars($row['isi'])) ?></p>
            </div>
        </div>

        <!-- Form Komentar -->
        <div class="mt-5">
            <h4>Tinggalkan Komentar</h4>
            <form method="POST">
                <div class="mb-3">
                    <textarea name="komentar" class="form-control" rows="3" required placeholder="Tulis komentar..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Kirim</button>
            </form>
        </div>

        <!-- Daftar Komentar -->
        <div class="mt-4">
            <h5>Komentar</h5>
            <?php if (mysqli_num_rows($komentar_query) > 0): ?>
                <ul class="list-group">
                    <?php while ($komen = mysqli_fetch_assoc($komentar_query)): ?>
                        <li class="list-group-item">
                            <small class="text-muted"><?= $komen['tanggal'] ?></small><br>
                            <?= nl2br(htmlspecialchars($komen['isi'])) ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">Belum ada komentar.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
