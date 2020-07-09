<?php
    include "connection.php";
    session_start();
    //cek apakah user telah login
    if (isset($_SESSION['username']) && isset($_SESSION['type'])) {
        $user = $_SESSION['username'];
        $login = mysqli_query($conn, "SELECT gambar FROM akun WHERE username='$user'");
        $akun = mysqli_fetch_assoc($login);
        //cek apakah benar admin yang login
        if ($_SESSION['type']=="admin") {
            $page = 0;
            if (isset($_GET['tambah'])) {
                $page = 1;
                $title = "Tambah Produk";
            } elseif (isset($_GET['ubah']) && isset($_GET['id'])) {
                $id = $_GET['id'];
                $item = mysqli_query($conn, "SELECT * FROM barang WHERE id='$id'");
                $produk = mysqli_fetch_assoc($item);
                $page = 2;
                $title = "Ubah Produk";
            } else header("Location: item.php?tambah");
        } else header("Location: index.php");
    } else header("Location: index.php")
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title?></title>
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
                    <?php if (isset($_SESSION['username']) && $_SESSION['type']=="admin") { ?>
                        <li class="nav-item ml-2">
                            <a class="nav-link border bg-white p-2 <?php if (mysqli_num_rows($order)!=0) echo "text-danger" ?>" href="order.php">
                                Daftar Pesanan
                            </a>
                        </li>
                    <?php } ?>
                    <li class="nav-item ml-2 dropdown">
                        <a class="nav-link text-capitalize text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="img/akun/<?=$akun['gambar']?>" class="d-none d-lg-inline rounded-circle image-user" style="max-width: 40px;"> 
                            <i class="fa fa-user d-inline d-lg-none"></i> Hai, <?=$_SESSION['username']?> <span class="dropdown-toggle"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <?php if (isset($_SESSION['username']) && $_SESSION['type']=="admin") { ?>
                                <a class="dropdown-item" href="admin.php?produk"><i class="fa fa-store"></i> Atur Toko</a>
                        <?php } ?>
                        <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="action-logout.php"><i class="fa fa-sign-out-alt"></i> Keluar</a>
                        </div>
                    </li>
                </ul>
            </div>
        <!-- end-navbar -->
        </nav>
    </div>
    <div class="container">
        <div class="card shadow-sm mx-auto mt-5" style="max-width: 50rem;">
            <div class="card-header text-center font-weight-bold">
                <?=$title?>
            </div>
            <div class="card-body">
                <form action="action.php" enctype="multipart/form-data" method="POST">
                    <?php if ($page==2) { ?>
                        <div class="form-group row">
                            <label for="id" class="col-sm-3 col-form-label">ID Produk</label>
                            <div class="col-sm-9">
                            <input type="text" class="form-control" id="id" name="id" readonly value="<?=$produk['id']?>">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group row">
                        <label for="nama" class="col-sm-3 col-form-label">Nama Produk</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="nama" name="nama" maxlength="32" required
                        <?php if ($page==2) { ?>
                            value="<?=$produk['nama']?>"
                        <?php } ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="harga" class="col-sm-3 col-form-label">Harga</label>
                        <div class="col-sm-9">
                        <input type="number" class="form-control" id="harga" name="harga" maxlength="32" required
                        <?php if ($page==2) { ?>
                            value="<?=$produk['harga']?>"
                        <?php } ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="stok" class="col-sm-3 col-form-label">Stok</label>
                        <div class="col-sm-9">
                        <input type="number" class="form-control" id="stok" name="stok" maxlength="12" required
                        <?php if ($page==2) { ?>
                            value="<?=$produk['stok']?>"
                        <?php } ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="deskripsi" class="col-sm-3 col-form-label">Deskripsi</label>
                        <div class="col-sm-9">
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required><?php if ($page==2) {?><?=$produk['deskripsi']?><?php } ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="gambar" class="col-sm-3 col-form-label">Gambar</label>
                        <div class="col-sm-9">
                        <input type="file" accept=".jpg,.jpeg,.png,.webp" id="gambar" name="gambar" <?php if ($page==1) echo "required"?>>
                        <?php if ($page==2) { ?>
                            <img src="img/produk/<?=$produk['gambar']?>" width="128px" height="128px">
                        <?php } ?>
                        </div>
                    </div>
                    <div class="form-group row text-right mt-5">
                        <div class="col-sm-9 text-center ml-3">
                            <?php if ($page==1) { ?>
                                <button type="submit" name="add-item" class="btn btn-primary ml-3">Tambah</button>
                            <?php } elseif ($page==2) { ?>
                                <button type="submit" name="edit-item" class="btn btn-primary ml-5">Ubah</button>
                                <button type="button" class="btn btn-danger ml-2" data-toggle="modal" data-target="#hapusProduk">Hapus Produk</button>
                                <div class="modal fade" id="hapusProduk" tabindex="-1" role="dialog" aria-labelledby="hapusProdukLabel" aria-hidden="true">
                                    <div class="modal-dialog mt-5" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="hapusProdukLabel">Konfirmasi Hapus</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda Yakin Ingin Menghapus Produk Ini?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <a href="action.php?del-item&id=<?=$produk['id']?>" class="btn btn-danger">Ya, Hapus</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer text-right">
                <a href="admin.php?produk" class="btn btn-danger">Kembali</a>
            </div>
        </div>
    <!-- end-container -->
    </div>
    <br>
    <footer class="footer bg-dark"><div class="container text-center text-white-50">design by <a style="text-decoration: none" class="text-info" href="#">Irfan</a> & <a style="text-decoration: none" class="text-info" href="#">K.A</a> ~ 2020</div></footer>
<script src="/bs/js/jquery-3.5.1.slim.min.js"></script>
<script src="/bs/js/bootstrap.min.js"></script>
<script src="assets/libraries/fontawesome/js/all.js"></script>
</body>
</html>