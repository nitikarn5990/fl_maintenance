
<?php
if ($_POST['btn_submit'] == 'บันทึกข้อมูล') { //เช็คว่ามีการกดปุ่ม บันทึกข้อมูล
    //ถ้าว่างทำส่วนนี้ คือ เพิม่ลงฐานข้อมูล
    $data = array(
        "name" => $_POST['name'],
        "created_at" => DATE_TIME, //วันที่บันทึก
        "updated_at" => DATE_TIME, //วันที่แก้ไข
    );

// insert ข้อมูลลงในตาราง tb_category โดยฃื่อฟิลด์ และค่าตามตัวแปร array ชื่อ $data
    if (insert("tb_category", $data)) { // บันทึกข้อมูลลงตาราง tb_category 
        //  echo AlertSuccess;
        SetAlert('เพิ่ม แก้ไข ข้อมูลสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
        header('location:' . ADDRESS . 'category');
        die();
    } else {
        SetAlert('เกิดข้อผิดพลาดไม่สามารถเพิ่มข้อมูลได้'); //แสดงข้อมูลแจ้งเตือนถ้าไม่สำเร็จ
        header('location:' . ADDRESS . 'category_add');
        die();
    }
}

// แสดงการแจ้งเตือน

Alert(GetAlert('error'));

Alert(GetAlert('success'), 'success');
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">เพิ่มข้อมูลประเภทคอมพิวเตอร์</h1>

    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <p id="breadcrumb">
            <a href="<?= ADDRESS ?>category">ข้อมูลประเภทคอมพิวเตอร์ทั้งหมด</a>
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
                        <form role="form" action="<?= ADDRESS ?>category_add" method="POST">
                            <div class="row da-form-row">
                                <label class="col-md-2">ชื่อประเภทคอมพิวเตอร์ <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" name="name" type="text" value="<?= isset($_POST['name']) ? $_POST['name'] : '' ?>">
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
            name: {
                required: true
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

