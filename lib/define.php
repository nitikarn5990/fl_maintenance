<?php

error_reporting(0);



set_time_limit(3600);



error_reporting(E_ALL ^ E_NOTICE); // Disable Error Notice
date_default_timezone_set('Asia/Bangkok');

define('ADDRESS', 'index.php?controllers=');

define("DATE", date('Y-m-d'));
define("DATE_TIME_FILE", date('YmdHis'));


define("DATE_TIME", date('Y-m-d H:i:s'));

define('DIR_ABS', $_SERVER["DOCUMENT_ROOT"] . '/');

$AlertSuccess = '<div class="row">
            <div class="col-md-12">
                <div class="da-message success">เพิ่ม แก้ไข ข้อมูลสำเร็จ</div>
            </div>
        </div>';


function AlertError($msg = "เกิดข้อผิดพลาด ลองใหม่อีกครั้ง"){
    $AlertError = '<div class="row">
            <div class="col-md-12">
                <div class="da-message error">'.$msg.'</div>
            </div>
        </div>';
    
    return $AlertError;
}

define("AlertSuccess", $AlertSuccess );
//define("AlertError", $AlertError );
define('REDIRECT_URI', 'http://' . $_SERVER['SERVER_NAME'] .$_SERVER['REQUEST_URI']);
