<?php if ($_SESSION['group'] == '' || $_SESSION['group'] == 'ผู้บริหาร') { ?>
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 10px;">
            <p>&nbsp;</p>
            <img src="./dist/images/404.png" class="img-responsive" style="margin: auto;">
        </div>
    </div>
<?php

}else{
//จำกัดสิทธ์การเข้าถึงหน้านี้เฉพาะ admin เท่านั้น
if ($_SESSION['group'] != 'ผู้ดูแลระบบ') {
    echo "<h1>ไม่พบหน้าที่คุณต้องการ</h1>";
    die();
}

if ($_POST['btn_submit'] == 'บันทึกข้อมูล') { //เช็คว่ามีการกดปุ่ม บันทึกข้อมูล
    $sql_check_username = "SELECT * FROM tb_staff WHERE username = '" . $_POST['username'] . "'";

    $result = mysql_query($sql_check_username);
    $num_row = mysql_num_rows($result); //หาว่ามีกี่แถว
    if ($num_row > 0) { //ถ้ามีมากกว่า 0 แสดงว่า username นี้มีการใช้ไปแล้ว
        SetAlert('username นี้มีการใช้งานแล้ว กรุณาเปลี่ยนใหม่'); //แสดงข้อมูลแจ้งเตือน
    } else {
        //ถ้าว่างทำส่วนนี้ คือ เพิม่ลงฐานข้อมูล

        $data = array(
            "first_name" => $_POST['first_name'],
            "last_name" => $_POST['last_name'],
            "address" => $_POST['address'],
            "email" => $_POST['email'],
            "tel" => $_POST['tel'],
            "status" => $_POST['status'],
            "username" => $_POST['username'],
            "password" => $_POST['password'],
            "created_at" => DATE_TIME, //วันที่บันทึก
            "updated_at" => DATE_TIME, //วันที่แก้ไข
        );

// insert ข้อมูลลงในตาราง tb_staff โดยฃื่อฟิลด์ และค่าตามตัวแปร array ชื่อ $data
        if (insert("tb_staff", $data)) { // บันทึกข้อมูลลงตาราง tb_staff 
            //  echo AlertSuccess;
            SetAlert('เพิ่ม แก้ไข ข้อมูลสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
            header('location:' . ADDRESS . 'staff');
            die();
        } else {
            SetAlert('เกิดข้อผิดพลาดไม่สามารถเพิ่มข้อมูลได้'); //แสดงข้อมูลแจ้งเตือนถ้าไม่สำเร็จ
            header('location:' . ADDRESS . 'staff_add');
            die();
        }
    }
}


$sql_status = "SELECT * FROM tb_staff WHERE id = " . $_SESSION['user_id'];
$result = mysql_query($sql_status);
$num_row = mysql_num_rows($result);
if ($num_row == 1) {
    $row = mysql_fetch_assoc($result);
}
?>
<?php
// แสดงการแจ้งเตือน

Alert(GetAlert('error'));

Alert(GetAlert('success'), 'success');
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">เพิ่มข้อมูลเจ้าหน้าที่</h1>

    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <p id="breadcrumb">
            <a href="<?= ADDRESS ?>staff">ข้อมูลพนักงานทั้งหมด</a>
            เพิ่มข้อมูลเจ้าหน้าที่
        </p>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="icol-add"></i> เพิ่มข้อมูลเจ้าหน้าที่
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <form role="form" action="<?= ADDRESS ?>staff_add" method="POST">
                            <div class="row da-form-row">
                                <label class="col-md-2">ชื่อ <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" name="first_name" type="text" value="<?= isset($_POST['first_name']) ? $_POST['first_name'] : '' ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row  da-form-row">
                                <label class="col-md-2">สกุล <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" name="last_name" type="text" value="<?= isset($_POST['last_name']) ? $_POST['last_name'] : '' ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="row  da-form-row">
                                <label class="col-md-2">ที่อยู่ <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" type="text" name="address" value="<?= isset($_POST['address']) ? $_POST['address'] : '' ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row  da-form-row">
                                <label class="col-md-2">Email</label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" type="text" name="email" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row  da-form-row">
                                <label class="col-md-2">เบอร์ติดต่อ <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" type="text" name="tel" value="<?= isset($_POST['tel']) ? $_POST['tel'] : '' ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row  da-form-row">
                                <label class="col-md-2">สถานะ <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <select name="status" class="form-control">
                                        <option value="เจ้าหน้าที่">--- เลือกสถานะ ---</option>
                                        <option value="เจ้าหน้าที่"> เจ้าหน้าที่</option>
                                        <option value="ผู้บริหาร"> ผู้บริหาร</option>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="panel-toolbar" style="padding: 5px 15px 0 15px;">
                                    <label class=""><i class="fa fa-key"></i><strong>  สำหรับเข้าใช้งาน</strong></label>
                                </div>
                            </div>
                            <div class="row  da-form-row">
                                <label class="col-md-2">Username <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" type="text" name="username" value="">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row  da-form-row">
                                <label class="col-md-2">Password <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" type="text" name="password" value="">
                                    <p class="help-block">ไม่ต่ำกว่า 6 ตัวอักษร</p>
                                </div>
                            </div>
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
                number: true,
                rangelength: [10, 10]
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
<?php }?>
