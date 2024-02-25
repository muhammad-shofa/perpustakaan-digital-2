<?php
include "../service/config.php";
session_start();

if (isset($_SESSION['is_login']) == false) {
    header('location: ../index.php');
}

$message_peminjaman = "";

// buku
$sql_buku = "SELECT * FROM buku";
$result_buku = $db->query($sql_buku);

// pinjam buku
if (isset($_POST["pinjam_buku"])) {
    $user_id = $_SESSION['user_id'];
    $buku_id = $_POST['buku_id'];
    $tanggal_peminjaman = date('Y-m-d');
    $tanggal_pengembalian = $_POST['tanggal_pengembalian'];
    $status_peminjaman = "Dipinjam";

    $tanggal_pengembalian_formatted = date('Y-m-d', strtotime($tanggal_pengembalian));

    $sql_pinjam = "INSERT INTO peminjaman (user_id, buku_id, tanggal_peminjaman, tanggal_pengembalian, status_peminjaman) VALUES ('$user_id', '$buku_id', '$tanggal_peminjaman', '$tanggal_pengembalian_formatted', '$status_peminjaman')";
    if ($db->query($sql_pinjam)) {
        header('location: peminjaman.php');
    } else {
        header('location: buku.php');
    }
    $db->close();
}

// tambah buku
if (isset($_POST['tambah_buku'])) {
    $judul_buku = $_POST['judul-buku'];
    $penulis_buku = $_POST['penulis-buku'];
    $penerbit_buku = $_POST['penerbit-buku'];
    $tahun_terbit_buku = $_POST['tahun-terbit-buku'];
    $deskripsi = $_POST['deskripsi-buku'];

    $sql_tambah_buku = "INSERT INTO buku (judul, penulis, penerbit, tahun_terbit, deskripsi) VALUES ('$judul_buku', '$penulis_buku', '$penerbit_buku', '$tahun_terbit_buku', '$deskripsi')";
    if ($result = $db->query($sql_tambah_buku)) {
        header("location: kelola_buku.php");
    }
}

