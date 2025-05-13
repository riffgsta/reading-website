<?php
require_once '../admin/inc_header.php';
include '../inc/inc_koneksi.php';

$judul = '';
$isi = '';
$kutipan = '';
$gambar = '';

if (isset($_POST['simpan'])) {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $kutipan = $_POST['kutipan'];

    // Proses upload gambar
    $target_dir = "../uploads/";
    $gambar = basename($_FILES["gambar"]["name"]);
    $target_file = $target_dir . $gambar;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validasi file gambar
    if (isset($_FILES["gambar"]) && $_FILES["gambar"]["error"] == 0) {
        $check = getimagesize($_FILES["gambar"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "<script>alert('File bukan gambar.'); window.location='halaman_input.php';</script>";
            $uploadOk = 0;
        }

        // Validasi ukuran file (maksimal 2MB)
        if ($_FILES["gambar"]["size"] > 2000000) {
            echo "<script>alert('Ukuran file terlalu besar. Maksimal 2MB.'); window.location='halaman_input.php';</script>";
            $uploadOk = 0;
        }

        // Validasi format file
        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            echo "<script>alert('Hanya file JPG, JPEG, PNG, dan GIF yang diperbolehkan.'); window.location='halaman_input.php';</script>";
            $uploadOk = 0;
        }

        // Jika validasi lolos, simpan file
        if ($uploadOk == 1) {
            if (!move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                echo "<script>alert('Gagal mengunggah gambar.'); window.location='halaman_input.php';</script>";
                $uploadOk = 0;
            }
        }
    }

    // Simpan data ke database jika upload berhasil
    if ($uploadOk == 1) {
        $koneksi = mysqli_query($conn, "INSERT INTO halaman (judul, isi, kutipan, gambar) VALUES ('$judul', '$isi', '$kutipan', '$gambar')");
        if (!$koneksi) {
            echo "<div class='alert alert-danger mt-5'>Data gagal ditambahkan!</div>";
        } else {
            echo "<div class='alert alert-success mt-5'>Data berhasil ditambahkan!</div>";
        }
    }
}
?>



<main class="container">
    <h1>Halaman Admin Input Data</h1>
    <span >
        <a href="halaman.php" class="btn btn-secondary mb-3">Kembali</a>
    </span>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3 row">
            <label for="judul" class="col-sm-2 col-form-label">Judul</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="judul" name="judul" value="<?php echo $judul; ?>" required>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="kutipan" class="col-sm-2 col-form-label">Kutipan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="kutipan" name="kutipan" value="<?php echo $kutipan; ?>"
                    required>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="gambar" class="col-sm-2 col-form-label">Sampul Buku</label>
            <div class="col-sm-10">
                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" required>
            </div>
        </div>
        <div class="mb-3 row"></div>
        <label for="isi" class="col-sm-2 col-form-label">isi</label>
        <div class="col-sm-10">
            <textarea name="isi" class="form-control" required><?php echo $isi; ?></textarea>
        </div>
        </div>
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            <input type="submit" name="simpan" class="btn btn-primary" value="Simpan Data">
        </div>
    </form>
</main>
<?php include '../admin/inc_footer.php'; ?>