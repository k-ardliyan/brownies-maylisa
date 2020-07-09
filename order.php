<?php
    include "connection.php";
    session_start();
    //cek apakah user telah login
    if (isset($_SESSION['username'])) {
        $user = $_SESSION['username'];
        $login = mysqli_query($conn, "SELECT id,tipe,gambar FROM akun WHERE username='$user'");
        $akun = mysqli_fetch_assoc($login);
        $id_user = $akun['id'];
        if ($_SESSION['type']=="admin") {
            $orders = mysqli_query($conn, "SELECT id FROM transaksi WHERE status='menunggu konfirmasi'");
            if (isset($_GET['proses'])) {
                $from = "proses";
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $order = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_user='$id' AND status='proses' ORDER BY id DESC");
                } else
                    $order = mysqli_query($conn, "SELECT * FROM transaksi WHERE status='proses' ORDER BY id DESC");
            }
            elseif (isset($_GET['selesai'])) {
                $from = "selesai";
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $order = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_user='$id' AND status='selesai' ORDER BY id DESC");
                } else
                    $order = mysqli_query($conn, "SELECT * FROM transaksi WHERE status='selesai' ORDER BY id DESC");
            } elseif (isset($_GET['semua'])) {
                $from = "semua";
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $order = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_user='$id' AND (status='proses' OR status='selesai') ORDER BY id DESC");
                } else
                    $order = mysqli_query($conn, "SELECT * FROM transaksi WHERE status='proses' OR status='selesai' ORDER BY id DESC");
            } else {
                $from = "menunggu";
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $order = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_user='$id' AND status='menunggu konfirmasi' ORDER BY id DESC");
                } else
                    $order = mysqli_query($conn, "SELECT * FROM transaksi WHERE status='menunggu konfirmasi' ORDER BY id DESC");
            }
        } elseif ($_SESSION['type']=="user") {
            $orders = mysqli_query($conn, "SELECT id FROM transaksi WHERE id_user='$id_user' AND status='belum'");
            if (isset($_GET['proses'])) {
                $from = "proses";
                $order = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_user='$id_user' AND status='menunggu konfirmasi' OR status='proses' ORDER BY id DESC");
            } elseif (isset($_GET['selesai'])) {
                $from = "selesai";
                $order = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_user='$id_user' AND status='selesai' ORDER BY id DESC");
            } elseif (isset($_GET['semua'])) {
                $from = "semua";
                $order = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_user='$id_user' AND NOT status='belum' ORDER BY id DESC");
            } else {
                $from = "menunggu";
                $order = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_user='$id_user' AND status='belum' ORDER BY id DESC");
            }
        }
        $count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM notif WHERE id_user='$id_user' AND terbaca='0'"));
        $notif = mysqli_query($conn, "SELECT * FROM notif WHERE id_user='$id_user' ORDER BY id DESC");
    } else header("Location: index.php")
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
    <link rel="icon" type="image/png" href="img/icon.png">
    <link rel="stylesheet" href="/bs/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/styles/sticky-footer.css">
    <link rel="stylesheet" href="assets/libraries/fontawesome/css/all.css">
    <link rel="stylesheet" href="assets/styles/main.css">
