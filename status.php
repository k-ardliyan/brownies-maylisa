<?php
    session_start();
    //cek apakah user telah login
    if (isset($_SESSION['username'])) {
        //cek apakah benar guest yang login
        if ($_SESSION['type']=="guest") {
            $message = "Silahkan Verifikasikan Akun Anda di Cabang Rumah Brownies Terdekat";
        } elseif ($_SESSION['type']=="user") {
            $message = "Akun Anda Sudah Terdaftar";
        } elseif ($_SESSION['type']=="admin") {
            $message = "Anda Adalah Admin";
        }
    } else $message = "Anda Belum Login"
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status</title>
    <link rel="icon" type="image/png" href="img/icon.png">
    <link rel="stylesheet" href="/bs/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/libraries/fontawesome/css/all.css">
    <link rel="stylesheet" href="assets/styles/main.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="card shadow-sm mx-auto mt-5 mb-4" style="max-width: 32rem;">
            <div class="card-header text-center">
                Status
            </div>
            <div class="card-body text-center">
                <?=$message?>
            </div>
            <div class="card-footer text-center">
                <a href="index.php" class="btn btn-danger">Kembali</a>
            </div>
        </div>
    </div>
<script src="/bs/js/jquery-3.5.1.slim.min.js"></script>
<script src="/bs/js/bootstrap.min.js"></script>
<script src="assets/libraries/fontawesome/js/all.js"></script>
</body>
</html>