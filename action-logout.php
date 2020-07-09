<?php
    session_start();
    //cek apakah user telah login
    if (isset($_SESSION['username'])) {
        $_SESSION['username'] = '';
        $_SESSION['type'] = '';
        unset($_SESSION['username']);
        unset($_SESSION['type']);
        session_unset();
        session_destroy();
    }
    header("Location: index.php")
?>