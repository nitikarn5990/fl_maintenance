
<?php
//เช็คการส่งค่า POST ของฟอร์ม
//var_dump(strpos('media', 'media'));

if ($_POST['btn_submit'] == 'บันทึกข้อมูล') { //เช็คว่ามีการกดปุ่ม บันทึกข้อมูล
    //ทำการอัพเดรต ส่วนแรกคือชื่อฟิลล์ในฐานข้อมูล ส่วนที่สองคือ POST ที่มาจากฟอร์ม (จับคู่ให้ตรงกัน)
    $data = array(
        "name" => $_POST['name'], //ชื่อสื่อ
        "category_id" => $_POST['category_id'], //id สื่อ
        "detail" => $_POST['detail'], // รายละเอียด
        "qty" => $_POST['qty'], // จำนวน
        "available" => $_POST['qty'], // จำนวนเหลือที่สามารถให้ยืมหรอจองได้
        "days_borrow" => $_POST['days_borrow'], //จำนวนวันที่สามาระยืมได้
        "cost" => $_POST['cost'], // ราคาสื่อ
        "fine_per_day" => $_POST['fine_per_day'], // ราคาค่าปรับต่อวัน
        "status" => $_POST['status'], // สถานะ
        "agent_id" => $_POST['agent_id'], //id ตัวแทนจำหน่าย
        "updated_at" => DATE_TIME, //วันที่แก้ไข
    );

// update ข้อมูลลงในตาราง tb_media โดยฃื่อฟิลด์ และค่าตามตัวแปร array ชื่อ $data
    if (update("tb_media", $data, "id = " . $_GET['id'])) { //ชื่อตาราง,ข้อมูลจากตัวแปร $data,id ที่จะทำการแก้ไข
        //  echo AlertSuccess;
         // SetAlert('เพิ่ม แก้ไข ข้อมูลสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
        //   header('location:' . ADDRESS . 'media_edit&id=' . $_POST['id'] . $_POST['action'] != '' ? '&action=repassword':''); //กลับยังหน้าแสดงข้อมูล media ทั้งหมด
        //   die();
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
                $oldImage = getDataDesc('image', 'tb_media', 'id = ' . $_GET['id']);
                if (update('tb_media', $data, 'id = ' . $_GET['id'])) {
                    @unlink($targetPath.$oldImage); //ลบภาพเก่า
                  //  SetAlert('เพิ่ม แก้ไข ข้อมูลสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
                   // header('location:' . ADDRESS . 'media');
                  //  die();
                }
            }
        }
    }
    SetAlert('เพิ่ม แก้ไข ข้อมูลสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
   // header('location:' . ADDRESS . 'media');
    //die();
}

//เช็คค่า id ต้องมีค่า และ ไม่เป็นค่าว่าง และ ต้องเป็นตัวเลขเท่านั้น
if (isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])) {

    //ดึงข้อมูลตาม  $_GET['id'] ที่รับมา
    $sql = "SELECT * FROM tb_media WHERE id = " . $_GET['id'];
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

            แก้ไขข้อมูลสื่อ

        </h1>

    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <p id="breadcrumb">
            <a href="<?= ADDRESS ?>media">ข้อมูลสื่อทั้งหมด</a>
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
                        <form role="form" action="<?= ADDRESS ?>media_edit&id=<?= $_GET['id'] ?>" method="POST" enctype="multipart/form-data">
                            <div class="row da-form-row">
                                <label class="col-md-2">ชื่อสื่อ <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" name="name" type="text" value="<?= isset($row['name']) ? $row['name'] : '' ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row da-form-row">
                                <label class="col-md-2">สื่อประเภท <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <select class="form-control" name="category_id">
                                        <option value="">เลือกประเภท</option> 
                                        <?php
                                        $sql2 = "SELECT * FROM tb_category";
                                        $result2 = mysql_query($sql2);
                                        $numRow2 = mysql_num_rows($result2);
                                        if ($numRow2 > 0) {
                                            while ($row2 = mysql_fetch_assoc($result2)) {
                                                ?>
                                                <option value="<?= $row2['id'] ?>" <?= $row['category_id'] == $row2['id'] ? 'selected' : '' ?>><?= $row2['name'] ?></option> 
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row da-form-row">
                                <label class="col-md-2">รายละเอียด </label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="detail"><?= isset($row['detail']) ? $row['detail'] : '' ?></textarea>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row da-form-row">
                                <label class="col-md-2">จำนวน <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" name="qty" type="text" value="<?= isset($row['qty']) ? $row['qty'] : '' ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row da-form-row">
                                <label class="col-md-2">จำนวนวันที่ยืมได้ <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" name="days_borrow" type="text" value="<?= isset($row['days_borrow']) ? $row['days_borrow'] : '' ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row da-form-row">
                                <label class="col-md-2">ราคา <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" name="cost" type="text" value="<?= isset($row['cost']) ? $row['cost'] : '' ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row da-form-row">
                                <label class="col-md-2">ค่าปรับต่อวัน <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" name="fine_per_day" type="text" value="<?= isset($row['fine_per_day']) ? $row['fine_per_day'] : '' ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row da-form-row">
                                <label class="col-md-2">ตัวแทนจำหน่าย <span class="required">*</span></label>
                                <div class="col-md-10">

                                    <select class="form-control" name="agent_id">
                                        <option value="">เลือกตัวแทนจำหน่าย</option> 
                                        <?php
                                        $sql3 = "SELECT * FROM tb_agent";
                                        $result3 = mysql_query($sql3);
                                        $numRow3 = mysql_num_rows($result3);
                                        if ($numRow3 > 0) {
                                            while ($row3 = mysql_fetch_assoc($result3)) {
                                                ?>
                                                <option value="<?= $row3['id'] ?>" <?= $row['agent_id'] == $row3['id'] ? 'selected' : '' ?>><?= $row3['name'] ?></option> 
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                            </div>

                            <div class="row da-form-row">
                                <label class="col-md-2">ภาพที่อัพโหลด</label>
                                <div class="col-md-10">
                                    <?php if ($_GET['id'] != '') { ?>
                                        <img src="<?= './dist/images/media/' . getDataDesc('image', 'tb_media', 'id=' . $_GET['id']) ?>" style="max-width: 100%;" class="img-thumbnail"> 
                                    <?php } ?>
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
                                <label class="col-md-2">สถานะ </label>
                                <div class="col-md-10">
                                    <input class="form-control input-sm" name="status" type="text" value="<?= isset($row['status']) ? $row['status'] : '' ?>">
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
            category_id: {
                required: true,
            },
            qty: {
                required: true,
                number: true
            },
            days_borrow: {
                required: true,
                number: true
            },
            cost: {
                required: true,
                number: true
            },
            fine_per_day: {
                required: true,
                number: true
            },
            agent_id: {
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

