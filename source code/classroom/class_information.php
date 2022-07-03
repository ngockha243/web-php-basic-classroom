<?php

    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }
    if($_SESSION['role']==3){
        header('Location: class_information_student.php');
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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>







<?php

    $codelop = $_SESSION['lop1'];

    $result = get_classinfo_by_code($codelop);
    $data = $result['data'];

    $res = get_teacher($codelop);
    $d = $res['data'];
    $g = $d[0];
    $giaovien = $g[0];

    if(isset($_POST['dang'])){
        $error = '';
        $success = '';

        $content = '';
        $file = '';
        if(isset($_POST['content'])){
            $content = $_POST['content'];
        }

        if(!empty($content)){
            if (($_FILES['fileToUpload']['name']!="")){
                $target_dir = "files/";
                $file = $_FILES['fileToUpload']['name'];
                $path = pathinfo($file);
                $filename = $path['filename'];
                $ext = $path['extension'];
                $temp_name = $_FILES['fileToUpload']['tmp_name'];
                $rand = create_code();
                $filename = $filename.$rand;
                $path_filename_ext = $target_dir.$filename.".".$ext;


                move_uploaded_file($temp_name,$path_filename_ext);

                $file = $path_filename_ext;
            }
            $class_code = $codelop;
            $res1 = get_fname_uname($_SESSION['user']);
            $data1 = $res1['data'];
            $uname = $data1['fullname'];

            $res2 = post_bai($class_code, $content, $file, $_SESSION['user']);

            $list_sv_a = get_student_by_code($class_code);
            $list_sv = $list_sv_a['data'];

            $ten_lop_a = get_subj_code($class_code);
            $ten_lop_b = $ten_lop_a['data'];
            $ten_lop = $ten_lop_b['monhoc'];

            for($m = 0; $m<count($list_sv);$m++){
                $sv = $list_sv[$m];
                $sv_uname = $sv[0];
                send_email_not($sv_uname, $ten_lop, $content);
            }

            if($res2['code']!=0){
                $error = $res2['error'];
            }
            else{
                $success = $res2['error'];
            }
        }

    }
    if(isset($_POST['user-comment-btn'])){
        $ctn = '';
        if(isset($_POST['user-comment-content'])){
            $ctn = $_POST['user-comment-content'];
            if(!empty($ctn)){
                $post_code_comment = $_POST['post-code-comment'];
                $user_comment = $_SESSION['user'];
                $res_comment = post_comment($post_code_comment, $ctn, $user_comment);
            }

        }
    }
    $teacher_delete_uname = '';
    if(isset($_POST['delete-teacher'])){
        $error = '';
        $success = '';
        $teacher_delete_uname = $_POST['teacher-name-delete'];
        $res7 = delete_teacher_from_class($teacher_delete_uname);
        if($res7['code']!=0){
            $error = $res7['error'];
        }else if($res7['code']==0){
            $success = $res7['error'];
        }
    }
    $student_delete_uname = '';
    if(isset($_POST['delete-student'])){
        $error = '';
        $success = '';
        $student_delete_uname = $_POST['student-name-delete'];
        $res9 = delete_student_from_class($student_delete_uname);
        if($res9['code']!=0){
            $error = $res9['error'];
        }else if($res9['code']==0){
            $success = $res9['error'];
        }
    }
    if(isset($_POST['add_student'])){
        header('Location: add_student.php');
    }
    if(isset($_POST['delete_comment'])){
        $error='';
        $success = '';
        $cmt_code = $_POST['delete_comment_in'];
        $res10 = delete_comment($cmt_code);
        if($res10['code']!=0){
            $error = $res10['error'];
        }
        else if($res10['code']==0){
            $success = $res10['error'];
        }
    }
    if(isset($_POST['update_post'])){
        $error = '';
        $success = '';
        $post_code_in = $_POST['post_code_in'];
        $content_post_in = $_POST['post_ctn'];

        if(!empty($content_post_in)){
            $res12 = update_post($post_code_in, $content_post_in);
            if($res12['code']!=0){
                $error = $res12['error'];
            }
            else if($res12['code']==0){
                $success = $res12['error'];
            }
        }




    }
    if(isset($_POST['delete_post'])){
        $error = '';
        $success = '';
        $post_code_in = $_POST['post_code_in'];
        $res13 = delete_post($post_code_in);
        if($res13['code']!=0){
            $error = $res13['error'];
        }
        else if($res13['code']==0){
            $success = $res13['error'];
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
<div class="back">
    <a href="<?=$loca?>">
        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-arrow-left-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
            <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
        </svg>
    </a>
</div>

<div class="container">

    <div class="jumbotron">


        <img class="class-info-img" src="<?=$data['avatar']?>">
        <div class="info-text">
        <h1><?=$data['name']?></h1>
            <?php

                $gvien_fname = get_fname_uname($giaovien);
                $d_gvien = $gvien_fname['data'];
                $gvien_fname_out = $d_gvien['fullname'];
            ?>
        <h5>Tên giảng viên: <?=$gvien_fname_out?></h5>
        <h6>Phòng: <?=$data['phong']?></h6>
        <h6>Mã code: <?=$data['code']?></h6>
        </div>
    </div>

    <div class="row">
        <div class="col-9">
            <div class="jumbotron">
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
            <form method="POST" action="" enctype="multipart/form-data">

                <div class="accordion" id="accordionExample">
                    <div class="jumbotron">
                        <div class="">
                            <textarea name="content" class="form-control" data-target="#collapseOne" rows="4" aria-expanded="true" aria-controls="collapseOne" data-toggle="collapse" placeholder="Nhấn vào đây để đăng bài viết..."></textarea>
                        </div>

                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body">

                                <label class="" for="fileToUpload"><i class="material-icons">attachment</i></label>
                                <input type="file" name="fileToUpload" id="fileToUpload" class="">


                                <button class="btn btn-success dang" name="dang">Đăng</button>

                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <?php

                $res3 = get_post_by_class_code($codelop);
                $data3 = $res3['data'];
                for($i = 0; $i <count($data3); $i++){
                    $po = $data3[$i];
                    $f = $po[4];
                    $res11 = get_fname_uname($po[5]);
                    $data11 = $res11['data'];
                    $name11 = $data11['fullname'];


            ?>

            <div class="jumbotron">
                <form method="POST" action="" class="new-post">
                    <div class="card card1234">
                        <div class="card-header">
                            <div class="postten">

                                <h5 >Người đăng: <?=$name11?></h5>
                                <input type="hidden" name="user_name_in" value="<?=$po[5]?>">


                            </div>
                            <div class="ngay">

                                <h6 class="postngay">Thời gian: <?=$po[0]?></h6>
                                <input type="hidden" name="time_post_in" value="<?=$po[0]?>">
                                <?php
                                if($po[6]!=0){
                                    echo "<h6>(Edited)</h6>";
                                }
                                ?>


                            </div>
                        </div>
                        <div class="card-body">
                            <h6>Nội dung</h6>
                            <div class="card card123">
                                <input type="hidden" value="<?=$po[2]?>" name="post_code_in">
                                <textarea name="post_ctn" class="abc" rows="5"><?=$po[3]?></textarea>

                            </div>
                            <a href="<?=$po[4]?>"><?=$po[4]?></a>
<!--                            <input type="file" class="form-input" name="attach_in" value="--><?//=$po[4]?><!--">-->
                            <input type="hidden" name="attach_in" value="<?=$po[4]?>">
                            <input type="hidden" name="class_code_in" value="<?=$po[1]?>">
                        </div>

                        <div class="card-footer">
                            <h5 class="text-center">Bình luận</h5>
                            <?php

                            $res4 = get_comment_by_post_code($po[2]);
                            $d4 = $res4['data'];
                            for($j = 0; $j<count($d4);$j++){
                                $u4 = $d4[$j];
                                $res_cmt = get_fname_uname($u4[4]);
                                $d_cmt = $res_cmt['data'];
                                $u_cmt = $d_cmt['fullname'];

                                ?>
                                    <div class="col-12 binhluan">
                            <div class="col-10">
                                <div>
                                    <b><?=$u_cmt?>: </b>
                                </div>
                                <div class="comment-ctn">
                                    <?=$u4[3]?>
                                </div>
                                <h6 class="postngay">Thời gian: <?=$u4[0]?></h6>
                            </div>
                            <div class="col-2">
                                <button class="btn-comment" onclick="return confirm('Bạn có chắc muốn xóa bình luận này không?')" name="delete_comment">

                                    <div class="">
                                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                        </svg>
                                    </div>

                                </button>
                            </div>
                                    </div>
                                <input type="hidden" name="delete_comment_in" value="<?=$u4[2]?>">
                            <?php

                            }

                            ?>

                            <div class="binhluan-input">
                                <input type="hidden" name="post-code-comment" value="<?=$po[2]?>">
                                <input  name="user-comment-content" id="user" type="text" class="comment" placeholder="Thêm bình luận">
                                <button class="btn-comment" name="user-comment-btn">
                                    <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-reply" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M9.502 5.013a.144.144 0 0 0-.202.134V6.3a.5.5 0 0 1-.5.5c-.667 0-2.013.005-3.3.822-.984.624-1.99 1.76-2.595 3.876C3.925 10.515 5.09 9.982 6.11 9.7a8.741 8.741 0 0 1 1.921-.306 7.403 7.403 0 0 1 .798.008h.013l.005.001h.001L8.8 9.9l.05-.498a.5.5 0 0 1 .45.498v1.153c0 .108.11.176.202.134l3.984-2.933a.494.494 0 0 1 .042-.028.147.147 0 0 0 0-.252.494.494 0 0 1-.042-.028L9.502 5.013zM8.3 10.386a7.745 7.745 0 0 0-1.923.277c-1.326.368-2.896 1.201-3.94 3.08a.5.5 0 0 1-.933-.305c.464-3.71 1.886-5.662 3.46-6.66 1.245-.79 2.527-.942 3.336-.971v-.66a1.144 1.144 0 0 1 1.767-.96l3.994 2.94a1.147 1.147 0 0 1 0 1.946l-3.994 2.94a1.144 1.144 0 0 1-1.767-.96v-.667z"/>
                                    </svg>
                                </button>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary float-left" id="capnhat" name="update_post">Cập nhật</button>
                            <button class="btn btn-danger float-right"  id="xoa" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')"name="delete_post"  >Xóa</button>
                        </div>



                    </div>

                </form>

            </div>
                <?php
                }
                ?>
        </div>

        <div class="col-3 text-center list">
            <h4>Giáo viên</h4>


            <?php

            $result5 = get_teacher($_SESSION['lop1']);
            $data5 = $result5['data'];
            for($k = 0; $k<count($data5); $k++){

                $u5 = $data5[$k];
                $uname5 = $u5[0];
                $res6 = get_fullname_teacher($uname5);
                $d6 = $res6['data'];
                $username_teacher1 = $d6['fullname'];

            ?>
            <form method="POST" action=""  >
                <div class="login-form-usernamebc">
                    <input type="hidden" name="teacher-name-delete" value="<?=$uname5?>">
                    <h6 class="teacher_name"><?=$username_teacher1?></h6>


                </div>
            </form>

            <?php
            }
            ?>
            <br><br><br>
            <h4>Sinh viên</h4>
            <form method="POST" action="">
                <button class="btn_delete_teacher" name="add_student">
                    <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-plus-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                </button>
            </form>

            <form method="POST" action="">

            <?php
            $r8 = get_student_by_code($_SESSION['lop1']);
            $d8 = $r8['data'];
            if(count($d8)!=0){

                for($n = 0; $n<count($d8); $n++){

                    $u8 = $d8[$n];
                    $uname8 = $u8[0];
                    $res8 = get_fullname_teacher($uname8);
                    $d20 = $res8['data'];
                    $username_student = $d20['fullname'];

                    ?>
                        <div class="col-12 student">
                            <div class="col-12 login-form-usernamebc">
                                <div class="col-9">
                                <input type="hidden" name="student-name-delete" value="<?=$uname8?>">
                                <h6 class="teacher_name"><?=$username_student?></h6>
                                </div>
                                <div class="col-2">
                                <button class="btn_delete_teacher" name="delete-student" onclick="return confirm('Bạn có chắc muốn xóa sinh viên <?=$username_student?>?')">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                    </svg>
                                </button>
                            </div>

                            </div>

                        </div>



                    <?php
                }
            }
            ?>
            </form>
        </div>
    </div>

</div>


</body>
</html>