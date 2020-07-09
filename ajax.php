<?php
    session_start();
    if (isset($_SESSION['username']) && $_SESSION['type']=="user" && $_GET['read']==1) {
        include "connection.php";
        $user = $_SESSION['username'];

        $id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM akun WHERE username='$user'"))['id'];
        mysqli_query($conn, "UPDATE notif SET terbaca='1' WHERE id_user='$id' AND terbaca='0'");
    } else header("Location: index.php");
?>