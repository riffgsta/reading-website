<?php
require_once '../admin/inc_header.php';
include '../inc/inc_koneksi.php';

$katakunci = (isset($_GET['katakunci'])) ? $_GET['katakunci'] : '';

// Proses menghapus data
if (isset($_GET['op'])) {
    if ($_GET['op'] == 'delete') {
        $id = $_GET['id'];
        // Ambil nama file gambar berdasarkan ID
        $query_gambar = "SELECT gambar FROM halaman WHERE id='$id'";
        $result_gambar = mysqli_query($conn, $query_gambar);
        if ($result_gambar) {
            $row_gambar = mysqli_fetch_assoc($result_gambar);
            $gambar = $row_gambar['gambar'];

            // Hapus file gambar dari folder uploads jika ada
            if (!empty($gambar) && file_exists("../uploads/" . $gambar)) {
                unlink("../uploads/" . $gambar);
            }
        }

        $sql1 = "DELETE FROM halaman WHERE id='$id'";
        $sql = mysqli_query($conn, $sql1);
        if (!$sql) {
            echo "<script>alert('Data gagal dihapus!')</script>";
        } else {
            echo "<script>alert('Data berhasil dihapus!')</script>";
        }
       
    }
}




// Pencarian
$sqltambahan = "";
if ($katakunci != "") {
    $array_katakunci = explode(" ", $katakunci);
    $sqlcari = [];
    for ($x = 0; $x < count($array_katakunci); $x++) {
        $sqlcari[] = "(judul LIKE '%" . $array_katakunci[$x] . "%' OR kutipan LIKE '%" . $array_katakunci[$x] . "%' OR isi LIKE '%" . $array_katakunci[$x] . "%')";
    }
    $sqltambahan = "WHERE " . implode(" OR ", $sqlcari);
}

// Pagination
$page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;

$jml_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM halaman $sqltambahan");
$jml_data = mysqli_fetch_assoc($jml_query);
$jml = $jml_data['total'];
$batas = 5;
$jmlhalaman = ceil($jml / $batas);

if ($page > $jmlhalaman) {
    $page = $jmlhalaman;
}
if ($page < 1) {
    $page = 1;
}

$halaman = ($page - 1) * $batas;

// Query data
$sql1 = "SELECT * FROM halaman $sqltambahan ORDER BY id DESC LIMIT $halaman, $batas";
$mhs = mysqli_query($conn, $sql1);
if (!$mhs) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<h1>Halaman Admin</h1>
<p>
    <a href="halaman_input.php">
        <input type="button" class="btn btn-primary" value="Buat Halaman Baru">
    </a>
</p>
<form method="get" class="row g-3">
    <div class="col-auto">
        <input type="text" class="form-control" name="katakunci" placeholder="Cari Halaman"
            value="<?php echo $katakunci ?>">
    </div>
    <div class="col-auto">
        <input type="submit" name="cari" class="btn btn-secondary" value="Cari">
    </div>
</form>
<br>
<?php
echo "<p>Total Data: $jml</p>";
?>
<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-1">No</th>
            <th>Judul</th>
            <th>Kutipan</th>
            <th>Gambar</th>
            <th class="col-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = $halaman + 1;
        while ($row = mysqli_fetch_array($mhs)) {
            ?>
            <tr>
                <td><?= $no ?></td>
                <td><?= $row['judul'] ?></td>
                <td><?= $row['kutipan'] ?></td>
                <td>
                    <img src="../uploads/<?= $row['gambar'] ?>" alt="<?= $row['judul'] ?>" class="img-thumbnail"
                        width="100">
                </td>
                <td>
                    <a href="halaman_edit.php?id=<?= $row['id'] ?>">
                        <span class="badge bg-warning text-dark">Edit</span>
                    </a>
                    <a href="halaman.php?op=delete&id=<?= $row['id'] ?>"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                        <span class="badge bg-danger">Delete</span>
                    </a>
                </td>
            </tr>
            <?php $no++;
        } ?>
    </tbody>
</table>

<nav aria-label="Page navigation example">
    <ul class="pagination">
        <?php
        if ($page > 1) {
            $prev = $page - 1;
            echo "<li class='page-item'><a class='page-link' href='halaman.php?page=$prev&katakunci=$katakunci'>Previous</a></li>";
        }
        for ($i = 1; $i <= $jmlhalaman; $i++) {
            if ($i == $page) {
                echo "<li class='page-item active'><a class='page-link' href='#'>$i</a></li>";
            } else {
                echo "<li class='page-item'><a class='page-link' href='halaman.php?page=$i&katakunci=$katakunci'>$i</a></li>";
            }
        }
        if ($page < $jmlhalaman) {
            $next = $page + 1;
            echo "<li class='page-item'><a class='page-link' href='halaman.php?page=$next&katakunci=$katakunci'>Next</a></li>";
        }
        ?>
    </ul>
</nav>

<?php
require_once '../admin/inc_footer.php';
?>