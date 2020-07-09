<?php
    include "connection.php";
    session_start();
    $query = "SELECT alamat,link_alamat,keterangan FROM info";
    $result = mysqli_query($conn, $query);
    $toko = mysqli_fetch_assoc($result);
    if (isset($_SESSION['username'])) {
        $user = $_SESSION['username'];
        $login = mysqli_query($conn, "SELECT id,tipe,gambar FROM akun WHERE username='$user'");
        $akun = mysqli_fetch_assoc($login);
        $id_user = $akun['id'];
        if ($_SESSION['type']=="user")
            $order = mysqli_query($conn, "SELECT id FROM transaksi WHERE id_user='$id_user' AND status='belum'");
        elseif ($_SESSION['type']=="admin")
            $order = mysqli_query($conn, "SELECT id FROM transaksi WHERE status='menunggu konfirmasi'");
        $count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM notif WHERE id_user='$id_user' AND terbaca='0'"));
        $notif = mysqli_query($conn, "SELECT * FROM notif WHERE id_user='$id_user' ORDER BY id DESC");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang</title>
    <link rel="icon" type="image/png" href="img/icon.png">
    <link rel="stylesheet" href="/bs/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/styles/sticky-footer.css">
    <link rel="stylesheet" href="assets/libraries/fontawesome/css/all.css">
    <link rel="stylesheet" href="assets/styles/main.css">
</head>
<body class="bg-light">
    <div class="container font-weight-bold border-bottom p-0">
        <nav class="navbar navbar-expand-lg navbar-light" style="min-height: 72px">
            <a class="navbar-brand" href="index.php">
                <img src="img/icon.png" width="32" height="32" class="d-inline-block align-top" loading="lazy">
                Rumah Brownies Maylisa
            </a>
            <button class="navbar-toggler ml-auto <?php if (isset($_SESSION['username'])) echo "border-0"?>" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <?php if (!isset($_SESSION['username'])) { ?>
                <span class="navbar-toggler-icon"></span>
                <?php } else {?>
                    <?php if ($akun['gambar']==NULL) { ?>
                        <img src="img/user_placeholder.webp" class="border shadow-sm rounded-circle image-user" style="max-width: 40px"> <span class="dropdown-toggle"></span>
                    <?php } else { ?>
                        <img src="img/akun/<?=$akun['gambar']?>" class="border shadow-sm rounded-circle image-user" style="max-width: 40px"> <span class="dropdown-toggle"></span>
                <?php } } ?>
            </button>
            <div class="collapse navbar-collapse ml-2" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto align-items-lg-center">
                <?php if (isset($_SESSION['username']) && $_SESSION['type']=="user") { ?>
                    <li class="nav-item ml-2">
                        <a class="nav-link border rounded-circle text-center p-1 bg-white d-none d-lg-block" style="height: 36px; width: 36px" type="button" onclick="myFunction()" data-toggle="modal" data-target="#notif" href="#notif">
                            <i class="fa fa-bell"></i><span id="count" class="position-absolute text-danger" style="font-size: 14px; margin-left: -1px; margin-top: 2px"><?php if ($count!=0) echo $count?></span>
                        </a>
                        <a class="nav-link d-inline d-lg-none" type="button" onclick="myFunction()" data-toggle="modal" data-target="#notif" href="#notif">
                            <i class="fa fa-bell"></i><span class="d-inline d-lg-none"> Notifikasi <span class="bagde badge-warning"><?php if ($count!=0) echo $count?></span></span>
                        </a>
                    </li>
                <?php } ?>
                <?php if (isset($_SESSION['username'])) { if ($_SESSION['type']=="user") { ?>
                    <li class="nav-item ml-2">
                        <a class="nav-link border rounded-circle text-center p-1 bg-white d-none d-lg-block" style="height: 36px; width: 36px" href="order.php">
                            <i class="fa fa-shopping-cart <?php if (mysqli_num_rows($order)!=0) echo "text-danger" ?>"></i>
                        </a>
                        <a class="nav-link d-inline d-lg-none <?php if (mysqli_num_rows($order)!=0) echo "text-danger" ?>" href="order.php">
                            <i class="fa fa-shopping-cart"></i> <span class="d-inline d-lg-none">Keranjang </span>
                        </a>
                    </li>
                <?php } elseif ($_SESSION['type']=="admin") { ?>
                    <li class="nav-item ml-2">
                        <a class="nav-link border bg-white p-2 <?php if (mysqli_num_rows($order)!=0) echo "text-danger" ?>" href="order.php">
                            Daftar Pesanan
                        </a>
                    </li>
                <?php }} ?>
                    <?php if (!isset($_SESSION['username'])) { ?>
                    <li class="nav-item ml-2">
                        <a class="btn btn-outline-primary" href="login.php"><i class="fa fa-sign-in-alt"></i> Masuk</a>
                    </li>
                    <?php } else {?>
                    <li class="nav-item ml-2 dropdown">
                        <a class="nav-link text-capitalize text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="img/akun/<?=$akun['gambar']?>" class="d-none d-lg-inline rounded-circle image-user" style="max-width: 40px;"> 
                            <i class="fa fa-user d-inline d-lg-none"></i> Hai, <?=$_SESSION['username']?> <span class="dropdown-toggle"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <?php if (isset($_SESSION['username'])) { if ($_SESSION['type']=="user" || $_SESSION['type']=="guest") { ?>
                            <?php if ($akun['tipe']=="user") { ?>
                            <div class="dropdown-item rounded-0 badge badge-success">Terverifikasi</div>
                            <?php } elseif ($akun['tipe']=="guest") { ?>
                            <div class="dropdown-item rounded-0 badge badge-danger">Belum Terverifikasi</div>
                            <?php } ?>
                            <a class="dropdown-item" href="user.php"><i class="fa fa-edit"></i> Setting Akun</a>
                            <?php } elseif ($_SESSION['type']=="admin") { ?>
                            <a class="dropdown-item" href="admin.php?halaman"><i class="fa fa-store"></i> Atur Toko</a>
                            <?php }} ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="action-logout.php"><i class="fa fa-sign-out-alt"></i> Keluar</a>
                            <?php } ?>
                        </div>
                    </li>
                </ul>
            </div>
        <!-- end-navbar -->
        </nav>
        <div class="modal fade" id="notif" tabindex="-1" role="dialog" aria-labelledby="notifLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="notifLabel">Notifikasi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-2">
                        <?php foreach ($notif as $notif) : ?>
                            <div class="bg-<?php if ($notif['terbaca']==0) echo "warning"; else echo "light" ?> text-muted border rounded-sm m-2 pl-1 pr-1">
                                <?=$notif['pesan']?>
                                <div class="text-black-50 text-right"><?=$notif['tanggal']?></div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        <!-- end-modal -->
        </div>
    </div>
    <div class="container">
        <div class="card shadow-sm py-4 px-5 mt-5 bg-white">
            <div class="card-body pt-0">
                <h4 class="text-center">Alamat Toko</h4>
                <a href="<?=$toko['link_alamat']?>" target="_blank" style="text-decoration: none">
                    <textarea style="cursor: pointer" class="form-control text-center mb-4 mt-2 pt-4 bg-light" rows="4" readonly><?=$toko['alamat']?></textarea>
                </a>
                <h4 class="text-center">Keterangan Toko</h4>
                <textarea class="form-control text-center mt-3 pt-5 bg-light" rows="8" readonly><?=$toko['keterangan']?></textarea>
            </div>
        </div>
    <!-- end-container -->
    </div>
    <br>
    <footer class="footer bg-dark"><div class="container text-center text-white-50">design by <a style="text-decoration: none" class="text-info" href="#">Irfan</a> & <a style="text-decoration: none" class="text-info" href="#">K.A</a> ~ 2020</div></footer>
<script src="/bs/js/jquery-3.5.1.min.js"></script>
<script src="assets/scripts/notif-read.js"></script>
<script src="/bs/js/bootstrap.min.js"></script>
<script src="assets/libraries/fontawesome/js/all.js"></script>
</body>