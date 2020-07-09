<?php
    $ulasan = false;
    $sudah = false;
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        include "connection.php";
        session_start();
        $query = "SELECT * FROM barang WHERE id='$id'";
        $result = mysqli_query($conn, $query);
        $produk = mysqli_fetch_assoc($result);
        //cek apakah user telah login
        if (isset($_SESSION['username'])) {
            $user = $_SESSION['username'];
            $login = mysqli_query($conn, "SELECT id,tipe,gambar FROM akun WHERE username='$user'");
            $akun = mysqli_fetch_assoc($login);
            $id_user = $akun['id'];
            $transaksi = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_user='$id_user' AND id_barang='$id' AND status='selesai'");
            if ($_SESSION['type']=="user")
                $order = mysqli_query($conn, "SELECT id FROM transaksi WHERE id_user='$id_user' AND status='belum'");
            elseif ($_SESSION['type']=="admin")
                $order = mysqli_query($conn, "SELECT id FROM transaksi WHERE status='menunggu konfirmasi'");
            $count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM notif WHERE id_user='$id_user' AND terbaca='0'"));
            $notif = mysqli_query($conn, "SELECT * FROM notif WHERE id_user='$id_user' ORDER BY id DESC");
            if (mysqli_num_rows($transaksi)!=0) {
                $ulasan = true;
                $review = mysqli_query($conn, "SELECT * FROM review WHERE id_user='$id_user' AND id_barang='$id'");
                if (mysqli_num_rows($review)!=0) {
                    $ulasan = true;
                    $sudah = true;
                }
            }
        }
        $review = mysqli_query($conn, "SELECT * FROM review WHERE id_barang='$id' ORDER BY id DESC");
    } else header("Location: index.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$produk['nama']?></title>
    <link rel="icon" type="image/png" href="img/icon.png">
    <link rel="stylesheet" href="/bs/css/bootstrap.min.css">
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
    <main>
        <div class="container">
          <div class="card shadow-sm mt-4">
            <div class="card-header">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 m-0">
                  <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?=$produk['nama']?></li>
                </ol>
              </nav>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-4 mb-3">
                    <a href="img/produk/<?=$produk['gambar']?>" target="_blank" data-toggle="tooltip" title="klik gambar untuk perbesar">
                        <img src="img/produk/<?=$produk['gambar']?>" class="card-img" alt="tidak ada gambar">
                    </a>
                </div>
                <div class="col-lg-8">
                  <h2><?=$produk['nama']?></h2>
                  <p><?=$produk['deskripsi']?></p>
                  <hr/>
                  <div class="d-flex alert alert-secondary">
                    <h4 class="pt-1 flex-grow-1">Rp<?=$produk['harga']?></h4>
                    <?php if ($produk['stok']!="0") { ?>
                        <div class="p-2 text-success"><?=$produk['stok']?> tersedia</div>
                    <?php } else { ?>
                        <div class="p-2 text-danger">Habis</div>
                    <?php } ?>
                  </div>
                  <form action="action.php?id=<?=$produk['id']?>" method="POST">
                    <button style="width: 100px" class="btn btn-success" <?php if ($produk['stok']=="0") echo "disabled" ?> type="submit" name="order"><i class="fa fa-cart-plus"></i> Pesan</button>
                    <div class="btn-group">
                        <button style="width: 100px" class="btn btn-info" type="button" data-toggle="collapse" data-target="#ulasan" aria-expanded="false" aria-controls="ulasan"><i class="fa fa-star"></i> Ulasan</button>
                        <button class="btn btn-outline-info" <?php if (!$ulasan) echo "disabled hidden"; if ($sudah) echo "disabled"?> type="button" data-toggle="collapse" data-target="#tulis-ulasan" aria-expanded="false" aria-controls="tulis-ulasan"><i class="fa fa-pen"></i> Tulis Ulasan</button>
                    </div>
                  </form>
                </div>
                <div class="col mt-2">
                  <hr/>
                  <div id="myGroup">
                    <div class="collapse" id="ulasan" data-parent="#myGroup">
                        <div class="card card-body">
                            <?php foreach ($review as $ulasan) : 
                            $id_user = $ulasan['id_user'];
                            $akun2 = mysqli_query($conn, "SELECT nama,gambar FROM akun WHERE id='$id_user'");
                            $pelanggan = mysqli_fetch_assoc($akun2);
                            ?>
                            <div class="row">
                                <div class="col-3 col-sm-2 col-lg-1">
                                    <img src="img/akun/<?=$pelanggan['gambar']?>" class="border shadow-sm rounded-circle" style="max-width: 70px;">
                                </div>
                                <div class="col align-self-center">
                                    <h5 class="m-0"><?=$pelanggan['nama']?></h5>
                                    <p class="m-0"><?=$ulasan['ulasan']?></p>
                                </div>
                            </div>
                            <hr/>
                            <?php endforeach ?>
                        </div>
                    </div>
                    <div class="collapse" id="tulis-ulasan" data-parent="#myGroup">
                        <div class="card card-body">
                            <form action="action.php?id=<?=$produk['id']?>" method="POST">
                                <div class="form-group row">
                                    <label for="ulas" class="col-sm-3 col-form-label">Ulasan Anda</label>
                                    <div class="col-sm-9">
                                        <textarea type="text" class="form-control" id="ulas" name="ulasan" rows="5" required></textarea>
                                    </div>
                                </div>
                            <div class="form-group text-right mt-3 mb-0">
                                <div class="col-sm-12">
                                    <button type="submit" name="write-review" class="btn btn-primary">Post Ulasan</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </main>
    <br>
    <footer class="bg-dark text-white mt-3">
        <div class="container d-lg-flex text-center pt-5 pb-4">
            <div class="mr-lg-5">
                <img src="img/logo-rumah.png" class="logo img-thumbnail mb-2" alt="Logo">
                <p>All Right Reserved &copy; 2020
                    <br> Design by
                    <a href="#" target="_blank" class="text-light"> Irfan</a> 
                    &
                    <a href="#" target="_blank" class="text-light"> KA</a>
                </p>
            </div>
            <div class="mr-lg-5 text-lg-left">
                <p class="mb-1 font-weight-bold">
                    <a href="contact.php">Informasi</a>
                </p>
                <address>
                    Butuh bantuan? Kontak kami:
                    <br>
                    Senin-Sabtu 08.00 - 17.00 WIB
                    <br>
                    <a href="mailto:asrovitaru@gmail.com" class="text-decoration-none text-light">asrovitaru@gmail.com</a> 
                </address>
            </div>
            <div class="mr-lg-5 text-lg-left">
                <p class="mb-1 font-weight-bold">
                    <a href="about.php">Alamat</a>
                </p>
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d467.2261918353922!2d110.33428170396458!3d-7.000888020164002!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708a9f74cf6665%3A0x98fd64afb838c36e!2sTwin%20Snack%20%26%20Rumah%20Brownies%20Maylisa!5e0!3m2!1sid!2sid!4v1591751947665!5m2!1sid!2sid" width="300" height="180" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
            </div>
            <div class="text-lg-left">
                <p class="mb-1 font-weight-bold">
                    <a href="#">Galeri</a>
                </p>
                <div class="border mx-auto" style="max-width: 280px;">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="img/toko1" class="d-block " style="width: 100%; height: 180px" alt=" ">
                            </div>
                            <div class="carousel-item">
                                <img src="img/toko2" class="d-block " style="width: 100%; height: 180px" alt=" ">
                            </div>
                            <div class="carousel-item">
                                <img src="img/toko3" class="d-block " style="width: 100%; height: 180px" alt=" ">
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
<script src="/bs/js/jquery-3.5.1.min.js"></script>
<script src="assets/scripts/notif-read.js"></script>
<script src="/bs/js/bootstrap.min.js"></script>
<script src="assets/libraries/fontawesome/js/all.js"></script>
</body>
</html>