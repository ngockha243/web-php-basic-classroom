<?php

    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }
    if(!isset($_SESSION['role'])=='3'){
        header('Location: home.php');
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

    $name = '';
    $subject = '';
    $room = '';
    $success = '';

    if (isset($_POST['name']) && isset($_POST['subject']) && isset($_POST['room'])) {
        $name = $_POST['name'];
        $subject = $_POST['subject'];
        $room = $_POST['room'];
        $avt = $_FILES['fileToUpload']['type'];

        if (empty($name)) {
            $error = 'Please enter class name';
        }
        else if (empty($subject)) {
            $error = 'Please enter subject';
        }
        else if (empty($room)) {
            $error = 'Please enter room';
        }
        else if(!isset($avt)){
            $error = 'Please choose your avatar';
        }
        else {
            if (($_FILES['fileToUpload']['name']!="")){
                $target_dir = "images/";
                $file = $_FILES['fileToUpload']['name'];
                $path = pathinfo($file);
                $filename = $path['filename'];
                $ext = $path['extension'];
                $temp_name = $_FILES['fileToUpload']['tmp_name'];
                $rand = random_int(0, 1000);
                $filename = $filename.$rand;
                $path_filename_ext = $target_dir.$filename.".".$ext;

                if($ext != 'jpg' && $ext != 'png' && $ext != 'jpeg') {
                    $error = 'Please choose image file';
                }
                else{
                    if (file_exists($path_filename_ext)) {
                        $error =  'Sorry, file already exists';
                    }else{
                        move_uploaded_file($temp_name,$path_filename_ext);
                        $code = create_code();

                        $result = create_new_class($name, $subject, $room, $path_filename_ext, $code);
                        $res = create_new_class_teacher($_SESSION['user'], $code);

                        if($result['code']==0 && $res['code']==0){
                            $success = $result['error'];
                        }
                    }
                }
            }
            else{
                $r = random_int(1, 3);
                $av = 'images/avata'.$r.'.jpg';
                $code = create_code();

                $result = create_new_class($name, $subject, $room, $av, $code);
                $res = create_new_class_teacher($_SESSION['user'], $code);

                if($result['code']==0 && $res['code']==0){
                    $success = $result['error'];
                }
            }
        }
    }

?>
<?php
    $loca = '';
    if($_SESSION['role']=='1'){
        $loca = 'admin_home.php';
    }
    else{
        $loca = 'home.php';
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
        <div class="col-md-6 col-lg-5">
            <h3 class="text-center mt-5 mb-3">Tạo lớp mới</h3>
            <form method="post" action="" class="new-join-class text-center" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="class">Tên lớp</label>
                    <input value="<?= $name ?>" name="name" id="name" type="text" class="form-control" placeholder="Tên lớp">
                </div>
                <div class="form-group">
                    <label for="subject">Môn học</label>
                    <input value="<?= $subject ?>" name="subject" id="subject" type="text" class="form-control" placeholder="Môn học">
                </div>
                <div class="form-group">
                    <label for="room">Phòng học</label>
                    <input value="<?= $room ?>" name="room" id="room" type="text" class="form-control" placeholder="Phòng học">
                </div>
                <div class="form-group ">
                    Chọn ảnh đại diện:
                    <input type="file" name="fileToUpload" id="fileToUpload">
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
                    <button type="submit" class="btn">Create new class</button>
                </div>
            </form>

        </div>
    </div>
</div>
</body>
</html>