// hapus buku 
if (isset($_POST['hapus_buku'])) {
    $data_buku_id = $_POST['data-buku-id'];
    $sql_delete_buku_in_peminjaman = "DELETE FROM peminjaman WHERE buku_id = $data_buku_id";
    $sql_delete_buku_in_daftar = "DELETE FROM buku WHERE buku_id = $data_buku_id";

    $result_hapus_buku_peminjaman = $db->query($sql_delete_buku_in_peminjaman);
    $result_hapus_buku = $db->query($sql_delete_buku_in_daftar);
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('location: ../index.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Buku | Perpustakaan Digital</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css" />
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css" />
    <!-- iCheck -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css" />
    <!-- JQVMap -->
    <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css" />
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css" />
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css" />
    <!-- summernote -->
    <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css" />
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60"
                width="60" />
        </div>

        <!-- Navbar -->
        <?php include "../layout/navbar.php" ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include "../layout/sidebar.php" ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Kelola Buku</h1>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Kelola Buku</li>
                            </ol>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <!-- daftar buku -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Semua Buku</h3>
                    </div>
                    <!-- btn modal tambah buku -->
                    <button type="button" class="btn btn-primary col-2 mt-2 ml-4" data-toggle="modal"
                        data-target="#modal-tambah-buku">
                        Tambah Buku
                    </button>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Penerbit</th>
                                    <th>Tahun Terbit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($data_buku = $result_buku->fetch_assoc()) { ?>
                                    <tr>
                                        <td>
                                            <?= $data_buku['buku_id'] ?>
                                        </td>
                                        <td>
                                            <?= $data_buku['judul'] ?>
                                        </td>
                                        <td>
                                            <?= $data_buku['penulis'] ?>
                                        </td>
                                        <td>
                                            <?= $data_buku['penerbit'] ?>
                                        </td>
                                        <td>
                                            <?= $data_buku['tahun_terbit'] ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#modal-<?= $data_buku['buku_id'] ?>">
                                                Lihat
                                            </button>
                                            <button type="button" class="btn btn-warning" data-toggle="modal"
                                                data-target="#modal-edit-<?= $data_buku['buku_id'] ?>">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                                data-target="#modal-delete-<?= $data_buku['buku_id'] ?>">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- modal lihat buku -->
                                    <div class="modal fade" id="modal-<?= $data_buku['buku_id'] ?>">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Lihat Buku</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="container-flex-buku d-flex">
                                                        <div class="container-img-buku m-2 d-b">
                                                            <img class="text-center rounded" width="300px"
                                                                src="../dist/img/gambar_buku/buku<?= $data_buku['buku_id'] ?>.jpg">
                                                        </div>
                                                        <div class="data_buku-buku m-2 d-b">
                                                            <h5>
                                                                <u>
                                                                    <?= $data_buku['judul'] ?>
                                                                </u>
                                                            </h5>
                                                            <p><b class="text-blue">Penulis</b><br>
                                                                <?= $data_buku['penulis'] ?>
                                                            </p>
                                                            <p><b class="text-blue">Penerbit</b><br>
                                                                <?= $data_buku['penerbit'] ?>
                                                            </p>
                                                            <p><b class="text-blue">Tahun Terbit</b> <br>
                                                                <?= $data_buku['tahun_terbit'] ?>
                                                            </p>
                                                            <p>
                                                                <b class="text-blue">Deskripsi</b><br>
                                                                <?= $data_buku['deskripsi'] ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- modal lihat buku end -->

                                    <!-- modal delete buku -->
                                    <div class="modal fade" id="modal-delete-<?= $data_buku['buku_id'] ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Hapus Buku</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="container-img-buku m-2 d-b">
                                                        <img class="text-center rounded" width="300px"
                                                            src="../dist/img/gambar_buku/buku<?= $data_buku['buku_id'] ?>.jpg">
                                                    </div>
                                                    <form action="kelola_buku.php" method="POST">
                                                        <p class="text-center p-3">Anda yakin ingin menghapus buku ini?
                                                        </p>
                                                        <input type="hidden" name="data-buku-id"
                                                            value="<?= $data_buku['buku_id'] ?>">
                                                        <div class="card-footer">
                                                            <button type="submit" class="btn btn-danger"
                                                                name="hapus_buku">Hapus</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- modal delete buku end -->
                                <?php } ?>
                            </tbody>
                        </table>
                        <!-- modal tambah buku -->
                        <div class="modal fade" id="modal-tambah-buku">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Tambah Buku</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- tambah buku -->
                                        <div class="card card-primary">
                                            <div class="card-header">
                                                <h3 class="card-title">Tambah Buku Baru</h3>
                                            </div>
                                            <!-- form start -->
                                            <form action="kelola_buku.php" method="POST">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="inputId">ID Buku</label>
                                                        <input type="text" class="form-control" id="inputId"
                                                            value="Auto Generate" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputJudul">Judul</label>
                                                        <input type="text" name="judul-buku" class="form-control"
                                                            id="inputJudul" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputFile">Cover</label>
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input type="file" name="gambar-buku"
                                                                    class="custom-file-input" id="exampleInputFile">
                                                                <label class="custom-file-label"
                                                                    for="exampleInputFile">Choose
                                                                    file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputPenulis">Penulis</label>
                                                        <input type="text" name="penulis-buku" class="form-control"
                                                            id="inputPenulis" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputPenerbit">Penerbit</label>
                                                        <input type="text" name="penerbit-buku" class="form-control"
                                                            id="inputJudul" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputTahunTerbit">Tahun Terbit</label>
                                                        <input type="date" name="tahun-terbit-buku" class="form-control"
                                                            id="inputPenulis" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="inputTahunTerbit">Deskripsi</label>
                                                        <textarea class="form-control" name="deskripsi-buku"
                                                            style="min-height: 200px; max-height: 200px; resize: none;"
                                                            rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <!-- /.card-body -->

                                                <div class="card-footer">
                                                    <button type="submit" name="tambah_buku"
                                                        class="btn btn-primary">Tambahkan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- modal tambah buku end -->
                    </div>
                    <!-- /.card-body -->
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include "../layout/footer.php"; ?>


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- js -->
    <!-- Load flatpickr.js library -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Inisialisasi flatpickr pada input tanggal_pengembalian
        flatpickr(".datepicker", {
            dateFormat: "d/m/Y", // Format tanggal yang diinginkan
            altInput: true,
            altFormat: "d/m/Y", // Format alternatif untuk tampilan input
        });
    </script>

    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge("uibutton", $.ui.button);
    </script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="../plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="../plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="../plugins/moment/moment.min.js"></script>
    <script src="../plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="../plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="../dist/js/pages/dashboard.js"></script>
</body>

</html>