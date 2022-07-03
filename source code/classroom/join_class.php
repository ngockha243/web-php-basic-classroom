<?php

    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }

    $loca = '';
    if ($_SESSION['role'] == '1') {
        $loca = 'admin_home.php';
    } else if ($_SESSION['role'] == '3'){
        $loca = 'home.php';
    }
    else if ($_SESSION['role'] == '2'){
        $loca = 'teacher_home.php';
    }


require_once ('db.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Classroom</title>
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
$success = '';

$code = '';
$user = $_SESSION['user'];

if (isset($_POST['code'])) {
    $code = $_POST['code'];
    $check = check_code_exist($code);

    if($check['code']==0){
        if (empty($code)) {
            $error = 'Please enter class code';
        }
        else {
            $result = get_in_class($user, $code);

            $c = get_em_by_uname('lucluc');
            $d = $c['data'];
            $e = $d['email'];

            if($result['code']!=0){
                $error = $result['error'];
            }
            else{
                send_join_student($e, $code);
                $success = $result['error'];
            }
        }
    }

    else{
        $error = 'Code không tồn tại';
    }


}

?>

<a href="<?=$loca?>">
    <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-arrow-left-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
    </svg>
</a>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 ">
            <h3 class="text-center  mt-5 mb-3">Nhập code lớp học vào đây</h3>
            <form method="post" action="" class="new-join-class" enctype="multipart/form-data">
                <div class="form-group">
                        <input value="<?= $code ?>" name="code" id="code" type="text" class="form-control" placeholder="Mã code">
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
                    <button type="submit" class="btn">Tham gia</button>
                </div>
            </form>

        </div>
    </div>
</div>
</body>
</html>