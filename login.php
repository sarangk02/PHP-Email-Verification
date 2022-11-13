<?php

$login = false;
$showError = false;
$verifyerror = false;

if (isset($_SESSION['loggedin'])) {
    header('loction: wlcm.php');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require_once 'partials/_dbconnect.php';
    $username = $_POST["username"];
    $password = $_POST["password"];

    // $sql = "SELECT * from `users` where `username` = '$username' AND `password` = '$password'";
    $sql = "SELECT * from `users` where `username` = '$username'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if ($num == 1) {

        while ($row = mysqli_fetch_assoc($result)) {

            if ($row['status'] == 'active') {

                // if (password_verify($password, $row['password'])){
                if ($password == $row['password']) {
                    $login = true;
                    session_start();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $username;
                    header('location: wlcm.php');
                } else {
                    $showError = true;
                }
            } else {
                $verifyerror = true;
            }
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

    <title>Log In</title>
</head>

<body>
    <?php require 'partials/_nav.php';

    if ($login) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Success!</strong> You are logged In.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    }

    if ($showError) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong> Error!</strong> Invalid Credentials. <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    }
    if ($verifyerror) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong> Error!</strong> Please <a href="verify.php">Verify</a> your Email ID first. <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    }
    ?>

    <div class="container my-4">

        <h1 class="text-center my-5">Log In</h1>

        <form method="post" action="/otp/login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" maxlength="10" class="form-control" id="username" placeholder="Enter your username" name="username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" maxlength="20" class="form-control" id="password" placeholder="Password" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Log In</button>
        </form>

    </div>


    <!-- Optional Javascript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>