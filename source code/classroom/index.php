<?php

    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
    }
    if($_SESSION['role']=='1'){
        header('Location: admin_home.php');
    }
    else if($_SESSION['role']=='3'){
        header('Location: home.php');
    }
    else if($_SESSION['role']=='2'){
        header('Location: teacher_home.php');
    }

    require_once ('db.php');

?>