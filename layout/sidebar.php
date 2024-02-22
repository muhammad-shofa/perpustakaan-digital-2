<?php
include "../service/config.php";

$sql_user = "SELECT * FROM users WHERE user_id={$_SESSION['user_id']}";
$result_user = $db->query($sql_user);
$data_user = $result_user->fetch_assoc();
$_SESSION['username'] = $data_user['username'];

?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../pages/dashboard.php" class="brand-link">
        <img src="../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: 0.8" />
        <span class="brand-text font-weight-light">Perpustakaan Digital</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-panel mt-3  mb-3 d-flex">
            <div class="image">
                <img src="data:image/jpeg;base64,<?= base64_encode($data_user['photo_profile']) ?>"
                    class="img-circle elevation-2" width="100px" height="100px" alt="User Image" />
            </div>
            <div class="info">
                <a href="../pages/profile.php" class="d-block">
                    <?= $_SESSION['username'] ?>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="../pages/buku.php" class="nav-link">
                        <i class="fa fa-book" aria-hidden="true"></i>
                        <p>Buku</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../pages/peminjaman.php" class="nav-link">
                        <i class="fa fa-archive" aria-hidden="true"></i>
                        <p>Peminjaman</p>
                    </a>
                </li>
                <!-- admin menu -->
                <div class="admin-menu" id="admin-menu" style="display: none;">
                    <li class="nav-header">ADMIN MENU</li>
                    <li class="nav-item">
                        <a href="../pages/pengguna.php" class="nav-link">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <p>Pengguna</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../pages/report.php" class="nav-link">
                            <i class="fa fa-file" aria-hidden="true"></i>
                            <p>Laporan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../pages/kelola_buku.php" class="nav-link">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            <p>Kelola Buku</p>
                        </a>
                    </li>
                </div>

                <li class="nav-item">
                    <form action="../pages/dashboard.php" method="POST" class="nav-link">
                        <i class="fa fa-power-off" aria-hidden="true"></i>
                        <!-- <p name="logout">Logout</p> -->
                        <input type="submit" name="logout" value="logout" />
                    </form>
                </li>
                <!-- <li class="nav-item">

                    <form action="../pages/dashboard.php" method="POST" class="nav-link">
                        <button type="submit" name="logout" class="btn btn-danger">Logout</button>
                    </form>
                </li> -->
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<?php
include "../service/config.php";

$role = $_SESSION['role'];
if ($role === 'Admin') { ?>
    <script>
        let adminSidebar = document.getElementById("admin-menu");
        adminSidebar.style.display = "block";
    </script>
<?php } ?>