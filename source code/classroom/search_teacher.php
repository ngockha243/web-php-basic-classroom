<?php

session_start();
if (!isset($_SESSION['user'])) {
    header('Location: home.php');
    exit();
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
if(isset($_POST['back'])){
    unset($_SESSION['key']);
    header('Location: admin_home.php');
}

$loca = '';
if($_SESSION['role']=='1'){
    $loca = 'admin_home.php';
}
else if($_SESSION['role']=='3'){
    $loca = 'home.php';
}
else{
    $loca = 'teacher_home.php';
}

if(isset($_POST['lop'])){

    if(isset($_POST['lop1'])){
        $_SESSION['lop1'] = $_POST['lop1'];
        header('Location: class_information.php');
    }

}
if(isset($_POST['delete-class'])){
    $error = '';
    $success = '';

    $code = '';
    if(isset($_POST['lop1'])){
        $code = $_POST['lop1'];
        $result = delete_class_by_code($code);
        if($result['code']==1){
            $error = $result['error'];
        }
        else if($result['code']==0){
            $success = $result['error'];
        }
    }
}
if(isset($_POST['edit-class'])){
    $error = '';
    $success = '';

    $name = '';
    $monhoc = '';
    $room = '';
    $code = '';

    if(!isset($_POST['class-name'])){
        $error = 'Vui lòng nhập tên lớp học';
    }else if(!isset($_POST['subject'])){
        $error = 'Vui lòng nhập môn học';
    }else if(!isset($_POST['room'])){
        $error = 'Vui lòng nhập phòng học';
    }else if(isset($_POST['lop1'])){
        $name = $_POST['class-name'];
        $monhoc = $_POST['subject'];
        $room = $_POST['room'];
        $code = $_POST['lop1'];
        $result = edit_class_by_code($name, $monhoc, $room, $code);
        if($result['code']==1){
            $error = $result['error'];
        }
        else if($result['code']==0){
            $success = $result['error'];
        }
    }
}

?>

<form method="POST" action="#">
    <a href="<?=$loca?>">
        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-arrow-left-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
            <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
        </svg>
    </a>
</form>
<div class="form-group">
    <h2>Kết quả tìm kiếm</h2>
</div>
<?php

$error = '';
$success = '';
if(isset($_SESSION['key'])){
$key = $_SESSION['key'];

$result = search_class_by_teacher($key, $_SESSION['user']);

if($result['code']!=0){
    $error = $result['error'];
}
else {

?>






</div>
<div class="row">
    <div class="form-group">
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
    </div>
</div>

<div class = "row">
    <?php

    $data = $result['data'];
    for($i = 0; $i < count($data); $i++){
        $user = $data[$i];
        ?>

        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
            <a href="#">

                <form method="POST" action="#">
                    <div class="card">
                        <button class="lop-lon" name="lop">
                        <div class="card-header-fluid">
                            <div class="banner-items">
                                <img src="<?=$user[3]?>">
                            </div>
                        </div>
                        </button>
                        <div class="card-body">
                            <label for="class-name">Tên lớp học:</label>
                            <input type="text" name="class-name" class="form-control" value="<?=$user[0]?>">
                            <label for="subject">Môn học:</label>
                            <input type="text" name="subject" class="form-control" value="<?=$user[1]?>">
                            <label for="room">Phòng:</label>
                            <input type="text" name="room" class="form-control" value="<?=$user[2]?>">
                            <!--                            <div class = "item-header"><h5>--><?//=$user[0]?><!--</h5></div>-->
                            <!--                            <div class = "item-cost"><h6>--><?//=$user[1]?><!--</h6></div>-->
                            <!--                            <div class = "item-cost"><h6>--><?//=$user[4]?><!--</h6></div>-->
                        </div>
                        <div class="card-footer">
                            <input type="hidden" name="lop1" value="<?=$user[4]?>">


                                <button name="edit-class" class="btn btn-primary float-left" href="" onclick="return confirm('Bạn có chắc muốn sửa thông tin lớp học này?')">Cập nhật</button>
                            <button name="delete-class" class="btn btn-danger float-right" href="" onclick="return confirm('Bạn có chắc muốn xóa lớp học này?')">Xóa</button>


                        </div>

                    </div>

                </form>
            </a>

        </div>
        <?php
    }
    ?>


    <?php

    }}
    ?>
</div>


</div>




</body>
</html>