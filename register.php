<?php
    session_start();
    //cek apakah user telah login
    if (isset($_SESSION['username'])) header("Location: index.php")
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="icon" type="image/png" href="img/icon.png">
    <link rel="stylesheet" href="/bs/css/bootstrap.min.css">
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
<body>
    <!-- new -->
    <main class="register-container">
        <div class="container">
            <div class="row page-login d-flex align-items-center">
                <div class="section-left fixed-right">
                    <a href="index.php" style="text-decoration: none">
                        <h1 class="mb-4 d-none d-lg-flex">Temukan <br/>Browniesmu!</h1>
                        <img src="img/login.webp" alt="Logo" class="w-75 d-none d-lg-flex">
                    </a>
                </div>
                <div class="section-right col-12 col-lg-6 mt-5 mb-5">
                    <div class="card shadow">
                        <div class="card-body">
                            <a href="index.php" class="d-block text-center">
                                <img src="img/logo-rumah.png" alt="logo" class="w-50 mb-4">
                            </a>
                            <form action="action-login.php" enctype="multipart/form-data" method="POST">
                                <div class="form-group">
                                    <label for="user">Username</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                          <div class="input-group-text"><i class="fa fa-at"></i></div>
                                        </div>
                                        <input type="text" class="form-control" id="user" name="username" minlength="4" maxlength="32" oninput="this.value = this.value.toLowerCase()" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" maxlength="64" oninput="this.value = this.value.toLowerCase()" required>
                                </div>
                                <div class="d-flex">
                                    <div class="form-group col-6 pl-0">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" minlength="4" maxlength="32" required>
                                    </div>
                                    <div class="form-group col-6 pr-0 pl-1">
                                        <label for="confirm_password">Ulangi Password</label>
                                        <input type="password" class="form-control" id="confirm_password" minlength="4" maxlength="32" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama" name="nama" maxlength="64" required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="telepon">Telepon</label>
                                    <input type="number" class="form-control" id="telepon" name="telepon" maxlength="16" required>
                                </div>
                                <div class="form-group">
                                    <label for="gambar" class="d-block">Foto Profil <span class="blockquote-footer d-inline">Opsional</span></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" accept=".jpg,.jpeg,.png" id="gambar" name="gambar" role="button">
                                        <label class="custom-file-label" for="gambar" data-browse="Pilih File" role="button">File gambar jpg, jpeg, png</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="signup" class="btn btn-primary btn-block">Daftar</button>
                                    <a href="login.php" class="btn btn-outline-light btn-block text-muted text-decoration-none"><i class="fa fa-sign-in-alt"></i> Saya Sudah Terdaftar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- end-new -->
<script src="/bs/js/jquery-3.5.1.slim.min.js"></script>
<script>
    $(function() {
        $('#user').on('keypress', function(e) {
            if (e.which == 32)
                return false;
        });
        $('#email').on('keypress', function(e) {
            if (e.which == 32)
                return false;
        });
    });
</script>
<script src="assets/scripts/confirm-password.js"></script>
<script src="assets/scripts/bs-custom-file-input.min.js"></script>
<script>
    $(document).ready(function () {
        bsCustomFileInput.init()
    })
</script>
<script src="/bs/js/bootstrap.min.js"></script>
</body>
</html>