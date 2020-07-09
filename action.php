<?php
    session_start();
    //cek apakah user telah login
    if (isset($_SESSION['username']) && isset($_SESSION['type'])) {
        include "connection.php";
        include "function.php";
        //cek apakah benar admin yang login
        if ($_SESSION['type']=="admin") {
            $path = "img/produk/";
            if (isset($_POST['add-item'])) {
                $nama = $_POST['nama'];
                $harga = $_POST['harga'];
                $stok = $_POST['stok'];
                $deskripsi = $_POST['deskripsi'];
                $gambar = $nama.FileExtension($_FILES['gambar']['name']);

                move_uploaded_file($_FILES['gambar']['tmp_name'], $path.$gambar);

                $query = "INSERT INTO barang (nama,harga,stok,deskripsi,gambar) VALUES ('$nama','$harga','$stok','$deskripsi','$gambar')";
                mysqli_query($conn, $query);
                header("Location: admin.php");
            } elseif (isset($_POST['edit-item'])) {
                $id = $_POST['id'];
                $nama = $_POST['nama'];
                $harga = $_POST['harga'];
                $stok = $_POST['stok'];
                $deskripsi = $_POST['deskripsi'];

                $result = mysqli_query($conn,"SELECT nama,gambar FROM barang WHERE id='$id'");
                $data = mysqli_fetch_assoc($result);

                if ($_FILES['gambar']['name']=="") {
                    $gambar =  $nama.FileExtension($data['gambar']);
                    if ($nama!=$data['nama']) rename($path.$data['gambar'], $path.$gambar);
                } else {
                    $gambar = $nama.FileExtension($_FILES['gambar']['name']);
                    move_uploaded_file($_FILES['gambar']['tmp_name'], $path.$gambar);
                    if ($nama!=$data['nama']) unlink($path.$data['gambar']);
                }
                $query = "UPDATE barang SET nama='$nama', harga='$harga', stok='$stok', deskripsi='$deskripsi', gambar='$gambar' WHERE id='$id'";
                mysqli_query($conn, $query);
                header("Location: admin.php");
            } elseif (isset($_GET['del-item'])) {
                $id = $_GET['id'];

                $result = mysqli_query($conn,"SELECT gambar FROM barang WHERE id='$id'");
                $data = mysqli_fetch_assoc($result);

                $query = "DELETE FROM barang WHERE id='$id'";
                mysqli_query($conn, $query);
                unlink($path.$data['gambar']);
                header("Location: admin.php");
            } elseif (isset($_POST['delete-user'])) {
                $path = "img/akun/";
                $id = $_GET['id'];

                $result = mysqli_query($conn,"SELECT gambar FROM akun WHERE id='$id'");
                $data = mysqli_fetch_assoc($result);

                $gambar = $data['gambar'];
                unlink($path.$gambar);

                $query = "DELETE FROM akun WHERE id='$id'";
                mysqli_query($conn, $query);
                header("Location: admin.php?pengguna");
            } elseif (isset($_POST['verify-user'])) {
                $id = $_GET['id'];

                $query = "UPDATE akun SET tipe='user' WHERE id='$id'";
                mysqli_query($conn, $query);
                header("Location: user.php?id=".$id);
            } elseif (isset($_POST['order-accept'])) {
                $id = $_GET['id'];
                $date = date("Y-m-d");

                $transaksi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_user,id_barang,jumlah FROM transaksi WHERE id='$id'"));
                $id_user = $transaksi['id_user'];
                $id_barang = $transaksi['id_barang'];
                $jumlah = $transaksi['jumlah'];
                $stok = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stok FROM barang WHERE id='$id_barang'"))['stok'] - $jumlah;

                echo $stok;

                mysqli_query($conn, "UPDATE transaksi SET status='proses' WHERE id='$id'");
                mysqli_query($conn, "UPDATE barang SET stok='$stok' WHERE id='$id_barang'");
                mysqli_query($conn, "INSERT INTO notif (id_user,pesan,tanggal,terbaca) VALUES ('$id_user','Pesanan Anda dengan id: $id sedang diproses','$date','0')");
                header("Location: order.php");
            } elseif (isset($_POST['order-reject'])) {
                $id = $_GET['id'];
                $date = date("Y-m-d");

                $id_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_user FROM transaksi WHERE id='$id'"))['id_user'];
                mysqli_query($conn, "UPDATE transaksi SET status='belum' WHERE id='$id'");
                mysqli_query($conn, "INSERT INTO notif (id_user,pesan,tanggal,terbaca) VALUES ('$id_user','Pesanan Anda dengan id: $id ditolak','$date','0')");
                header("Location: order.php");
            } elseif (isset($_POST['order-done'])) {
                $id = $_GET['id'];
                $date = date("Y-m-d");

                $id_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_user FROM transaksi WHERE id='$id'"))['id_user'];
                mysqli_query($conn, "UPDATE transaksi SET status='selesai' WHERE id='$id'");
                mysqli_query($conn, "INSERT INTO notif (id_user,pesan,tanggal,terbaca) VALUES ('$id_user','Pesanan Anda dengan id: $id sudah selesai','$date','0')");
                header("Location: order.php?proses");
            } elseif (isset($_POST['clear'])) {
                $query = "DELETE FROM transaksi WHERE status='selesai'";
                mysqli_query($conn, $query);
                header("Location: order.php?".$_GET['from']);
            } elseif (isset($_POST['edit-page'])) {
                $nama = $_POST['nama'];
                $telepon = $_POST['telepon'];
                $email = $_POST['email'];
                $alamat = $_POST['alamat'];
                $link = $_POST['link'];
                $keterangan = $_POST['keterangan'];

                $query = "UPDATE info SET nama='$nama', telepon='$telepon', email='$email', alamat='$alamat', link_alamat='$link', keterangan='$keterangan'";
                mysqli_query($conn, $query);
                header("Location: admin.php?halaman");
            } elseif (isset($_POST['edit-page-image'])) {
                if ($_FILES['gambar1']['name']!="") {
                    move_uploaded_file($_FILES['gambar1']['tmp_name'], "img/toko1");
                }
                if ($_FILES['gambar2']['name']!="") {
                    move_uploaded_file($_FILES['gambar2']['tmp_name'], "img/toko2");
                }
                if ($_FILES['gambar3']['name']!="") {
                    move_uploaded_file($_FILES['gambar3']['tmp_name'], "img/toko3");
                }
                header("Location: admin.php?halaman");
            }
        }
        if (isset($_POST['edit-detail'])) {
            $user = $_SESSION['username'];
            $nama = $_POST['nama'];
            $email = $_POST['email'];
            $alamat = $_POST['alamat'];
            $telepon = $_POST['telepon'];

            $akun = mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM akun WHERE username='$user'"));

            $query = "UPDATE akun SET nama='$nama', email='$email', alamat='$alamat', telepon='$telepon' WHERE username='$user'";
            mysqli_query($conn, $query);
            if ($_SESSION['type']=="admin") {
                header("Location: user.php?id=".$akun['id']);
            } else header("Location: user.php");
        } elseif (isset($_POST['edit-password'])) {
            $user = $_SESSION['username'];
            $pass = password_hash($_POST['password'], PASSWORD_ARGON2ID);

            $query = "UPDATE akun SET password='$pass' WHERE username='$user'";
            mysqli_query($conn, $query);
            if ($_SESSION['type']=="admin") {
                $akun = mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM akun WHERE username='$user'"));
                header("Location: user.php?success&id=".$akun['id']);
            } else header("Location: user.php?success");
        } elseif (isset($_POST['edit-image'])) {
            $user = $_SESSION['username'];
            $path = "img/akun/";
            $gambar = $user.FileExtension($_FILES['gambar']['name']);

            $akun = mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM akun WHERE username='$user'"));

            move_uploaded_file($_FILES['gambar']['tmp_name'], $path.$gambar);

            $query = "UPDATE akun SET gambar='$gambar' WHERE username='$user'";
            mysqli_query($conn, $query);
            if ($_SESSION['type']=="admin") {
                header("Location: user.php?id=".$akun['id']);
            } else header("Location: user.php");
        } elseif (isset($_POST['order'])) {
            if ($_SESSION['type']=="admin") {
                header("Location: order.php");
            } elseif ($_SESSION['type']=="guest") {
                header("Location: status.php");
            } else {
                $baru = true;
                $delete = false;
                $user = $_SESSION['username'];
                $id_barang = $_GET['id'];

                $barang = mysqli_query($conn, "SELECT harga FROM barang WHERE id='$id_barang'");
                $produk = mysqli_fetch_assoc($barang);
                $harga = $produk['harga'];

                $akun = mysqli_query($conn, "SELECT id FROM akun WHERE username='$user'");
                $pelanggan = mysqli_fetch_assoc($akun);
                $id_user = $pelanggan['id'];

                $order = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_user='$id_user'");
                if (mysqli_num_rows($order)!=0) {
                    $order = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_user='$id_user' AND id_barang='$id_barang'");
                    if (mysqli_num_rows($order)!=0) {
                        foreach ($order as $data) :
                            if ($data['status']=="belum") {
                                $belum = true;
                            }
                        endforeach;
                        if ($belum) {
                            $order = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_user='$id_user' AND id_barang='$id_barang' AND status='belum'");
                            $orderan = mysqli_fetch_assoc($order);
                            $jumlah = $orderan['jumlah'] + 1;
                            if (isset($_POST['jumlah'])) {
                                $jumlah = $_POST['jumlah'];
                                if ($jumlah=='0') $delete = true;
                            }
                            $total = $jumlah * $harga;
                            $query = "UPDATE transaksi SET jumlah='$jumlah', total='$total' WHERE id_user='$id_user' AND id_barang='$id_barang' AND status='belum'";
                            $baru = false;
                            if ($delete)
                                $query = "DELETE FROM transaksi WHERE id_user='$id_user' AND id_barang='$id_barang' AND status='belum'";
                        }
                    }
                }
                if ($baru)
                    $query = "INSERT INTO transaksi (id_user,id_barang,jumlah,total,status) VALUES ('$id_user','$id_barang','1','$harga','belum')";
                mysqli_query($conn,$query);
                header("Location: order.php");
            }
        } elseif (isset($_POST['confirm-order'])) {
            $user = $_SESSION['username'];

            $id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM akun WHERE username='$user'"))['id'];
            $query = mysqli_query($conn, "UPDATE transaksi SET status='menunggu konfirmasi' WHERE id_user='$id' AND status='belum'");
            header("Location: order.php?proses");
        } elseif (isset($_POST['write-review'])) {
            $user = $_SESSION['username'];
            $id_barang = $_GET['id'];
            $ulasan = $_POST['ulasan'];

            $akun = mysqli_query($conn, "SELECT id FROM akun WHERE username='$user'");
            $pelanggan = mysqli_fetch_assoc($akun);
            $id_user = $pelanggan['id'];

            $query = "INSERT INTO review (id_user,id_barang,ulasan) VALUES ('$id_user','$id_barang','$ulasan')";
            mysqli_query($conn, $query);
            header("Location: product.php?id=".$id_barang);
        }
    } elseif (!isset($_SESSION['username']) && isset($_POST['order'])) {
        header("Location: login.php");
    } else header("Location: index.php")
?>