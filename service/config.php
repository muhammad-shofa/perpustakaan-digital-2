<?php
$hostname = "localhost";
$username = "root";
$password = "";
$db_name = "perpustakaan_digital";

$db = mysqli_connect($hostname, $username, $password, $db_name);
if ($db->connect_error) {
    echo "koneksi database gagal";
}

?>