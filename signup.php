<?php

$showAlert = false;
$showPassError = false;
$showUError = false;

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require_once 'partials/_dbconnect.php';

    function SendMail($email, $otp)
    {
        //Load Composer's autoloader
        require("PHPMailer/PHPMailer.php");
        require("PHPMailer/SMTP.php");
        require("PHPMailer/Exception.php");

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        //Server settings
        $mail->isSMTP();                                        //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'btw.im.busy@gmail.com';                     //SMTP username
        $mail->Password   = '****************';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('btw.im.busy@gmail.com', 'Sarang Kulkarni');
        $mail->addAddress($email);

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Email Verification';
        $mail->Body    = 'OTP is <b>' . $otp . '</b>';

        $mail->send();
    }

    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    $exists = false;
    $existsSQL = "SELECT * from `users` where `username` = '$username'";
    $result = mysqli_query($conn, $existsSQL);
    $numExistsRows = mysqli_num_rows($result);
    if ($numExistsRows > 0) {
        $exists = true;
        $showUError = true;
    } else {
        $exists = false;

        if (($password == $confirm_password)) {
            // $hash = password_hash($password, PASSWORD_DEFAULT);
            $otp = bin2hex(random_bytes(16));
            // $sql = "INSERT INTO `users` ( `username`, `password`, `dt`) VALUES ( '$username', '$hash', current_timestamp())";
            SendMail($email, $otp);
            $sql = "INSERT INTO `users` ( `username`, `email`, `password`, `verification_code`) VALUES ( '$username', '$email', '$password', '$otp')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $showAlert = true;
            }
        } else {
            $showPassError = true;
        }
    }
}
?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Sign Up</title>
</head>

<body>
    <?php require 'partials/_nav.php';

    if ($showAlert) {
        echo '<script> alert("Check your Email for Verification Code.</script>';
        header("location: verify.php");
    }

    if ($showPassError) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong> Error!</strong> Passwords do not match <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    }
    if ($showUError) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong> Error!</strong> Username already exists <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    }
    ?>

    <div class="container my-4">

        <h1 class="text-center my-5">Sign Up</h1>

        <form method="post" action="/otp/signup.php">
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email" placeholder="Enter Valid Email Address">
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" placeholder="Enter username" name="username" maxlength="10">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Password" name="password" maxlength="20">
            </div>
            <div class="form-group">
                <label for="confrim_password">Confirm Password</label>
                <input type="password" class="form-control" id="confrim_password" placeholder="Confirm Password" name="confirm_password">
            </div>
            <button type="submit" class="btn btn-primary">SignUp</button>
        </form>

    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>