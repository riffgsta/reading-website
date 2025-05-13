</main>

<footer class="bg-dark text-white mt-4">
    <div class="container-fluid text-center p-3">
        <p class="mb-0">Copyright &copy; <?php echo date('Y'); ?> | Admin Panel</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
<script>

    $(document).ready(function () {
        // Menutup navbar saat klik di luar
        $(document).click(function (event) {
            var clickover = $(event.target);
            var _opened = $(".navbar-collapse").hasClass("show");
            if (_opened && !clickover.hasClass("navbar-toggler")) {
                $(".navbar-toggler").click(); // Menutup navbar
            }
        });

        // Inisialisasi Summernote (jika digunakan)
        $('#summernote').summernote();
    });
</script>
</body>

</html>