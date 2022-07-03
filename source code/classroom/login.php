<?php
    session_start();
    if (isset($_SESSION['user'])) {
        header('Location: index.php');
        exit();
    }
    require_once ('db.php');

?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="../style.css">
    <script type="text/javascript" src="../main.js"></script>

</head>
<body>

<?php

$error = '';

$user = '';
$pass = '';

if (isset($_POST['user']) && isset($_POST['pass'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    if (empty($user)) {
        $error = 'Please enter your username';
    }
    else if (empty($pass)) {
        $error = 'Please enter your password';
    }
    else if (strlen($pass) < 6) {
        $error = 'Password must have at least 6 characters';
    }
    else{
        $result = login($user, $pass);
        if($result['code']==0){
            $data = $result['data'];
            $_SESSION['user'] = $user;
            $_SESSION['name'] = $data['fullname'];
            $_SESSION['role'] = $data['role'];

            header('Location: index.php');
            exit();
        }
        else {
            $error = $result['error'];
        }
    }
}
?>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-6">
            <h2 class="form-title">CLASSROOM</h2>
            <form method="post" action="" class=" new-join-class ">
                <h2 class="form-title">LOGIN</h2>
                <div class="form-group">

                    <div class="login-form-username">
                        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        </svg>
                        <input value="<?= $user ?>" name="user" id="user" type="text" class="login-username" placeholder="Username">
                    </div>
                </div>
                <div class="form-group">
                    <div class="login-form-password">
                        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-lock-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2.5 9a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-7a2 2 0 0 1-2-2V9z"/>
                            <path fill-rule="evenodd" d="M4.5 4a3.5 3.5 0 1 1 7 0v3h-1V4a2.5 2.5 0 0 0-5 0v3h-1V4z"/>
                        </svg>
                        <input name="pass" value="<?= $pass ?>" id="password" type="password" class="login-password" placeholder="••••••">
                    </div>
                </div>


                <div class="form-group text-center">
                    <?php
                    if (!empty($error)) {
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                    ?>
                    <button class="btn px-5 button-login">Login</button>
                </div>
                <div class="forgot">
                    <h6><a href="forgot.php">Forgot Password?</a></h6>
                </div>


                <div class="signn-up-left login-form-sign-up">
                    <h6><a href="signup.php">Sign up</a></h6>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>