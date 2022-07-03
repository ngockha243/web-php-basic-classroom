<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require '../vendor/autoload.php';

//
//    require 'PHPMailerAutoload.php';

    function open_database(){
        $servername = "127.0.0.1";
        $uname = "root";
        $pass = "";

        $db = "database";

        $conn = new mysqli($servername, $uname, $pass, $db);
        if($conn->connect_error){

            die('Connect error: '.$conn->connect_error);
        }
        return $conn;

    }

    function login($user, $pass){
        $sql = "select * from account where username = ?";
        $conn = open_database();

        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$user);

        if(!$stm->execute()){

            return array('code'=>1, 'error'=>'Can not execute command');

        }

        $result = $stm->get_result();

        if($result->num_rows == 0){
            return array('code'=>1, 'error'=>'User does not exists');
        }

        $data = $result->fetch_assoc();

        $hashed_password = $data['password'];
        if(!password_verify($pass, $hashed_password)){
            return array('code'=>2, 'error'=>'Invalid password');
        }
        else if($data['activated']==0){
            return array('code'=>3, 'error'=>'This account is not activated');
        }else{
            return array('code'=>0, 'error'=>'', 'data'=>$data);
        }
    }

    function is_email_exist($email){
        $sql = 'select * from account where email = ?';

        $conn = open_database();

        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$email);
        if(!$stm->execute()){
            die('Query error: '.$stm->error);
        }

        $result = $stm->get_result();
        if($result->num_rows > 0){
            return true;
        }
        else{
            return false;
        }
    }

    function register($user, $fullname, $birth, $email, $phone, $pass, $role){
        if(is_email_exist($email)){
            return array('code'=>1,'error'=>'Email exists.');
        }

        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $rand = random_int(0, 1000);
        $token = md5($user.'+'.$rand);

        $sql = 'insert into account (username, fullname, birthday, email, phone, password, activate_token, role) values (?,?,?,?,?,?,?,?)';

        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('ssssssss', $user, $fullname, $birth, $email, $phone, $hash, $token, $role);


        if(!$stm->execute()){
            return array('code'=>2,'error'=>'Can not execute command.');
        }

        send_activation_email($email, $token);

        return array('code'=>0,'error'=>'Create account successfull.');
    }

    function send_activation_email($email, $token){

        $mail = new PHPMailer;

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->CharSet = 'UTF-8';
            $mail->Host = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = 'nnktcbkr@gmail.com';                     // SMTP username
            $mail->Password = 'pcpteiclywtbsnqa';                               // SMTP password
            $mail->SMTPSecure = 'ssl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged

            $mail->Port = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('nnktcbkr@gmail.com', 'Admin Classroom');
            $mail->addAddress($email, 'Người nhận');     // Add a recipient
//            $mail->addAddress('ellen@example.com');               // Name is optional
//            $mail->addReplyTo('info@example.com', 'Information');
//            $mail->addCC('cc@example.com');
//            $mail->addBCC('bcc@example.com');

            // Attachments
//            $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Xác minh tài khoản của bạn';
            $mail->Body = "Click <a href='http://localhost:8888/classroom/active.php?email=$email&token=$token'>vào đây</a> để xác minh tài khoản của bạn.";
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return true;
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    function send_reset_email($email, $token){

        $mail = new PHPMailer;

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->CharSet = 'UTF-8';
            $mail->Host = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = 'nnktcbkr@gmail.com';                     // SMTP username
            $mail->Password = 'pcpteiclywtbsnqa';                               // SMTP password
            $mail->SMTPSecure = 'ssl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged

            $mail->Port = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('nnktcbkr@gmail.com', 'Admin Classroom');
            $mail->addAddress($email, 'Người nhận');     // Add a recipient
    //            $mail->addAddress('ellen@example.com');               // Name is optional
    //            $mail->addReplyTo('info@example.com', 'Information');
    //            $mail->addCC('cc@example.com');
    //            $mail->addBCC('bcc@example.com');

            // Attachments
    //            $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Khôi phục mật khẩu email của bạn';
            $mail->Body = "Click <a href='http://localhost:8888/classroom/reset_password.php?email=$email&token=$token'>vào đây</a> để khôi phục mật khẩu của bạn.";
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return true;
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    function active_account($email, $token){
        $sql = 'select username from account where email =? and activate_token =? and activated = 0';

        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('ss',$email, $token);

        if(!$stm->execute()){
            return array('code'=>1,'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        if($result->num_rows == 0){
            return array('code'=>2,'error'=>'Email address or token not found');
        }

        $sql = "update account set activated = 1, activate_token = '' where email = ?";
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$email);
        if(!$stm->execute()){
            return array('code'=>1,'error'=>'Can not execute command');
        }
        return array('code'=>0,'message'=>'Account activated');
    }

    function reset_password($email){
        if(!is_email_exist($email)){
            return array('code'=>1, 'error'=>'Email does not exists');
        }
        $token = md5($email.'+'.random_int(1000, 2000));
        $sql = 'update reset_token set token = ? where email = ?';

        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('ss',$token, $email);

        if(!$stm->execute()){
            return array('code'=>2, 'error'=>'Can not execute command');
        }
        if($stm->affected_rows == 0){

            $exp = time() + 3600*1;

            $sql = 'insert into reset_token values(?,?,?)';
            $stm = $conn->prepare($sql);
            $stm->bind_param('ssi',$email, $token, $exp);

            if(!$stm->execute()){
                return array('code'=>1, 'error'=>'Can not execute command');
            }
        }
        $success = send_reset_email($email, $token);

        return array('code'=>0, 'success'=>$success);

    }

    function change_password($email, $pass){
        if(!is_email_exist($email)){
            return array('code'=>1, 'error'=>'Email does not exists');
        }
        $sql = 'update account set password = ? where email = ?';

        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('ss',$hash, $email);
        if(!$stm->execute()){
            return array('code'=>2, 'error'=>'Can not execute command');
        }
        if($stm->affected_rows == 0){
            if(!$stm->execute()){
                return array('code'=>1, 'error'=>'Can not execute command');
            }
        }
        return array('code'=>0, 'success'=>'Đổi mật khẩu thành công');
    }

    function get_all_user($user){
        $sql = 'select fullname, username, birthday, email, phone, role from account where username != ?';

        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s', $user);
        if(!$stm->execute()){
            return array('code'=>2, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        return $result->fetch_all();
    }

    function get_all_class(){
        $sql = 'select name, monhoc, phong, avatar, code from class';

        $conn = open_database();
        $stm = $conn->prepare($sql);
        if(!$stm->execute()){
            return array('code'=>2, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        return $result->fetch_all();
    }

    function get_class_by_teacher($teacher){
        $sql = 'select name, monhoc, phong, avatar, code from class where code in (select code_class from teacher_class where username_teacher = ?)';
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$teacher);
        if(!$stm->execute()){
            return array('code'=>2, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        return $result->fetch_all();
    }

    function get_class_by_student($student){
        $sql = 'select name, monhoc, phong, avatar, code from class where code in (select code from student_class where username = ? and is_join = ?)';
        $join = '1';
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('ss',$student,$join);
        if(!$stm->execute()){
            return array('code'=>2, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        return $result->fetch_all();
    }

    function get_class_by_user($user){
        $sql = 'select name, monhoc, phong, avatar, code from class where code in (select code_class from teacher_class where username_teacher = ?)';

        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$user);
        if(!$stm->execute()){
            return array('code'=>2, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        return $result->fetch_all();
    }

    function create_code(){
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double)microtime()*1000000);
        $i = 0;
        $pass = '' ;

        while ($i <= 7) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;

    }

    function create_new_class($name, $monhoc, $phong, $avatar, $code){

        $sql = 'insert into class values (?, ?, ?, ?, ?)';

        $conn = open_database();

        $stm = $conn->prepare($sql);
        $stm->bind_param('sssss',$name, $monhoc, $phong, $avatar, $code);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        return array('code'=>0, 'error'=>'Thêm lớp học thành công');

    }

    function create_new_class_teacher($user, $code){
        $sql = 'insert into teacher_class values (?, ?)';

        $conn = open_database();

        $stm = $conn->prepare($sql);
        $stm->bind_param('ss',$user, $code);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        return array('code'=>0, 'error'=>'Thêm lớp học thành công');
    }

    function update_role($username, $role){
        $sql = 'update account set role = ? where username = ?';

        $conn = open_database();

        $stm = $conn->prepare($sql);
        $stm->bind_param('ss',$role, $username);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        return array('code'=>0, 'error'=>'Thay đổi quyền thành công');
    }

    function join_class($user, $code){
        $sql = 'insert into student_class values (?, ?)';
        $conn = open_database();
        $join = '0';

        $stm = $conn->prepare($sql);
        $stm->bind_param('sss',$user, $code, $join);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        $result = send_join_student($user, $code);
        if($result['code'] == 1){
            return array('code'=>2, 'error'=>'Can not join class. Please try again later');
        }
        return array('code'=>0, 'error'=>'Vui lòng chờ giáo viên xác nhận');
    }

    function get_class_by_code($code){
        $sql = 'select monhoc from class where code = ?';
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$code);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        $data = $result->fetch_assoc();
        return array('code'=>0, 'error'=>'Thành công','data'=>$data);
    }

    function get_classinfo_by_code($code){
        $sql = 'select * from class where code = ?';
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$code);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        $data = $result->fetch_assoc();
        return array('code'=>0, 'error'=>'Thành công','data'=>$data);
    }

    function send_join_student($user, $code){
        $result = get_teacher_email($code);
        $data = $result['data'];
        $teacher = $data['email'];

        $result1 = get_class_by_code($code);
        $data1 = $result1['data'];
        $subject = $data1['monhoc'];

        $mail = new PHPMailer;

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->CharSet = 'UTF-8';
            $mail->Host = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = 'nnktcbkr@gmail.com';                     // SMTP username
            $mail->Password = 'pcpteiclywtbsnqa';                               // SMTP password
            $mail->SMTPSecure = 'ssl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged

            $mail->Port = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('nnktcbkr@gmail.com', 'Admin Classroom');
            $mail->addAddress($teacher, 'Người nhận');     // Add a recipient
//            $mail->addAddress('ellen@example.com');               // Name is optional
//            $mail->addReplyTo('info@example.com', 'Information');
//            $mail->addCC('cc@example.com');
//            $mail->addBCC('bcc@example.com');

            // Attachments
//            $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Có sinh viên muốn tham gia vào lớp học của bạn';
            $mail->Body = "Có sinh viên $user muốn tham gia vào lớp $subject của bạn";
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return true;
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }

    function send_email_not($user, $subject, $content){
        $r = get_em_by_uname($user);
        $d = $r['data'];
        $em = $d['email'];


        $mail = new PHPMailer;

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->CharSet = 'UTF-8';
            $mail->Host = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = 'nnktcbkr@gmail.com';                     // SMTP username
            $mail->Password = 'pcpteiclywtbsnqa';                               // SMTP password
            $mail->SMTPSecure = 'ssl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged

            $mail->Port = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('nnktcbkr@gmail.com', 'Admin Classroom');
            $mail->addAddress($em, 'Người nhận');     // Add a recipient
    //            $mail->addAddress('ellen@example.com');               // Name is optional
    //            $mail->addReplyTo('info@example.com', 'Information');
    //            $mail->addCC('cc@example.com');
    //            $mail->addBCC('bcc@example.com');

            // Attachments
    //            $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Thông báo từ classroom';
            $mail->Body = "Bạn có thông báo mới từ môn $subject: $content";
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return true;
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }

    function send_invite_student($user, $code){
        $result = get_teacher_email($code);
        $data = $result['data'];
        $teacher = $data['email'];

        $result1 = get_class_by_code($code);
        $data1 = $result1['data'];
        $subject = $data1['monhoc'];

        $mail = new PHPMailer;

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->CharSet = 'UTF-8';
            $mail->Host = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = 'nnktcbkr@gmail.com';                     // SMTP username
            $mail->Password = 'pcpteiclywtbsnqa';                               // SMTP password
            $mail->SMTPSecure = 'ssl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged

            $mail->Port = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('nnktcbkr@gmail.com', 'Admin Classroom');
            $mail->addAddress($user, 'Người nhận');     // Add a recipient
    //            $mail->addAddress('ellen@example.com');               // Name is optional
    //            $mail->addReplyTo('info@example.com', 'Information');
    //            $mail->addCC('cc@example.com');
    //            $mail->addBCC('bcc@example.com');

            // Attachments
    //            $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = "Lời mời tham gia lớp học";
            $mail->Body = "Bạn có lời mời tham gia lớp $subject từ $teacher";
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return true;
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }

    function get_teacher($code){
        $sql = 'select username_teacher from teacher_class where code_class = ?';

        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$code);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        $data = $result->fetch_all();
        return array('code'=>0, 'error'=>'Thành công','data'=>$data);
    }

    function get_student_by_code($code){
        $sql = 'select username from student_class where code = ? and is_join = ?';
        $join = '1';


        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('ss', $code, $join);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        $data = $result->fetch_all();
        return array('code'=>0,'data'=>$data);
    }

    function get_fullname_teacher($username){
        $sql = 'select fullname from account where username = ?';

        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$username);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        $data = $result->fetch_assoc();
        return array('code'=>0, 'error'=>'Thành công','data'=>$data);
    }

    function delete_teacher_from_class($username){
        $sql = 'delete from teacher_class where username_teacher = ?';
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$username);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        return array('code'=>0, 'error'=>'Xóa giáo viên khỏi lớp thành công');
    }

    function delete_student_from_class($username){
        $sql = 'delete from student_class where username = ?';
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$username);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        return array('code'=>0, 'error'=>'Xóa sinh viên khỏi lớp thành công');
    }

    function get_teacher_email($code){
        $res = check_code_exist($code);

        if($res['code'] == 2){
            return array('code'=>2, 'error'=>'Lớp học không tồn tại');
        }
        else if($res['code'] == 1){
            return array('code'=>1, 'error'=>'Can not execute command');
        }

        $res1 = get_teacher($code);
        $data1 = $res1['data'];
        $t = $data1[0];
        $teacher = $t[0];

        $sql = 'select email from account where username = ?';
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$teacher);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        $data = $result->fetch_assoc();
        return array('code'=>0, 'error'=>'Thành công','data'=>$data);
    }

    function get_code($teacher){
        $sql = "select username, code from student_class where is_join = '0' and code in (select code_class from teacher_class where username_teacher = ?)";
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$teacher);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        $data = $result->fetch_all();
        return array('code'=>0, 'error'=>'Thành công','data'=>$data);
    }

    function get_code_by_em($em){
        $sql = "select class_code, username from invite_student where username = ? ";


        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$em);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        $data = $result->fetch_all();
        return array('code'=>0, 'error'=>'Thành công','data'=>$data);
    }

    function get_teacher_name_by_code($code){
        $sql = 'select fullname from account where username = ?';

        $res = get_teacher($code);
        $data= $res['data'];
        $u = $data[0];
        $c = $u[0];

        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$c);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        $data = $result->fetch_assoc();
        return array('code'=>0, 'error'=>'Thành công','data'=>$data);
    }

    function get_em_by_uname($uname){
        $sql = 'select email from account where username = ?';
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$uname);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        $data = $result->fetch_assoc();
        return array('code'=>0, 'error'=>'Thành công','data'=>$data);
    }

    function get_in_class($user, $code){

        $sql = 'insert into student_class values (?, ?, ?)';
        $join = '0';
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('sss',$user, $code, $join);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        return array('code'=>0, 'error'=>'Vui lòng chờ giáo viên xác nhận');
    }

    function check_email_exist($email){
        $sql = 'select * from account where email = ?';
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$email);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        $re = $stm->get_result();
        $re1 = $re->fetch_assoc();
        if(empty($re1)){
            return array('code'=>2, 'error'=>'Email không tồn tại');
        }
        return array('code'=>0, 'error'=>'Vui lòng chờ sinh viên xác nhận','data'=>$re1);
    }

    function check_code_exist($code){
        $sql = 'select * from class where code = ?';
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$code);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        $re = $stm->get_result();
        $re1 = $re->fetch_assoc();
        if(empty($re1)){
            return array('code'=>2, 'error'=>'Lớp học không tồn tại');
        }
        return array('code'=>0, 'error'=>'Vui lòng chờ giáo viên xác nhận','data'=>$re1);
    }

    function get_fname_uname($username){
        $sql = 'select fullname from account where username = ?';
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$username);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        $data = $result->fetch_assoc();
        return array('code'=>0, 'data'=>$data);
    }

    function get_subj_code($code){
        $sql = 'select monhoc from class where code = ?';
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$code);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        $data = $result->fetch_assoc();
        return array('code'=>0, 'data'=>$data);
    }

    function add_student_to_class($user, $code){
        $sql = 'update student_class set is_join = ? where username = ? and code = ?';
        $join = '1';

        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('sss', $join, $user, $code);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        return array('code'=>0, 'error'=>'Thêm sinh viên vào lớp học thành công');
    }

    function add_student_to_class_join($user, $code){
        $sql = 'insert into student_class values(?,?,?)';
        $join = '1';

        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('sss', $user, $code, $join);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        return array('code'=>0, 'error'=>'Tham gia lớp học thành công');
    }

    function delete_request($user, $code){
        $sql = 'delete from student_class where username = ? and code = ?';

        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('ss',$user, $code);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        return array('code'=>0, 'error'=>'Xóa yêu cầu thành công');
    }

    function delete_invite($code, $email){
        $sql = 'delete from invite_student where class_code = ? and username = ?';

        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('ss',$code, $email);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        return array('code'=>0, 'error'=>'Xóa lời mời thành công');
    }

    function delete_class_by_code($code){
        $sql = 'delete from class where code = ?';
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$code);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        return array('code'=>0, 'error'=>'Xóa lớp học thành công');
    }

    function edit_class_by_code($name, $monhoc, $room, $code){
        $sql = 'update class set name = ?, monhoc = ?, phong = ? where code = ?';
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('ssss',$name, $monhoc, $room, $code);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        return array('code'=>0, 'error'=>'Cập nhật thông tin lớp học thành công');
    }

    function search_class($k){
        $sql = 'select * from class where name like ? or monhoc like ?';
        $key = '%'."$k".'%';
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('ss',$key,$key);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
//        if($stm->num_rows ==0){
//            return array('code'=>2, 'error'=>'Không tìm thấy kết quả');
//        }
        $result = $stm->get_result();
        $data = $result->fetch_all();
        return array('code'=>0, 'error'=>'Tìm kiếm thành công','data'=>$data);
    }

    function search_class_by_teacher($k, $teacher){
        $sql = 'select * from class where (name like ? or monhoc like ?) and code in (select code_class from teacher_class where username_teacher = ?)';
        $key = '%'."$k".'%';
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('sss',$key,$key,$teacher);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
    //        if($stm->num_rows ==0){
    //            return array('code'=>2, 'error'=>'Không tìm thấy kết quả');
    //        }
        $result = $stm->get_result();
        $data = $result->fetch_all();
        return array('code'=>0, 'error'=>'Tìm kiếm thành công','data'=>$data);
    }

    function search_class_by_student($k, $student){
        $sql = 'select * from class where (name like ? or monhoc like ?) and code in (select code from student_class where username = ? and is_join = ?)';
        $key = '%'."$k".'%';
        $join = '1';
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('ssss',$key,$key,$student, $join);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        //        if($stm->num_rows ==0){
        //            return array('code'=>2, 'error'=>'Không tìm thấy kết quả');
        //        }
        $result = $stm->get_result();
        $data = $result->fetch_all();
        return array('code'=>0, 'error'=>'Tìm kiếm thành công','data'=>$data);
    }

    function post_bai($class_code, $content, $file, $username){
        $sql = 'insert into post_info values(?,?,?,?,?,?,?)';

        $post_code = create_code();
        date_default_timezone_set('Asia/ho_chi_minh');
        $date_time = date("Y-m-d H:i:s");
        $edit = '0';

        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('sssssss', $date_time,$class_code, $post_code, $content, $file, $username,$edit);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        return array('code'=>0, 'error'=>'Thêm bài đăng thành công');
    }

    function post_comment($post_code, $content, $username){
        $sql = 'insert into comment_info values(?,?,?,?,?)';

        $comment_code = create_code();
        date_default_timezone_set('Asia/ho_chi_minh');
        $date_time = date("Y-m-d H:i:s");

        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('sssss', $date_time,$post_code, $comment_code, $content, $username);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        return array('code'=>0);
    }

    function update_post($post, $content){
        $sql = 'update post_info set content = ?, edit = ? where post_code = ?';
        $edit = '1';

        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('sss',$content,$edit,$post);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        return array('code'=>0,'error'=>'Cập nhật bài đăng thành công');

    }

    function delete_post($post){
        $sql = 'delete from post_info where post_code = ?';
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$post);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        return array('code'=>0,'error'=>'Xóa bài đăng thành công');
    }

    function get_post_by_class_code($code){
        $sql = 'select * from post_info where class_code = ? ORDER BY date_post DESC';


        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s', $code);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        $data = $result->fetch_all();
        return array('code'=>0,'data'=>$data);
    }

    function get_comment_by_post_code($code){
        $sql = 'select * from comment_info where post_code = ? ORDER BY comment_date ASC';


        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s', $code);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        $data = $result->fetch_all();
        return array('code'=>0,'data'=>$data);
    }

    function invite_student($code, $user){
        $sql = 'insert into invite_student values(?,?)';

        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('ss',$code, $user);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Bạn đã gửi lời mời');
        }
        return array('code'=>0,'error'=>'Vui lòng chờ sinh viên xác nhận');
    }

    function delete_comment($code){
        $sql = 'delete from comment_info where comment_code = ?';

        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$code);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        return array('code'=>0, 'error'=>'Xóa bình luận thành công');
    }

    function get_uname_by_email($email){
        $sql = 'select username from account where email = ?';
        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s',$email);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        $data = $result->fetch_assoc();
        return array('code'=>0, 'error'=>'Thành công','data'=>$data);
    }

    function is_email_in_class($email, $code){
        $sql = 'select * from student_class where username = ? and code = ? and is_join = ?';
        $join = '1';

        $conn = open_database();
        $stm = $conn->prepare($sql);
        $stm->bind_param('sss',$u,$code, $join);
        if(!$stm->execute()){
            return array('code'=>1, 'error'=>'Can not execute command');
        }
        $result = $stm->get_result();
        $data = $result->fetch_assoc();
        if(empty($data)){
            return array('code'=>0, 'error'=>'Thành công');
        }
        return array('code'=>1, 'error'=>'Sinh viên đã tham gia lớp học');
    }


?>
