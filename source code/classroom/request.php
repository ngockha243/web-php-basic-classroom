<?php

    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }
    if ($_SESSION['role']=='3') {
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

$user1 = '';
$code1 = '';
$e = '';
$s = '';
if(isset($_POST['username']) && isset($_POST['code'])){

    $user1 = $_POST['username'];
    $code1 = $_POST['code'];

    if (isset($_POST['yes'])){

        $result1 = add_student_to_class($user1, $code1);

        if ($result1['code'] != 0) {
            $e = $result1['error'];
        } else if ($result1['code']==0){
            $s = $result1['error'];
        }

    }
    if (isset($_POST['no'])){

        $result1 = delete_request($user1, $code1);
        if ($result1['code'] != 0) {
            $e = $result1['error'];
        } else {
            $s = $result1['error'];
        }

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

                    $result = get_all_class();
                    for($i = 0; $i < count($result); $i++){
                        $user = $result[$i];

                        $res = get_teacher($user[4]);
                        $d = $res['data'];
                        $t = $d[0];
                        $teacher = $t[0];




                        ?>
                        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>

                        <form method="POST" action="#">
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

                <a class="navbar-brand" href="#">
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
                        <a class="nav-link " data-toggle="dropdown" href="#">
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
    <div class="row">


    </div>
    <div class="row">
        <div class="form-group">
            <a href="admin_home.php" class="user-manager" name="user-manager">
                <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-arrow-left-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                    <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
                </svg>
            </a>
            <label for="user-manager">Home</label>
        </div>



    </div>
    <div class="form-group">
        <?php
        if (!empty($success)) {
            echo "<div class='alert alert-success'>$success</div>";
        }
        ?>
        <?php
        if (!empty($error)) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
        ?>
    </div>
    <div class="form-group">
        <h2>Yêu cầu tham gia lớp học</h2>
    </div>

    <table class="table">
        <thead class="black white-text">
        <tr>
            <th scope="col">Tên người dùng</th>
            <th scope="col">Tên lớp học</th>
            <th scope="col">Yêu cầu</th>
        </tr>
        </thead>
        <tbody>
        <?php

        $result = get_code($_SESSION['user']);
        $data = $result['data'];
        for($i = 0; $i < count($data); $i++){
            $temp = $data[$i];

            $res1 = get_fname_uname($temp[0]);
            $res2 = get_subj_code($temp[1]);
            $r1 = '';
            $r2 = '';
            $user ='';
            $sub='';
            if($res1['code']==0){
                $r1 = $res1['data'];
                $user = $r1['fullname'];
            }
            if($res2['code']==0){
                $r2 = $res2['data'];
                $sub = $r2['monhoc'];
            }

            ?>
            <tr>
                <td ><?=$user?></td>
                <td><?=$sub?></td>
                <td>
                    <form method="POST" action="#">
                        <input name="username" type="hidden" value="<?=$temp[0]?>">
                        <button name="yes" class="btn btn-success" onclick="return confirm('Bạn có chắc cho  <?=$user?> tham gia vào lớp học <?=$sub?>?')">Đồng ý</button>
                        <input name="code" type="hidden" value="<?=$temp[1]?>">
                        <button name="no" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa yêu cầu?')">Từ chối</button>
                    </form>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>



</div>




</body>
</html>