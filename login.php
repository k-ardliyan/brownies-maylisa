<?php
    $login = 0;
    $forget = 0;

    session_start();
    //cek apakah user telah login
    if (isset($_SESSION['username'])) header("Location: index.php");

    //mengubah jenis message tergantung status login
    if (isset($_GET['login'])) $login = $_GET['login'];

    if (isset($_GET['forget'])) $forget = 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/png" href="img/icon.png">
    <link rel="stylesheet" href="/bs/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/libraries/fontawesome/css/all.css">
    <link rel="stylesheet" href="assets/styles/main.css">
</head>
<body>
    <main class="login-container">
        <div class="container">
            <div class="row page-login d-flex align-items-center">
                <div class="section-left col-12 col-md-6 d-none d-md-block">
                    <a href="index.php" style="text-decoration: none">
                        <h1 class="mb-4 d-none d-md-block">Pesan Browniesmu <br/> Sekarang!</h1>
                        <img src="img/login.webp" alt="" class="w-75 d-none d-sm-flex">
                    </a>
                </div>
                <div class="section-right col-12 col-md-4 mb-4 mt-4">
                    <div class="card shadow">
                        <div class="card-body">
                            <a href="index.php" class="d-block text-center">
                                <img src="img/logo-rumah.png" alt="logo" class="w-50 mb-4">
                            </a>
                            <form action="action-login.php" method="POST">
                                <div class="form-group">
                                  <label for="user">Username</label>
                                  <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                      <div class="input-group-text"><i class="fa fa-at"></i></div>
                                    </div>
                                    <input type="text" class="form-control" id="user" name="username" maxlength="32" oninput="this.value = this.value.toLowerCase()" required>
                                  </div>
                                </div>
                                <?php if ($forget==1) { ?>
                                    <div class="form-group">
                                    <label for="email">Email</label>
                                        <div class="input-group mb-2">
                                            <input type="email" class="form-control" id="email" name="email" maxlength="64" required>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="form-group">
                                    <label for="pass">Password</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-key"></i></div>
                                            </div>
                                            <input type="password" class="form-control" id="pass" name="password" maxlength="32" required>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="form-group text-center">
                                    <?php if ($forget==1) { ?>
                                        <a href="login.php" class="d-block text-right mb-1">Saya ingat</a>
                                    <?php } else { ?>
                                        <a href="login.php?forget" class="d-block text-right mb-1">Saya lupa password</a>
                                    <?php } ?>
                                    <?php if ($login==1) { ?>
                                        <p class="text-danger">Username atau password salah!</p>
                                    <?php } elseif ($login==2) { ?>
                                        <p class="text-success">Anda berhasil mendaftar!, silahkan verifikasikan akun anda di cabang terdekat</p>
                                    <?php } elseif ($login==3) { ?>
                                        <p class="text-warning">Username yang sama telah terdaftar!</p>
                                    <?php } elseif ($login==4) { ?>
                                        <p class="text-success">Silahkan cek email Anda!</p>
                                    <?php } elseif ($login==5) { ?>
                                        <p class="text-danger">Username atau email salah!</p>
                                    <?php } else {?>
                                        <p>&nbsp;</p>
                                    <?php } ?>
                                </div>
                                <?php if ($forget==1) { ?>
                                    <button type="submit" name="forget-password" class="btn btn-success btn-block">Cek Password</button>
                                <?php } else { ?>
                                    <button type="submit" name="login" class="btn btn-primary btn-block"><i class="fa fa-sign-in-alt"></i> Masuk</button>
                                <?php } ?>
                                <a href="register.php" class="btn btn-outline-light btn-block text-muted" style="text-decoration: none;">Belum Punya Akun?</a>
                              </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
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
<script src="/bs/js/bootstrap.min.js"></script>
</body>
</html>