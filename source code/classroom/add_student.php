<?php

session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$loca = '';
if ($_SESSION['role'] == '1') {
    $loca = 'class_information.php';
} else if ($_SESSION['role'] == '3'){
    header('Location: home.php');
}
else if ($_SESSION['role'] == '2'){
    $loca = 'class_information.php';
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

$em = '';
$user = $_SESSION['user'];

if (isset($_POST['invite_student'])) {
    $em = $_POST['em'];
    $check = check_email_exist($em);




    if($check['code']==0){
        $r = get_uname_by_email($em);

        if($r['code']==0){
            $d = $r['data'];
            $u = $d['username'];
        }


        $res1 = is_email_in_class($u, $_SESSION['lop1']);

        if($res1['code']==0){
            if (empty($em)) {
                $error = 'Please enter student email';
            }
            else {
                $result = invite_student( $_SESSION['lop1'],$em);

                if($result['code']!=0){
                    $error = $result['error'];
                }
                else{
                    send_invite_student($em, $_SESSION['lop1']);
                    $success = $result['error'];
                }
            }
        }
        else{
            $error = $res1['error'];
        }


    }
    else{
        $error = $check['error'];
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
            <h3 class="text-center  mt-5 mb-3">Nhập email sinh viên vào đây</h3>
            <form method="post" action="" class="new-join-class" enctype="multipart/form-data">
                <div class="form-group">
                    <input value="<?= $em ?>" name="em" id="code" type="text" class="form-control" placeholder="Email">
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
                    <button name="invite_student" class="btn">Mời</button>
                </div>
            </form>

        </div>
    </div>
</div>
</body>
</html>