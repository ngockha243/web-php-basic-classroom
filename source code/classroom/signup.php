<?php
    session_start();
    if (isset($_SESSION['user'])) {
        header('Location: index.php');
        exit();
    }
    require_once ('db.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="../style.css">
    <script src="../main.js"></script>
</head>
<body>
<?php
    $error = '';
    $name = '';
    $email = '';
    $user = '';
    $pass = '';
    $pass_confirm = '';
    $birth = '';
    $phone = '';
    $role = '3';
    $success = '';

    if (isset($_POST['name']) && isset($_POST['email'])
    && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['re_password'])
        && isset($_POST['birthday']) && isset($_POST['phone']))
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $pass_confirm = $_POST['re_password'];
        $birth = $_POST['birthday'];
        $phone = $_POST['phone'];
        $role = $_POST['role'];

        if (empty($name)) {
            $error = 'Please enter your full name';
        }
        else if (empty($email)) {
            $error = 'Please enter your email';
        }
        else if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            $error = 'This is not a valid email address';
        }
        else if (empty($user)) {
            $error = 'Please enter your username';
        }
        else if (empty($pass)) {
            $error = 'Please enter your password';
        }
        else if (strlen($pass) < 6) {
            $error = 'Password must have at least 6 characters';
        }
        else if ($pass != $pass_confirm) {
            $error = 'Password does not match';
        }
        else if (empty($birth)) {
            $error = 'Please enter your birthday';
        }
        else if (empty($phone)) {
            $error = 'Please enter your phone number';
        }
        else {

            $result = register($user, $name, $birth, $email, $phone, $pass, $role);
            if($result['code']==0){
                $success = 'Đăng ký thành công. Vui lòng vào email để xác nhận tài khoản';

            }else if($result['code']==1){
                $error = 'This email is already exists';
            }
            else{
                $error = 'An error occured. Please try again later';
            }

        }
    }
?>


<div class="container">
    <div class="signup-content">
        <h2 class="form-title">CLASSROOM</h2>
        <form method="POST" action="" id="signup-form" class="new-join-class" novalidate>
            <h2 class="form-title">Create account</h2>
            <div class="form-group">
                <input value="<?= $name?>" type="text" class="form-control" name="name" id="name" placeholder="Your Name"/>
            </div>
            <div class="form-group">
                <input value="<?= $email?>" type="email" class="form-control" name="email" id="email" placeholder="Your Email"/>
            </div>
            <div class="form-group">
                <input value="<?= $user?>" type="text" class="form-control" name="username" id="username" placeholder="User Name"/>
            </div>
            <div class="form-group">
                <input  value="<?= $pass?>" type="password" class="form-control" name="password" id="password" placeholder="Password"/>
            </div>
            <div class="form-group">
                <input value="<?= $pass_confirm?>" type="password" class="form-control" name="re_password" id="re_password" placeholder="Repeat your password"/>
            </div>
            <div class="form-group">
                <input value="<?= $birth?>"  type="date" placeholder="Your Birthday: " name="birthday" class="form-control" type="text" id="birthday">
            </div>
            <div class="form-group">
                <input value="<?= $phone?>" type="text" class="form-control" name="phone" id="phone" placeholder="Your Phone number"/>
            </div>
            <div class="form-group">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" class="custom-control-input" id="student" name="role" value="3" checked>
                    <label class="custom-control-label" for="student">Student </label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" class="custom-control-input" id="teacher" name="role" value="2">
                    <label class="custom-control-label" for="teacher">Teacher</label>
                </div>
            </div>
            <div class="form-group text-center">
                <?php
                if (!empty($error)) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
                ?>
                <?php
                if (!empty($success)) {
                    echo "<div class='alert alert-success'>$success</div>";
                }
                ?>
                <button type="submit" class="btn  px-5 button-login ">Sign up</button>
            </div>

            <div class="login-now">
                Already have an account? <a href="login.php"><b> Login </b></a> now.
            </div>
        </form>

    </div>
</div>
</body>
</html>

