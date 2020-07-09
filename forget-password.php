<?php
    session_start();
    //cek apakah user telah login
    if (isset($_SESSION['username'])) header("Location: user.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recovery</title>
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
                        <h1 class="mb-4 d-none d-md-block">Ingatlah selalu <br/> Passwordmu!</h1>
                        <img src="img/login.webp" alt="" class="w-75 d-none d-sm-flex">
                    </a>
                </div>
                <div class="section-right col-12 col-md-4 mb-4 mt-4">
                    <div class="card shadow">
                        <div class="card-body">
                            <a href="index.php" class="d-block text-center">
                                <img src="img/logo-rumah.png" alt="logo" class="w-50 mb-4">
                            </a>
                            <p class="alert alert-warning"><strong>Jangan Lupa</strong> ganti password Anda setelah masuk
                            <?php if (isset($_GET['failed'])) echo '<br><br><strong class="text-danger">username atau token salah</strong>' ?>
                            </p>
                            <form action="action-login.php" method="POST">
                                <div class="form-group">
                                  <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                      <div class="input-group-text"><i class="fa fa-at"></i></div>
                                    </div>
                                    <input type="text" class="form-control" id="user" name="username" maxlength="32" oninput="this.value = this.value.toLowerCase()" required placeholder="Username" <?php if (isset($_GET['username'])) echo 'value="'.$_GET['username'].'"'?>>
                                  </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-key"></i></div>
                                        </div>
                                        <input type="text" class="form-control" id="pass" name="token" maxlength="32" required placeholder="Token Akses" <?php if (isset($_GET['token'])) echo 'value="'.$_GET['token'].'"'?>>
                                    </div>
                                </div>
                                    <button type="submit" name="login-token" class="btn btn-info btn-block"><i class="fa fa-unlock"></i> Recovery</button>
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
        $('#pass').on('keypress', function(e) {
            if (e.which == 32)
                return false;
        });
    });
</script>
<script src="/bs/js/bootstrap.min.js"></script>
</body>
</html>