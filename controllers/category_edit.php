
<?php
//เช็คการส่งค่า POST ของฟอร์ม
//var_dump(strpos('category', 'category'));

if ($_POST['btn_submit'] == 'บันทึกข้อมูล') { //เช็คว่ามีการกดปุ่ม บันทึกข้อมูล
    //ทำการอัพเดรต ส่วนแรกคือชื่อฟิลล์ในฐานข้อมูล ส่วนที่สองคือ POST ที่มาจากฟอร์ม (จับคู่ให้ตรงกัน)
    $data = array(
        "name" => $_POST['name'],
        "updated_at" => DATE_TIME, //วันที่แก้ไข
    );

// update ข้อมูลลงในตาราง tb_category โดยฃื่อฟิลด์ และค่าตามตัวแปร array ชื่อ $data
    if (update("tb_category", $data, "id = " . $_GET['id'])) { //ชื่อตาราง,ข้อมูลจากตัวแปร $data,id ที่จะทำการแก้ไข
        //  echo AlertSuccess;
        SetAlert('เพิ่ม แก้ไข ข้อมูลสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
        //   header('location:' . ADDRESS . 'category_edit&id=' . $_POST['id'] . $_POST['action'] != '' ? '&action=repassword':''); //กลับยังหน้าแสดงข้อมูล category ทั้งหมด
        //   die();
    } else {
        SetAlert('เกิดข้อผิดพลาดไม่สามารถเพิ่มข้อมูลได้'); //แสดงข้อมูลแจ้งเตือนถ้าไม่สำเร็จ
        header('location:' . ADDRESS . 'category_edit');
        die();
    }
}

//เช็คค่า id ต้องมีค่า และ ไม่เป็นค่าว่าง และ ต้องเป็นตัวเลขเท่านั้น
if (isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])) {

    //ดึงข้อมูลตาม  $_GET['id'] ที่รับมา
    $sql = "SELECT * FROM tb_category WHERE id = " . $_GET['id'];
    $result = mysql_query($sql);
    $num_row = mysql_num_rows($result);
    if ($num_row == 1) {
        $row = mysql_fetch_assoc($result);
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

            แก้ไขชื่อประเภทคอมพิวเตอร์

        </h1>

    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <p id="breadcrumb">
            <a href="<?= ADDRESS ?>category">ข้อมูลประเภทคอมพิวเตอร์ทั้งหมด</a>
            แก้ไขข้อมูล
        </p>

    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="icol-application-edit"></i> แก้ไขข้อมูล

            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <form role="form" action="<?= ADDRESS ?>category_edit&id=<?= $_GET['id'] ?>" method="POST">
                       
                            <input type="hidden" name="id" value="<?= isset($_GET['id']) ? $_GET['id'] : '' ?>">    

                            <div class="row da-form-row">
                                <label class="col-md-2">ชื่อประเภท <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" name="name" type="text" value="<?= isset($row['name']) ? $row['name'] : '' ?>">
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

