
<?php
if ($_POST['btn_submit'] == 'บันทึกข้อมูล') { //เช็คว่ามีการกดปุ่ม บันทึกข้อมูล
    //ถ้าว่างทำส่วนนี้ คือ เพิม่ลงฐานข้อมูล
    $sql_check_id_card= "SELECT * FROM tb_people WHERE id_card = '" . $_POST['id_card'] . "'";

    $result = mysql_query($sql_check_id_card);
    $num_row = mysql_num_rows($result); //หาว่ามีกี่แถว
    if ($num_row > 0) { //ถ้ามีมากกว่า 0 แสดงว่า หมายเลขบัตรประชาชน นี้มีการใช้ไปแล้ว
        SetAlert('หมายเลขบัตรประชาชนนี้ มีการสมัครสมาชิกแล้ว'); //แสดงข้อมูลแจ้งเตือน
    } else {
        $data = array(
            "id_card" => $_POST['id_card'], //เลขที่บัตรประชาชน
            "first_name" => $_POST['first_name'], //ชื่อ
            "last_name" => $_POST['last_name'], //สกุล
            "address" => $_POST['address'], //ที่อยู่
            "email" => $_POST['email'], //email
            "tel" => $_POST['tel'], // เบอร์โทร
            "created_at" => DATE_TIME, //วันที่บันทึก
            "updated_at" => DATE_TIME, //วันที่แก้ไข
        );
        // insert ข้อมูลลงในตาราง tb_people โดยฃื่อฟิลด์ และค่าตามตัวแปร array ชื่อ $data
        if (insert("tb_people", $data)) { // บันทึกข้อมูลลงตาราง tb_people 
            //  echo AlertSuccess;
            SetAlert('เพิ่ม แก้ไข ข้อมูลสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
            header('location:' . ADDRESS . 'people');
            die();
        }
    }
}

// แสดงการแจ้งเตือน

Alert(GetAlert('error'));

Alert(GetAlert('success'), 'success');
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">เพิ่มข้อมูลประชาชน</h1>

    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <p id="breadcrumb">
            <a href="<?= ADDRESS ?>people">ข้อมูลประชาชนทั้งหมด</a>
            เพิ่มข้อมูล
        </p>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="icol-add"></i> เพิ่มข้อมูล
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <form role="form" action="<?= ADDRESS ?>people_add" method="POST">
                            <div class="row da-form-row">
                                <label class="col-md-2">รหัสบัตรประชาชน <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" name="id_card" type="text" value="<?= isset($_POST['id_card']) ? $_POST['id_card'] : '' ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row da-form-row">
                                <label class="col-md-2">ชื่อ <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" name="first_name" type="text" value="<?= isset($_POST['first_name']) ? $_POST['first_name'] : '' ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row da-form-row">
                                <label class="col-md-2">สกุล <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" name="last_name" type="text" value="<?= isset($_POST['last_name']) ? $_POST['last_name'] : '' ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row da-form-row">
                                <label class="col-md-2">ที่อยู่ <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="address"><?= isset($_POST['address']) ? $_POST['address'] : '' ?></textarea>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row da-form-row">
                                <label class="col-md-2">Email <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" name="email" type="text" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row da-form-row">
                                <label class="col-md-2">เบอร์ติดต่อ <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" name="tel" type="text" value="<?= isset($_POST['tel']) ? $_POST['tel'] : '' ?>">
                                    <p class="help-block"></p>
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
            id_card: {
                required: true,
                number: true,
                 rangelength: [13, 13]
            },
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            address: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            tel: {
                required: true,
                number: true
            },
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

