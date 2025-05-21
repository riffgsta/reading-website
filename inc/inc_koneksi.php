<?php
$host = 'sql313.infinityfree.com';
$user = 'if0_38977815';
$pass= 'epbnaHkMz8';
$db ='if0_38977815_rifbook';

$conn = mysqli_connect ($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

?>
