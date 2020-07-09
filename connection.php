<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $database = "web_toko";
    $conn = mysqli_connect($host, $user, $pass, $database);
    echo mysqli_error($conn)
?>