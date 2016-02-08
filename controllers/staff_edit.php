
<?php
//เช็คการส่งค่า POST ของฟอร์ม
//var_dump(strpos('staff', 'staff'));

if ($_POST['btn_submit'] == 'บันทึกข้อมูล') { //เช็คว่ามีการกดปุ่ม บันทึกข้อมูล
    //ทำการอัพเดรต ส่วนแรกคือชื่อฟิลล์ในฐานข้อมูล ส่วนที่สองคือ POST ที่มาจากฟอร์ม (จับคู่ให้ตรงกัน)
    if ( $_SESSION['group'] == 'admin') {
        $data = array(
            "first_name" => $_POST['first_name'],
            "last_name" => $_POST['last_name'],
            "position" => $_POST['position'],
            "address" => $_POST['address'],
            "email" => $_POST['email'],
            "tel" => $_POST["tel"],
            "password" => $_POST['password'],
            "updated_at" => DATE_TIME, //วันที่แก้ไข
        );
    } else {
        $data = array(
            "first_name" => $_POST['first_name'],
            "last_name" => $_POST['last_name'],
            "position" => $_POST['position'],
            "address" => $_POST['address'],
            "email" => $_POST['email'],
            "tel" => $_POST["tel"],
        
            "updated_at" => DATE_TIME, //วันที่แก้ไข
        );
    }


// update ข้อมูลลงในตาราง tb_staff โดยฃื่อฟิลด์ และค่าตามตัวแปร array ชื่อ $data

    $user_id = $_GET['id'] == '' ? $_SESSION['user_id'] : $_GET['id'];
    if (update("tb_staff", $data, "id = " . $user_id)) { //ชื่อตาราง,ข้อมูลจากตัวแปร $data,id ที่จะทำการแก้ไข
        //  echo AlertSuccess;
        SetAlert('เพิ่ม แก้ไข ข้อมูลสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
        //   header('location:' . ADDRESS . 'staff_edit&id=' . $_POST['id'] . $_POST['action'] != '' ? '&action=repassword':''); //กลับยังหน้าแสดงข้อมูล staff ทั้งหมด
        //   die();
    } else {
        SetAlert('เกิดข้อผิดพลาดไม่สามารถแก้ไขข้อมูลได้'); //แสดงข้อมูลแจ้งเตือนถ้าไม่สำเร็จ
        header('location:' . ADDRESS . 'staff_edit');
        die();
    }
}



//เช็คค่า id ต้องมีค่า และ ไม่เป็นค่าว่าง และ ต้องเป็นตัวเลขเท่านั้น
if (isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])) {

    //ดึงข้อมูลตาม  $_GET['id'] ที่รับมา
    $sql = "SELECT * FROM tb_staff WHERE id = " . $_GET['id'];
    $result = mysql_query($sql);
    $num_row = mysql_num_rows($result);
    if ($num_row == 1) {
        $row = mysql_fetch_assoc($result);
    }
} else {
    if ($_SESSION['group'] == 'admin') {

        $sql = "SELECT * FROM tb_staff WHERE id = " . $_SESSION['user_id'];
        $result = mysql_query($sql);
        $num_row = mysql_num_rows($result);
        if ($num_row == 1) {
            $row = mysql_fetch_assoc($result);
        }
    }
}
?>
<?php
// แสดงการแจ้งเตือน

Alert(GetAlert('error'));

