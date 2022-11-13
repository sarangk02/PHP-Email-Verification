<?php

$showAlert = false;
$showPassError = false;
$showActivatedError = false;


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require_once 'partials/_dbconnect.php';

    $email = $_POST["email"];
    $otp = $_POST["otp"];

    $exists = false;
    $existsSQL = "SELECT * from `users` where `email` = '$email'";
    $result = mysqli_query($conn, $existsSQL);
    $row = mysqli_fetch_row($result);

    if ($row['status'] == 'active') {
        $exists = true;
        $showActivatedError = true;
    } else {
        $sql = "UPDATE `users` SET `status` = 'active' WHERE `email` = '$email'";
        $result = mysqli_query($conn, $sql);
        $showAlert = true;
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
        echo '<script> alert("Email Verified.</script>';
        header("location: login.php");
    }
    if ($showActivatedError) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong> Error!</strong> Email already Verified <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    }
    ?>

    <div class="container my-4">

        <h1 class="text-center my-5">Verify Email ID</h1>

        <form method="post" action="/otp/verify.php">
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email" placeholder="Enter Email Address">
            </div>
            <div class="form-group">
                <label for="verification_code">Verification Code</label>
                <input type="text" class="form-control" id="verification_code" placeholder="Verification Code" name="otp">
            </div>
            <button type="submit" class="btn btn-primary">Verify</button>
        </form>

    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>