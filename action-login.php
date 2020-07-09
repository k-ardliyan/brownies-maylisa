<?php
    //cek apakah ada username dan password yang diinput
    if (isset($_POST['username']) && (isset($_POST['password'])) || isset($_POST['email']) || isset($_POST['token'])) {
        include "connection.php";
        $user = $_POST['username'];
        
        //kondisi jika login
        if (isset($_POST['login'])) {
            $pass = $_POST['password'];

            $result = mysqli_query($conn, "SELECT username FROM akun WHERE username='$user'");
            if (mysqli_num_rows($result)!=0) {
                if (password_verify($pass, mysqli_fetch_assoc(mysqli_query($conn, "SELECT password FROM akun WHERE username='$user'"))['password'])) {
                    $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username,tipe FROM akun WHERE username='$user'"));
                    session_start();
                    $_SESSION['username'] = $result['username'];
                    $_SESSION['type'] = $result['tipe'];
                    header("Location: index.php");
                } else header("Location: login.php?login=1");
            } else header("Location: login.php?login=1");
            
        //kondisi jika mendaftar
        } elseif (isset($_POST['signup'])) {
            include "function.php";
            $pass = password_hash($_POST['password'], PASSWORD_ARGON2ID);

            $path = "img/akun/";
            $nama = $_POST['nama'];
            $email = $_POST['email'];
            $alamat = $_POST['alamat'];                
            $telepon = $_POST['telepon'];

            if ($_FILES['gambar']['name']=='')
                $query = "INSERT INTO akun (username,password,tipe,nama,email,alamat,telepon,gambar) VALUES ('$user','$pass','guest','$nama','$email','$alamat','$telepon',NULL)";
            else {
                $gambar = $user.FileExtension($_FILES['gambar']['name']);
                $query = "INSERT INTO akun (username,password,tipe,nama,email,alamat,telepon,gambar) VALUES ('$user','$pass','guest','$nama','$email','$alamat','$telepon','$gambar')";
            }
                
            if (mysqli_query($conn,$query) == 'true' ) {
                move_uploaded_file($_FILES['gambar']['tmp_name'], $path.$gambar);
                header("Location: login.php?login=2");
            }
            else {
                header("Location: login.php?login=3");
            }

        //kondisi jika lupa password
        } elseif (isset($_POST['forget-password'])) {
            $email = $_POST['email'];

            $result = mysqli_query($conn, "SELECT id FROM akun WHERE username='$user' AND email='$email'");

            if (mysqli_num_rows($result)!=0) {
                header("Location: mailpassword.php?username=".$user."&email=".$email);
            } else header("Location: login.php?forget&login=5");

        //kondisi jika user login dengan token
        } elseif (isset($_POST['login-token'])) {
            $token = $_POST['token'];

            $result = mysqli_query($conn, "SELECT username FROM akun WHERE username='$user'");
            if (mysqli_num_rows($result)!=0) {
                if (password_verify($token, mysqli_fetch_assoc(mysqli_query($conn, "SELECT token FROM akun WHERE username='$user'"))['token'])) {
                    mysqli_query($conn, "UPDATE akun SET token=NULL WHERE username='$user'");
                    $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username,tipe FROM akun WHERE username='$user'"));
                    session_start();
                    $_SESSION['username'] = $result['username'];
                    $_SESSION['type'] = $result['tipe'];
                    header("Location: user.php");
                } else header("Location: forget-password.php?failed");
            } else header("Location: forget-password.php?failed");
        }
    } else header("Location: index.php")
?>