</head>
<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
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
                        <a class="nav-link border rounded-circle text-center p-1 bg-white d-none d-lg-block" style="height: 36px; width: 36px" href="#">
                            <i class="fa fa-shopping-cart <?php if (mysqli_num_rows($orders)!=0) echo "text-danger" ?>"></i>
                        </a>
                        <a class="nav-link d-inline d-lg-none <?php if (mysqli_num_rows($orders)!=0) echo "text-danger" ?>" href="#">
                            <i class="fa fa-shopping-cart"></i> <span class="d-inline d-lg-none">Keranjang </span>
                        </a>
                    </li>
                <?php } elseif ($_SESSION['type']=="admin") { ?>
                    <li class="nav-item ml-2">
                        <a class="nav-link border bg-white p-2 <?php if (mysqli_num_rows($orders)!=0) echo "text-danger" ?>" href="#">
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
                            <a class="dropdown-item" href="admin.php?produk"><i class="fa fa-store"></i> Atur Toko</a>
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
        <h3 class="text-center mt-5">Daftar Pesanan</h3>
        <hr>
        <nav aria-label="Page Navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item mr-1 <?php if ($from=="menunggu") echo "active" ?>">
                    <a class="page-link" href="order.php<?php if (isset($id)) echo "?id=".$id?>"><?php if ($_SESSION['type']=="user") echo "Keranjang"; elseif ($_SESSION['type']=="admin") echo "Menunggu"?></a>
                </li>
                <li class="page-item ml-1 mr-1 <?php if ($from=="proses") echo "active"?>">
                    <a class="page-link" href="order.php?proses<?php if (isset($id)) echo "&id=".$id?>">Proses</a>
                </li>
                <li class="page-item ml-1 mr-1 <?php if ($from=="selesai") echo "active"?>">
                    <a class="page-link" href="order.php?selesai<?php if (isset($id)) echo "&id=".$id?>">Selesai</a>
                </li>
                <li class="page-item ml-1 <?php if ($from=="semua") echo "active"?>">
                    <a class="page-link" href="order.php?semua<?php if (isset($id)) echo "&id=".$id?>">Semua</a>
                </li>
            </ul>
        </nav>
        <?php if ($_SESSION['type']=="user" && !isset($_GET['proses']) && !isset($_GET['selesai']) && !isset($_GET['semua'])) { ?>
            <form class="text-right" action="action.php" method="POST">
                <button class="btn btn-sm btn-success" type="submit" name="confirm-order">Konfirmasi Pesanan</button>
            </form>
        <?php } ?>
        <?php if ($_SESSION['type']=="admin" && !isset($_GET['id']) && ($from=="selesai" || $from=="semua")) { ?>
            <form class="text-right" action="action.php?from=<?=$from?>" method="POST">
                <button class="btn btn-sm btn-danger" type="submit" name="clear">Bersihkan Pesanan Selesai</button>
            </form>
        <?php } ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover shadow-sm mt-2 text-center">
                <thead class="thead-dark">
                    <tr>
                    <th scope="col" width="5%">id</th>
                    <?php if ($_SESSION['type']=="admin") { ?>
                        <th scope="col" width="20%">Pelanggan</th>
                    <?php } ?>
                    <th scope="col" width="20%">Produk</th>
                    <th scope="col" width="15%">Harga</th>
                    <th scope="col" width="10%">Jumlah</th>
                    <th scope="col" width="15%">Total</th>
                    <?php if ($_SESSION['type']=="admin" && $from=="menunggu") { ?>
                        <th scope="col" width="10%">Terima</th>
                    <?php } elseif ($_SESSION['type']=="admin" && $from=="proses") { ?>
                        <th scope="col" width="10%">Selesai</th>
                    <?php } elseif ($_SESSION['type']=="user" && $from=="proses") { ?>
                        <th scope="col" width="10%">Status</th>
                    <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($order)!=0) { foreach ($order as $orderan) : 
                        $id_user = $orderan['id_user'];
                        $user = mysqli_query($conn, "SELECT nama FROM akun WHERE id='$id_user'");
                        $akun = mysqli_fetch_assoc($user);
                        $id_barang = $orderan['id_barang'];
                        $produk = mysqli_query($conn, "SELECT id,nama,harga FROM barang WHERE id='$id_barang'");
                        $barang = mysqli_fetch_assoc($produk);
                    ?>
                        <tr>
                            <th><?=$orderan['id']?></th>
                            <?php if ($_SESSION['type']=="admin") { ?>
                                <td><a style="text-decoration: none" class="text-success" href="user.php?id=<?=$id_user?>"><div><?=$akun['nama']?></div></a></td>
                            <?php } ?>
                            <td><a style="text-decoration: none" class="text-info" href="product.php?id=<?=$id_barang?>"><div><?=$barang['nama']?></div></a></td>
                            <td>Rp<?=$barang['harga']?></td>
                            <td>
                                <?php if ($_SESSION['type']=="user" && $from=="menunggu") { ?>
                                    <form action="action.php?id=<?=$barang['id']?>" method="POST">
                                        <button type="button" class="btn btn-danger rounded-circle sub" style="width: 32px; height: 32px; padding: 4px">-</button>
                                        <input class="text-center" style="width: 40px" type="number" name="jumlah" value="<?=$orderan['jumlah']?>">
                                        <button type="button" class="btn btn-success rounded-circle add" style="width: 32px; height: 32px; padding: 4px">+</button>
                                        <br>
                                        <button type="submit" class="mt-1" name="order" id="butval">ubah</button>
                                    </form>
                                <?php } else echo $orderan['jumlah']?>
                            </td>
                            <td><?=$orderan['total']?></td>
                            <?php if ($_SESSION['type']=="user" && $from=="proses") { ?>
                                <td><?=$orderan['status']?></td>
                            <?php } ?>
                            <?php if ($_SESSION['type']=="admin" && ($from=="menunggu" || $from=="proses")) { ?>
                                <td>
                                    <form action="action.php?id=<?=$orderan['id']?>" method="POST">
                                        <?php if ($from=="menunggu") { ?>
                                            <button type="submit" class="btn btn-success rounded-circle" style="width: 32px; height: 32px; padding: 4px" name="order-accept">✔</button>
                                            <button type="submit" class="btn btn-danger rounded-circle" style="width: 32px; height: 32px; padding: 4px" name="order-reject">❌</button>
                                        <?php } elseif ($from=="proses") { ?>
                                            <button type="submit" class="btn btn-sm btn-warning border-danger" name="order-done">✔</button>
                                        <?php } ?>
                                    </form>
                                </td>    
                            <?php } ?>
                        </tr>
                    <?php endforeach; } else { ?>
                        <tr><th colspan="7">belum ada pesanan</th></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <!-- end-container -->
    </div>
    <br>
    <footer class="footer bg-dark"><div class="container text-center text-white-50">design by <a style="text-decoration: none" class="text-info" href="#">Irfan</a> & <a style="text-decoration: none" class="text-info" href="#">K.A</a> ~ 2020</div></footer>
<script src="/bs/js/jquery-3.5.1.min.js"></script>
<script src="assets/scripts/notif-read.js"></script>
<script src="assets/scripts/plus-minus-button.js"></script>
<script src="/bs/js/bootstrap.min.js"></script>
<script src="assets/libraries/fontawesome/js/all.js"></script>
</body>
</html>