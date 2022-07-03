<?php

session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
if($_SESSION['role']=='3'){
    header('Location: home.php');
}
else if($_SESSION['role']=='1'){
    header('Location: admin_home.php');
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
    <script type="text/javascript" src="../main.js"></script>
</head>
<body>
<?php
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
if(isset($_POST['search-box'])){
    $error = '';
    $key = $_POST['search'];
    $_SESSION['key']=$key;
    if($key==''){
        unset($_SESSION['key']);
        $error = 'Vui lòng nhập từ khóa trước khi tìm kiếm';
    }
    else{
        $_SESSION['key'] = $key;
        header('Location: search_teacher.php');
    }
}

if(isset($_POST['lop'])){

    if(isset($_POST['lop1'])){
        $_SESSION['lop1'] = $_POST['lop1'];
        header('Location: class_information.php');
    }

}




?>
<header id="header">
    <div class="container">
        <nav class="navbar navbar-expand-sm  fixed-top">
            <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">

                <div id="mySidepanel" class="sidepanel">
                    <?php
                    $res1 = get_fname_uname($_SESSION['user']);
                    $d1 = $res1['data'];
                    $ten_nguoi_dung = $d1['fullname'];
                    ?>


                    <h2><?=$ten_nguoi_dung?></h2>
                    <?php

                    $result = get_class_by_teacher($_SESSION['user']);
                    for($i = 0; $i < count($result); $i++){
                        $user = $result[$i];

                        $res = get_teacher($user[4]);
                        $d = $res['data'];
                        $t = $d[0];
                        $teacher = $t[0];




                        ?>
                        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>

                        <form method="POST" action="">
                            <button class="lop" name="lop">
                                <a> - <?=$user[0]?>  <h6><?=$teacher?></h6>
                                </a>

                            </button>
                            <input type="hidden" name="lop1" value="<?=$user[4]?>">
                        </form>

                        <?php
                    }
                    ?>
                </div>

                <button class="menu-home-slide" onclick="openNav()">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-list" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M2.5 11.5A.5.5 0 0 1 3 11h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 7h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 3h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                </button>

                <a class="navbar-brand" href="admin_home.php">
                    Classroom
                </a>
            </div>

            <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
                <ul class="navbar-nav ml-auto">

                    <li class="nav-item dropdown dropleft float-right">
                        <button type="button" class="btn-add-new-course " data-toggle="dropdown">
                            <a class="nav-link" href=""><i class="fas fa-plus"></i></a>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="join_class.php">Tham gia vào lớp học</a>
                            <a class="dropdown-item" href="create_new_class.php">Thêm lớp học</a>
                            <a class="dropdown-item" href="request.php">Yêu cầu tham gia lớp</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown dropleft float-right">
                        <a class="nav-link " data-toggle="dropdown" href="">
                            <img src="images/avt.jpg" width="32px" height="32px" class="img-avt">
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>

        </nav>
    </div>
</header>


<div class="container-fluid">
    <!--    <div class="row">-->
    <!--        <div class="col-12">-->
    <!---->
    <!---->
    <!---->
    <!--        </div>-->


    <div class="row d-flex justify-content-between">
        <div class="form-group">


        </div>
        <div class="search-container p-2 justify-content-end ">
            <form action="" method="POST">
                <input type="text" placeholder="Tìm kiếm lớp học.." name="search">
                <button name="search-box"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </div>
    <div class="row >

    </div>

    <div class = "row">
    <?php

    $result = get_class_by_teacher($_SESSION['user']);
    for($i = 0; $i < count($result); $i++){
        $user = $result[$i];
        ?>

        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

            <form method="POST" action="">

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
                        <input type="text" name="class-name" class="form-control" id="class-name" value="<?=$user[0]?>">

                        <label for="subject">Môn học:</label>
                        <input type="text" name="subject" class="form-control" id="subject" value="<?=$user[1]?>">

                        <label for="room">Phòng:</label>
                        <input type="text" name="room" class="form-control" id="room" value="<?=$user[2]?>">
                        <!--                            <div class = "item-header"><h5>--><?//=$user[0]?><!--</h5></div>-->
                        <!--                            <div class = "item-cost"><h6>--><?//=$user[1]?><!--</h6></div>-->
                        <!--                            <div class = "item-cost"><h6>--><?//=$user[4]?><!--</h6></div>-->
                    </div>
                    <div class="card-footer">
                        <input type="hidden" name="lop1" value="<?=$user[4]?>">


                        <button name="edit-class" class="btn btn-primary float-left " href="" onclick="return confirm('Bạn có chắc muốn sửa thông tin lớp học này?')">Cập nhật</button>
                        <button name="delete-class" class="btn btn-danger float-right" href="" onclick="return confirm('Bạn có chắc muốn xóa lớp học này?')">Xóa</button>


                    </div>
                </div>

            </form>

        </div>
        <?php
    }
    ?>



</div>


</div>


</div>

</body>
</html>