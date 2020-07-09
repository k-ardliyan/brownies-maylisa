<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    if (isset($_GET['username']) && isset($_GET['email'])) {
        include "connection.php";

        $user = $_GET['username'];
        $email = $_GET['email'];
            
        $result = mysqli_query($conn, "SELECT id FROM akun WHERE username='$user' AND email='$email'");
        if (mysqli_num_rows($result)!=0) {
            $token = bin2hex(openssl_random_pseudo_bytes(16));

            require 'c:/xampp/composer/vendor/autoload.php';

            $mail = new PHPMailer(true);
            
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Mailer     = "smtp";
                $mail->SMTPDebug  = 1;
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'asrovitaru@gmail.com';
                $mail->Password   = 'ipangts1289';
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom('asrovitaru@gmail.com', 'Admin Rumah Brownies Maylisa');
                $mail->addAddress($email, $user);
                $mail->addReplyTo('asrovitaru@gmail.com', 'Admin Rumah Brownies Maylisa');

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Lupa Password';
                $mail->Body    = 'Klik URL berikut untuk masuk ke Akun Anda<br>http://rumahbrownies.ddns.net/forget-password.php?username='.$user.'&token='.$token;

                $mail->send();

                $token = password_hash($token, PASSWORD_ARGON2ID);
                mysqli_query($conn, "UPDATE akun SET token='$token' WHERE username='$user'");

                header("Location: login.php?login=4");
            } catch (Exception $e) {
                header("Location: login.php?login=5");
            }
        } else header("Location: login.php?forget&login=5");
    } else header("Location: index.php");
?>