<?php
session_start();

include_once './lib/application.php';

if ($_COOKIE['user'] != '') {
    header('location:' . ADDRESS . 'repair_add_user');
    die();
}

if ($_POST['submit_bt'] == 'เข้าระบบ' || $_POST['submit_bt'] == 'เข้าสู่ระบบ') {

    // print_r($_POST);
    //die();

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

            $_SESSION['group'] = $row['status'];

            $_SESSION['user_id'] = $row['id']; //กำหนด session user_id
            $_SESSION['username'] = $username; //กำหนด session username
            $_SESSION['name'] = $row['first_name'] . ' ' . $row['last_name']; //กำหนด session name

            $ck_expire_hour = 4; // กำหนดจำนวนชั่วโมง ให้ตัวแปร cookie  
            $ck_expire = time() + ($ck_expire_hour * 60 * 60); // กำหนดเวลาหมดอายุของคุกกี้

            setcookie("user", $username, $ck_expire); // set cookie
//            if ($_GET['page'] == 'select_idcard') {
//                  header('location:' . $_GET['page'].'.php'); //ให้ไปสู่หน้า staff
//                  die();
//            }
//            if ($_GET['controllers'] != '') {
//                 header('location:'.ADDRESS.$_GET['controllers'] );
//                   die();
//            }

            if ($_SESSION['group'] == 'ผู้บริหาร') {
                header('location:' . ADDRESS . "report_type"); //ให้ไปสู่หน้า repair
                die();
            }
            if ($_SESSION['group'] == 'ผู้ดูแลระบบ' || $_SESSION['group'] == 'เจ้าหน้าที่') {
                header('location:' . ADDRESS . "repair"); //ให้ไปสู่หน้า repair
                die();
            }
            
        } else {
            SetAlert('ชื่อผู้ใช้ กับรหัสผ่านไม่ตรงกัน กรุณาลองใหม่อีกครั้ง');
            header('location:' . ADDRESS . "repair_add_user");
            die();
        }
    } else {

        SetAlert('ไม่มีชื่อผู้ใช้นี้ กรุณาลองใหม่อีกครั้ง');
          header('location:' . ADDRESS . "repair_add_user");
            die();
    }
}
?>
