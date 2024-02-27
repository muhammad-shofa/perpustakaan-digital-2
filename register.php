<?php
include "service/config.php";
session_start();

$message_register = "";

if (isset($_SESSION['is_login'])) {
    header('location: pages/dashboard.php');
}

if (isset($_POST['daftar'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $alamat = $_POST['alamat'];
    $role = $_POST['role'];

    $hash_password = hash("sha256", $password);

    try {
        $sql = "INSERT INTO users (username, password, email, nama_lengkap, alamat, role) VALUES ('$username', '$hash_password', '$email', '$nama_lengkap', '$alamat', '$role')";

        if ($db->query($sql)) {
            $message_register = "Selamat pendaftaran kamu berhasil, <a href='login.php'>login sekarang</a>";
        } else {
            $message_register = "Pendaftaran kamu gagal";
        }
    } catch (mysqli_sql_exception) {
        $message_register = "Username sudah digunakan, silahkan gunakan username lain!";
    }

    $db->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | Perpustakaan Digital</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>


<body class="hold-transition login-page">
    <div class="login-box pt-10">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="index.php" class="h1"><b>Perpustakaan</b> Digital</a>
            </div>
            <div class="card-body ">
                <p class="login-box-msg">Daftar untuk mulai meminjam buku</p>
                <i>
                    <?= $message_register ?>
                </i>

                <form action="register.php" method="POST">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Username" name="username" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fa fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Nama Lengkap" name="nama_lengkap" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fa fa-address-card"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Alamat" name="alamat" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fa fa-map-marker"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <select name="role" class="form-control select2" style="width: 100%;">
                                <option selected="selected">User</option>
                                <option>Petugas</option>
                                <option>Admin</option>
                            </select>
                            <div class="input-group-text">
                                <span class="fa fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label>Role</label>
                     
                    </div> -->
                    <div class="row">

                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block" name="daftar">Daftar</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <br>
                <p class="mb-0 text-center">
                    Sudah mempunyai akun?
                </p>
                <div class="social-auth-links text-center mt-2 mb-3">
                    <a href="login.php" class="btn btn-block btn-primary">
                        Login
                    </a>

                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
</body>

</html>