Alert(GetAlert('success'), 'success');
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">

            <?php
            if ($_SESSION['group'] == 'admin') {

                if ($_GET['action'] == 'repassword') {
                    echo "เปลี่ยนรหัสผ่าน";
                    $uri_action = '&action=repassword';
                } else {
                    echo "แก้ไขข้อมูลเจ้าหน้าที่";
                }
            } else {
                echo "ข้อมูลส่วนตัว";
            }
            ?>

        </h1>

    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <?php if ($_GET['id'] == '') { ?>
            <p id="breadcrumb">

                เปลี่ยนรหัสผ่าน
            </p>
        <?php } else { ?>
            <p id="breadcrumb">
                <?php if ($_SESSION['group'] == 'staff') { ?>
                    แก้ไขข้อมูลเจ้าหน้าที่
                <?php } else { ?>
                    <a href="<?= ADDRESS ?>staff">ข้อมูลพนักงานทั้งหมด</a>
                    แก้ไขข้อมูลเจ้าหน้าที่
                <?php } ?>

            </p>
        <?php } ?>

    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">

                <?php
                if ($_SESSION['group'] == 'admin') {
                    if ($_GET['action'] == 'repassword') {
                        echo "<i class=icol-key></i> ";
                        echo "เปลี่ยนรหัสผ่าน";
                    } else {
                        echo "<i class=icol-add></i> ";
                        echo "แก้ไขข้อมูลเจ้าหน้าที่";
                    }
                } else {
                    echo "<i class='fa fa-pencil-square-o'></i> ";
                    echo "แก้ไขข้อมูลส่วนตัว";
                }
                ?>

            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <form role="form" action="<?= ADDRESS ?>staff_edit&id=<?= $_GET['id'] ?><?= isset($uri_action) ? $uri_action : '' ?>" method="POST">
                            <input type="hidden" name="action" value="<?= isset($_GET['action']) ? $_GET['action'] : '' ?>">     
                            <input type="hidden" name="id" value="<?= isset($_GET['id']) ? $_GET['id'] : '' ?>">    
                            <?php if ($_GET['id'] != '') { ?>    
                                <div class="row da-form-row">
                                    <label class="col-md-2">ชื่อ <span class="required">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control input-sm" name="first_name" type="text" value="<?= isset($row['first_name']) ? $row['first_name'] : '' ?>">
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                                <div class="row  da-form-row">
                                    <label class="col-md-2">สกุล <span class="required">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control input-sm" name="last_name" type="text" value="<?= isset($row['last_name']) ? $row['last_name'] : '' ?>">
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                                <div class="row  da-form-row">
                                    <label class="col-md-2">ตำแหน่ง <span class="required">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control input-sm" type="text" name="position" value="<?= isset($row['position']) ? $row['position'] : '' ?>">
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                                <div class="row  da-form-row">
                                    <label class="col-md-2">ที่อยู่ <span class="required">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control input-sm" type="text" name="address" value="<?= isset($row['address']) ? $row['address'] : '' ?>">
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                                <div class="row  da-form-row">
                                    <label class="col-md-2">Email</label>
                                    <div class="col-md-10">
                                        <input class="form-control input-sm" type="text" name="email" value="<?= isset($row['email']) ? $row['email'] : '' ?>">
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                                <div class="row  da-form-row">
                                    <label class="col-md-2">เบอร์ติดต่อ <span class="required">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control input-sm" type="text" name="tel" value="<?= isset($row['tel']) ? $row['tel'] : '' ?>">
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($_SESSION['group'] == 'admin') { ?>          

                                <div class="row">
                                    <div class="panel-toolbar" style="padding: 5px 15px 0 15px;">
                                        <label class=""><i class="fa fa-key"></i><strong>  สำหรับเข้าใช้งาน</strong></label>
                                    </div>
                                </div>

                                <div class="row  da-form-row">
                                    <label class="col-md-2">Username <span class="required">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control input-sm" type="text" name="username" value="<?= isset($row['username']) ? $row['username'] : '' ?>" disabled="">
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                                <div class="row  da-form-row">
                                    <label class="col-md-2">Password <span class="required">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control input-sm" type="text" name="password" value="<?= isset($row['password']) ? $row['password'] : '' ?>">
                                        <p class="help-block">ไม่ต่ำกว่า 6 ตัวอักษร</p>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row ">
                                <div class="">
                                    <div class="btn-row">
                                        <button type="submit" name="btn_submit" value="บันทึกข้อมูล" class="btn btn-sm btn-success">บันทึกข้อมูล</button>
                                        <button type="reset" class="btn btn-sm btn-danger">ยกเลิก</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                <!-- /.col-lg-6 (nested) -->

                <!-- /.col-lg-6 (nested) -->
            </div>
            <!-- /.row (nested) -->
        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
</div>
<!-- /.col-lg-12 -->

<script>
    $('form').validate({
        rules: {
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            position: {
                required: true
            },
            address: {
                required: true
            },
            tel: {
                required: true,
                number: true

            },
            username: {
                required: true
            },
            password: {
                required: true,
                minlength: 6
            },
            email: {
                email: true
            }
        },
        messages: {
        },
        highlight: function (element) {
            $(element).closest('.da-form-row').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.da-form-row').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function (error, element) {
            if (element.parent('.form-control').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

</script>

