<?php

    require_once ('db.php');

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    />
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
      integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
      crossorigin="anonymous"
    />
      <link rel="stylesheet" href="../style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </head>
  <body>

    <?php
        $error = '';
        $message ='';
        if(isset($_GET['email']) && isset($_GET['token'])){
            $email = $_GET['email'];
            $token = $_GET['token'];

            if(filter_var($email, FILTER_VALIDATE_EMAIL)===false){
                $error = 'Invalid email address';
            }
            elseif(strlen($token)!=32){
                $error = 'Invalid token format';
            }
            else{
                $result = active_account($email, $token);
                if($result['code'] == 0){
                    $message = 'Your account has been activated. Login now';
                }
                else{
                    $error = $result['error'];
                }
            }
        }
        else{
            $error = 'Invalid activation url';
        }

    ?>

    <?php
        if(!empty($error)){
            ?>
                    <div class="row ">

                        <div class="col-sm-4 mt-5 mx-auto p-7 text-center new-join-class ">

                                <h2>Account Activation</h2>
                                <p class="text-danger"><?=$error?>.</p>
                                <p>Click <a href="login.php">here</a> to login.</p>
                                <a class="btn btn-primary px-5" href="login.php">Login</a>

                        </div>
                    </div>
            <?php

        }else{
            ?>

                    <div class="row">
                        <div class="col-md-4 mt-5 mx-auto p-7 text-center new-join-class ">
                            <h2>Account Activation</h2>
                            <p class="text-success">Congratulations! Your account has been activated.</p>
                            <p>Click <a href="login.php">here</a> to login and manage your account information.</p>
                            <a class="btn btn-primary px-5" href="login.php">Login</a>
                        </div>
                    </div>

            <?php
        }

    ?>





  </body>
</html>
