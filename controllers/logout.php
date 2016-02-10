<?php
session_start();
setcookie("user");  
session_destroy();


//header("Location: login.php?controllers=".$_GET['page']); //ส่งไปยังหน้าที่ตอ้งการ  
header("Location: login.php");

?>