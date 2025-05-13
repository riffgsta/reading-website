<?php
require_once '../admin/inc_header.php';
include '../inc/inc_koneksi.php';

$judul = $isi = $kutipan = $gambar = '';

// Ambil data berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM halaman WHERE id='$id'";
    $sql = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($sql)) {
        $judul = $row['judul'];
        $isi = $row['isi'];
        $kutipan = $row['kutipan'];
        $gambar = $row['gambar'];
    } else {
        echo "<script>alert('Data tidak ditemukan'); window.location='halaman.php';</script>";
        exit;
    }
}

// Proses update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $kutipan = $_POST['kutipan'];

    // Proses upload gambar baru (jika ada)
    if (!empty($_FILES['gambar']['name'])) {
        $target_dir = "../uploads/";
        $gambar_baru = basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $gambar_baru;
        move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file);
        if (!empty($gambar) && file_exists("../uploads/" . $gambar)) {
            unlink("../uploads/" . $gambar); // Hapus gambar lama
        }
        $gambar = $gambar_baru;
    }

    $query = "UPDATE halaman SET judul='$judul', isi='$isi', kutipan='$kutipan', gambar='$gambar' WHERE id='$id'";
    if (mysqli_query($conn, $query)) {
        echo "<div class='alert alert-success'> Data berhasil diedit </div>";
        echo "<script>window.location='halaman.php';</script>";
    } else {
        echo "<div class='alert alert-danger'> Data gagal diedit </div>";
        echo "<script>window.location='halaman_edit.php?id=$id';</script>";
    }
}
?>
<main class="container mt-5">
    <h1>Edit Data</h1>
    <p><a href="halaman.php" class="btn btn-secondary">Kembali</a></p>

<form action="" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Judul</label>
        <input type="text" class="form-control" name="judul" value="<?= $judul ?>" required>
    </div>
    <div class="mb-3">
        <label>Kutipan</label>
        <input type="text" class="form-control" name="kutipan" value="<?= $kutipan ?>" required>
    </div>
    <div class="mb-3">
        <label>Isi</label>
        <textarea class="form-control" name="isi" required><?= $isi ?></textarea>
    </div>
    <div class="mb-3">
        <label>Gambar</label><br>
        <img src="../uploads/<?= $gambar ?>" alt="Gambar" class="img-thumbnail" width="150">
    </div>
    <div class="mb-3">
        <label>Ganti Gambar</label>
        <input type="file" class="form-control" name="gambar">
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
</main>
<?php include '../admin/inc_footer.php'; ?>