
<?php
if ($_POST['btn_submit'] == 'บันทึกข้อมูล') { //เช็คว่ามีการกดปุ่ม บันทึกข้อมูล
    //ถ้าว่างทำส่วนนี้ คือ เพิม่ลงฐานข้อมูล
    $data = array(
        "detail" => $_POST['detail'], // รายละเอียด
        "qty" => $_POST['qty'], // จำนวน
        "cost" => $_POST['cost'], // ราคา
        "status" => $_POST['status'], // สถานะ
        "created_at" => DATE_TIME, //วันที่บันทึก
        "updated_at" => DATE_TIME, //วันที่แก้ไข
    );

// insert ข้อมูลลงในตาราง tb_computer โดยฃื่อฟิลด์ และค่าตามตัวแปร array ชื่อ $data
    if (insert("tb_computer", $data)) { // บันทึกข้อมูลลงตาราง tb_computer 
    }

    //อัพโหลดภาพ
    if (isset($_FILES['file_array'])) {

        for ($i = 0; $i < count($_FILES['file_array']['tmp_name']); $i++) {

            if ($_FILES["file_array"]["name"][$i] != "") {

                $rootPath = $_SERVER['DOCUMENT_ROOT'];
                $thisPath = dirname($_SERVER['PHP_SELF']);
                $onlyPath = str_replace($rootPath, '', $thisPath);

                $targetPath = $rootPath . '/' . $onlyPath . '/dist/images/media/';

                $ext = explode('.', $_FILES['file_array']['name'][$i]);
                $extension = $ext[count($ext) - 1];
                $rand = mt_rand(1, 100000);

                $newImage = DATE_TIME_FILE . $rand . "." . $extension;

                $cdir = getcwd(); // Save the current directory
                chdir($targetPath);

                move_uploaded_file($_FILES['file_array']['tmp_name'][$i], $targetPath . $newImage);

                chdir($cdir); // Restore the old working directory   
                $data = array(
                    "image" => $newImage, //ชื่อภาพ
                );

                if (update('tb_computer', $data, 'id = ' . getDataDescLastID('id', 'tb_computer'))) {

                    SetAlert('เพิ่ม แก้ไข ข้อมูลสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
                    header('location:' . ADDRESS . 'computer');
                    die();
                }
            }
        }
    }
    SetAlert('เพิ่ม แก้ไข ข้อมูลสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
    header('location:' . ADDRESS . 'computer');
    die();
}

// แสดงการแจ้งเตือน

Alert(GetAlert('error'));

Alert(GetAlert('success'), 'success');
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">เพิ่มข้อมูลคอมพิวเตอร์</h1>

    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <p id="breadcrumb">
            <a href="<?= ADDRESS ?>computer">ข้อมูลคอมพิวเตอร์ทั้งหมด</a>
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
                        <form role="form" action="<?= ADDRESS ?>computer_add" method="POST" enctype="multipart/form-data">

                         
                            <div class="row da-form-row">
                                <label class="col-md-2">รายละเอียด  <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <textarea class="form-control" rows="5" name="detail"><?= isset($_POST['detail']) ? $_POST['detail'] : '' ?></textarea>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row da-form-row">
                                <label class="col-md-2">จำนวน <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" name="qty" type="text" value="<?= isset($_POST['qty']) ? $_POST['qty'] : '' ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="row da-form-row">
                                <label class="col-md-2">ราคา <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" name="cost" type="text" value="<?= isset($_POST['cost']) ? $_POST['cost'] : '' ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>


                            <div class="row da-form-row">
                                <label class="col-md-2">อัพโหลดภาพ </label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" name="file_array[]" type="file" value="">
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="row da-form-row">
                                <label class="col-md-2">สถานะ <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <select class="form-control" name="status">
                                        <option value="ปกติ">ปกติ</option> 
                                        <option value="ส่งซ่อม">ส่งซ่อม</option> 

                                    </select>
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
            detail:{
                 required: true
            },
            name: {
                required: true
            },
           
            qty: {
                required: true,
                number: true
            },
          
            cost: {
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

