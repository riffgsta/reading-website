<?php
// koneksi database
include 'inc/inc_koneksi.php';
$query = "SELECT * FROM halaman"; // Pastikan tabel `halaman` punya: id, judul, kutipan, link
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}


// Ambil halaman saat ini dari URL (default: 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 4; // Jumlah buku per halaman
$offset = ($page - 1) * $limit; // Hitung offset

// Query untuk mendapatkan data buku dengan pagination
$query = "SELECT * FROM halaman LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

// Query untuk menghitung total buku
$total_query = "SELECT COUNT(*) AS total FROM halaman";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_books = $total_row['total'];

// Hitung jumlah halaman
$total_pages = ceil($total_books / $limit);


?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <title>Rifbook - Buku Digital</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <!-- Navbar -->

    <nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary shadow-sm sticky-top">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand" href="#">Rifbook</a>

            <!-- Tombol Toggle untuk Mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu dan Pencarian -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="d-flex ms-auto gap-2">
                    <input class="form-control" type="search" placeholder="Cari buku..." id="searchInput">
                    <button class="btn btn-outline-secondary" id="toggleTheme">🌓</button>
                </div>
            </div>
        </div>
    </nav>
    <!-- Hero Section -->
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-12 col-md-8">
                <h1 class="display-4 mb-4">Halo, bagaimana kabarmu?</h1>
                <p class="lead mb-4">
                    Jangan lupa untuk membaca buku hari ini! 📚 Meditasi itu penting loh, untuk menjaga kesehatan mental
                    dan fisik kita. 😊
                </p>
                <p class="lead mb-4">Membaca buku dapat memperluas wawasan kita, meningkatkan konsentrasi, dan menambah
                    pengetahuan. Di
                    Rifbook, kami menyediakan banyak pilihan buku untuk kamu nikmati kapan saja!</p>
                <button class="btn btn-primary" id="readMoreBtn">Read More</button>
            </div>
        </div>


        <!-- Konten -->
        <div class="container py-4 mt-5">
            <div class="row" id="bookGrid">
                <?php while ($row = mysqli_fetch_array($result)) { ?>
                    <div class="col-6 col-md-3 mb-4 book-item">
                        <div class="card h-100 shadow-sm">
                            <img src="uploads/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['judul']) ?>" class="card-img-top">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($row['judul']) ?></h5>
                                <p class="card-text fst-italic text-muted"><?= htmlspecialchars($row['kutipan']) ?></p>
                                <a href="baca.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-primary mt-auto">Baca</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

        <footer class="bg-light text-dark border-top mt-5">
    <div class="container py-5">
        <div class="row">
            <!-- Kolom 1: Tentang -->
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold">Tentang Rifbook</h5>
                <p class="small">Rifbook adalah platform baca buku digital gratis yang bertujuan meningkatkan
                    budaya literasi di Indonesia. Kami percaya membaca itu keren!</p>
            </div>

            <!-- Kolom 2: Navigasi -->
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold">Navigasi</h5>
                <ul class="list-unstyled small">
                    <li><a href="index.php" class="text-decoration-none text-dark">Beranda</a></li>
                    <li><a href="#bookGrid" class="text-decoration-none text-dark">Daftar Buku</a></li>
                    <li><a href="mailto:rifkismurf92@gmail.com" class="text-decoration-none text-dark">Kontak Kami</a></li>
                </ul>
            </div>

            <!-- Kolom 3: Kontak -->
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold">Hubungi Kami</h5>
                <p class="small mb-1">📧 <a href="mailto:rifkismurf92@gmail.com" class="text-decoration-none text-dark">rifkismurf92@gmail.com</a></p>
                <p class="small mb-1">📷 <a href="https://instagram.com/riffgsta" target="_blank" class="text-decoration-none text-dark">@riffgsta</a></p>
                <p class="small mb-1">💬 <a href="https://wa.me/6281234567890" target="_blank" class="text-decoration-none text-success">Chat Admin</a></p>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <h5 class="fw-bold">Lokasi Kami</h5>
            <div class="ratio ratio-4x3 rounded border">
                <iframe src="https://www.google.com/maps/embed?pb=..."></iframe>
            </div>
            <p class="small mt-2">Kantor pusat Rifbook (Monas, Jakarta)</p>
        </div>
    </div>

    <hr>

    <!-- Baris Bawah -->
    <div class="text-center small mt-3">
        <strong>Rifbook</strong> &copy; <?= date('Y') ?> — Semua hak dilindungi. Dibuat dengan semangat berbagi ilmu ✨
    </div>
</footer>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/script.js"></script>
</body>

</html>