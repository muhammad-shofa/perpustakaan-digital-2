<?php
include "../service/config.php";
session_start();

if (isset($_SESSION['is_login']) == false) {
    header('location: index.php');
}

$message_kembalikan = "";
$message_hapus_riwayat = "";


// get data buku yang dipinjam user
$user_id = $_SESSION['user_id'];
$sql_peminjaman = "SELECT peminjaman.*, buku.judul, buku.penulis, buku.penerbit, buku.tahun_terbit FROM peminjaman INNER JOIN buku ON peminjaman.buku_id = buku.buku_id WHERE peminjaman.user_id = $user_id";
$result_peminjaman = $db->query($sql_peminjaman);

// kembalikan buku
if (isset($_POST['kembalikan_buku'])) {
    $buku_id = $_POST['kembalikan_buku_id'];
    $sql_kembalikan = "UPDATE peminjaman SET status_peminjaman='Dikembalikan' WHERE buku_id = $buku_id";
    $result_kembalikan = $db->query($sql_kembalikan);
    if ($result_kembalikan) {
        $message_kembalikan = "Buku berhasil di kembalikan";
    }
}

// get data riwayat buku
$sql_riwayat_peminjaman = "SELECT peminjaman.*, buku.judul, buku.penulis, buku.penerbit, buku.tahun_terbit FROM peminjaman INNER JOIN buku ON peminjaman.buku_id = buku.buku_id WHERE peminjaman.user_id = $user_id AND peminjaman.status_peminjaman = 'Dikembalikan'";
$result_riwayat_peminjaman = $db->query($sql_riwayat_peminjaman);

// hapus riwayat buku
if (isset($_POST['hapus_riwayat_buku'])) {
    $hapus_buku_id = $_POST['hapus_riwayat_buku_id'];
    $sql_hapus_riwayat = "DELETE FROM peminjaman WHERE buku_id = $hapus_buku_id AND user_id = $user_id AND status_peminjaman = 'Dikembalikan'";
    $result_hapus_riwayat = $db->query($sql_hapus_riwayat);
    if ($result_hapus_riwayat = $db->query($sql_hapus_riwayat)) {
    }
}

$db->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Peminjaman | Perpustakaan Digital</title>

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
                            <h1 class="m-0">Peminjaman</h1>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Peminjaman</li>
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
                <!-- card daftar buku -->
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title">Daftar Buku Yang Anda Pinjam</h3>
                        <?= $message_kembalikan ?>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID Buku</th>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Tanggal Pengembalian</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result_peminjaman->num_rows > 0) { ?>
                                    <?php while ($data_peminjaman = $result_peminjaman->fetch_assoc()) { ?>
                                        <?php if ($data_peminjaman['status_peminjaman'] == "Dipinjam") { ?>
                                            <tr>
                                                <td>
                                                    <?= $data_peminjaman['buku_id'] ?>
                                                </td>
                                                <td>
                                                    <?= $data_peminjaman['judul'] ?>
                                                </td>
                                                <td>
                                                    <?= $data_peminjaman['penulis'] ?>
                                                </td>
                                                <td>
                                                    <?= $data_peminjaman['tanggal_pengembalian'] ?>
                                                </td>
                                                <td>
                                                    <?= $data_peminjaman['status_peminjaman'] ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary mt-2 btn-block" data-toggle="modal"
                                                        data-target="#modal-kembalikan-<?= $data_peminjaman['buku_id'] ?>">
                                                        Kembalikan
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <!-- modal kembalikan buku -->
                                        <div class="modal fade" id="modal-kembalikan-<?= $data_peminjaman['buku_id'] ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="card card-primary">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Kembalikan Buku</h3>
                                                            <button type="button" class="close" data_buku-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="peminjaman.php" method="POST">
                                                            <p class="text-center p-3">Anda yakin ingin mengembalikan buku
                                                                <?= $data_peminjaman['buku_id'] ?>
                                                            </p>
                                                            <input type="text" name="kembalikan_buku_id"
                                                                value="<?= $data_peminjaman['buku_id'] ?>">
                                                            <div class="card-footer">
                                                                <button type="submit" class="btn btn-primary"
                                                                    name="kembalikan_buku">Kembalikan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            Kamu belum pernah meminjam buku
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- end card daftar buku -->

                <!-- riwayat peminjaman -->
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title">Riwayat Peminjaman Buku</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID Buku</th>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Tanggal Pengembalian</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($data_riwayat_peminjaman = $result_riwayat_peminjaman->fetch_assoc()) { ?>
                                    <?php if ($data_riwayat_peminjaman['status_peminjaman'] == "Dikembalikan") { ?>
                                        <tr>
                                            <td>
                                                <?= $data_riwayat_peminjaman['buku_id'] ?>
                                            </td>
                                            <td>
                                                <?= $data_riwayat_peminjaman['judul'] ?>
                                            </td>
                                            <td>
                                                <?= $data_riwayat_peminjaman['penulis'] ?>
                                            </td>
                                            <td>
                                                <?= $data_riwayat_peminjaman['tanggal_pengembalian'] ?>
                                            </td>
                                            <td>
                                                <?= $data_riwayat_peminjaman['status_peminjaman'] ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger mt-2 btn-block" data-toggle="modal"
                                                    data-target="#modal-hapus-<?= $data_riwayat_peminjaman['buku_id'] ?>">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- modal hapus riwayat buku -->
                                        <div class="modal fade" id="modal-hapus-<?= $data_riwayat_peminjaman['buku_id'] ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="card card-primary">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Hapus Riwayat Buku</h3>
                                                            <button type="button" class="close" data_buku-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="peminjaman.php" method="POST">
                                                            <p class="text-center p-3">Anda yakin ingin menghapus buku
                                                                <?= $data_riwayat_peminjaman['buku_id'] ?>
                                                            </p>
                                                            <input type="hidden" name="hapus_riwayat_buku_id"
                                                                value="<?= $data_riwayat_peminjaman['buku_id'] ?>">
                                                            <div class="card-footer">
                                                                <button type="submit" class="btn btn-danger"
                                                                    name="hapus_riwayat_buku">Hapus</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>

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
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="../dist/js/pages/dashboard.js"></script>

</body>

</html>