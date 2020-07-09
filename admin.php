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
            if (isset($_GET['produk'])) {
                $item = mysqli_query($conn, "SELECT * FROM barang");
                $page = 1;
                $title = "Atur Produk";
            } elseif (isset($_GET['pengguna'])) {
                $user = mysqli_query($conn, "SELECT * FROM akun");
                $page = 2;
                $title = "Atur Pelanggan";
            } elseif (isset($_GET['halaman'])) {
                $toko = mysqli_query($conn, "SELECT * FROM info");
                $atur = mysqli_fetch_assoc($toko);
                $page = 3;
                $title = "Atur Halaman";
            } else header("Location: admin.php?produk");
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
                                <a class="dropdown-item" href="#"><i class="fa fa-store"></i> Atur Toko</a>
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
        <h3 class="text-center mt-5">Pengaturan Toko</h3>
        <div class="card shadow-sm mx-auto mt-3"  style="max-width: 70rem">
            <div class="card-header bg-secondary text-center font-weight-bold pb-0">
                <nav aria-label="Page Navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item mr-1 <?php if ($page==1) echo "active"?>">
                            <a class="page-link" href="admin.php?produk">Produk</a>
                        </li>
                        <li class="page-item ml-1 mr-1 <?php if ($page==2) echo "active"?>">
                            <a class="page-link" href="admin.php?pengguna">Pelanggan</a>
                        </li>
                        <li class="page-item ml-1 <?php if ($page==3) echo "active"?>">
                            <a class="page-link" href="admin.php?halaman">Halaman</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="card-body">
                <?php if ($page==1) { ?>
                    <div class="text-right"><a href="item.php" class="btn btn-sm btn-primary">Tambah Barang</a></div>
                    <div class="table-responsive">
                        <table class="table table-hover text-center mt-2">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Stok</th>
                                    <th scope="col">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $angka=1; foreach ($item as $produk) : ?>
                                <tr>
                                    <th><?=$angka?></th>
                                    <td><a style="text-decoration: none" class="text-info" href="product.php?id=<?=$produk['id']?>"><?=$produk['nama']?></a></td>
                                    <td>Rp<?=$produk['harga']?></td>
                                    <td><?=$produk['stok']?></td>
                                    <td>
                                        <a href="item.php?ubah&id=<?=$produk['id']?>" class="badge badge-success">edit</a>
                                    </td>
                                </tr>
                                <?php $angka++; endforeach ?>
                            </tbody>
                        </table>
                    </div>
                <?php } elseif ($page==2) { ?>
                    <div class="table-responsive">
                        <table class="table table-hover text-center mt-2">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Telepon</th>
                                    <th scope="col">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $angka=1; foreach ($user as $akun) : ?>
                                <tr>
                                    <th><?=$angka?></th>
                                    <td><a style="text-decoration: none" class="text-success" href="user.php?id=<?=$akun['id']?>"><?=$akun['nama']?></a></td>
                                    <td><?=$akun['email']?></td>
                                    <td><?=$akun['telepon']?></td>
                                    <td>
                                        <a href="user.php?id=<?=$akun['id']?>" class="badge badge-warning">detail</a>
                                        <a href="order.php?id=<?=$akun['id']?>" class="badge badge-primary" <?php if ($akun['tipe']=="admin") echo "hidden"?>>pesanan</a>
                                    </td>
                                </tr>
                                <?php $angka++; endforeach ?>
                            </tbody>
                        </table>
                    </div>
                <?php } elseif ($page==3) { ?>
                    <div class="pt-4 pl-5 pr-5 pb-1">
                        <form action="action.php" method="POST">
                            <div class="form-group row">
                                <label for="nama" class="col-sm-3 col-form-label">Nama Pemilik</label>
                                <div class="col-sm-9">
                                <input type="text" class="form-control" id="nama" name="nama" maxlength="64" required value="<?=$atur['nama']?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="telepon" class="col-sm-3 col-form-label">Nomor Telepon / Whatsapp</label>
                                <div class="col-sm-9">
                                <input type="number" class="form-control" id="telepon" name="telepon" maxlength="16" required value="<?=$atur['telepon']?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                <input type="email" class="form-control" id="email" name="email" maxlength="64" required value="<?=$atur['email']?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="alamat" class="col-sm-3 col-form-label">Alamat Toko</label>
                                <div class="col-sm-9">
                                <textarea class="form-control" id="alamat" name="alamat" rows="4" required><?=$atur['alamat']?></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="link" class="col-sm-3 col-form-label">URL Google Maps</label>
                                <div class="col-sm-9">
                                <input type="url" class="form-control" id="link" name="link" maxlength="64" required value="<?=$atur['link_alamat']?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="keterangan" class="col-sm-3 col-form-label">Keterangan Toko</label>
                                <div class="col-sm-9">
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="8" required><?=$atur['keterangan']?></textarea>
                                </div>
                            </div>
                            <div class="form-group text-center mt-3">
                                <div class="col-sm-9">
                                <button type="submit" name="edit-page" class="btn btn-primary mb-2">Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>
                        <form action="action.php" enctype="multipart/form-data" method="POST">
                        <hr>
                            <div class="form-group row mt-4">
                                <label for="gambar1" class="col-sm-3 col-form-label">Gambar 1</label>
                                <div class="col-sm-9">
                                <input type="file" accept=".jpg,.jpeg,.png,.webp" id="gambar1" name="gambar1">
                                <img src="img/toko1" width="180px" height="120px">
                                </div>
                            </div>
                            <div class="form-group row mt-4">
                                <label for="gambar2" class="col-sm-3 col-form-label">Gambar 1</label>
                                <div class="col-sm-9">
                                <input type="file" accept=".jpg,.jpeg,.png,.webp" id="gambar2" name="gambar2">
                                <img src="img/toko2" width="180px" height="120px">
                                </div>
                            </div>
                            <div class="form-group row mt-4">
                                <label for="gambar3" class="col-sm-3 col-form-label">Gambar 1</label>
                                <div class="col-sm-9">
                                <input type="file" accept=".jpg,.jpeg,.png,.webp" id="gambar3" name="gambar3">
                                <img src="img/toko3" width="180px" height="120px">
                                </div>
                            </div>
                            <div class="form-group text-center mt-4">
                                <div class="col-sm-9">
                                <button type="submit" name="edit-page-image" class="btn btn-primary">Ubah Gambar Toko</button>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php } ?>
            </div>
            <div class="card-footer bg-secondary">
                <hr class="mt-0 mb-0">
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