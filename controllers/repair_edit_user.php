
<?php
//เช็คการส่งค่า POST ของฟอร์ม


if ($_POST['btn_submit'] == 'บันทึกข้อมูล') { //เช็คว่ามีการกดปุ่ม บันทึกข้อมูล
    //ทำการอัพเดรต ส่วนแรกคือชื่อฟิลล์ในฐานข้อมูล ส่วนที่สองคือ POST ที่มาจากฟอร์ม (จับคู่ให้ตรงกัน)
    $data = array(
        "comment" => $_POST['comment'], // จำนวน
        "status" => $_POST['status'], // สถานะ
        "updated_at" => DATE_TIME, //วันที่แก้ไข
        "date_success" => DATE_TIME, //วันที่แก้ไข
    );


// update ข้อมูลลงในตาราง tb_repair_list โดยฃื่อฟิลด์ และค่าตามตัวแปร array ชื่อ $data
    if (update("tb_repair_list", $data, "repair_id = " . $_GET['id'] . " AND computer_id = " . $_POST['computer_id'])) { //ชื่อตาราง,ข้อมูลจากตัวแปร $data,id ที่จะทำการแก้ไข
        if ($_POST['status'] == 'ซ่อมแล้ว') {
            $computer_status = 'ปกติ';
        } else if ($_POST['status'] == 'แทงจำหน่าย') {
            $computer_status = 'แทงจำหน่าย';
        } else {
            $computer_status = 'ส่งซ่อม';
        }

        $data2 = array(
            "status" => $computer_status, // สถานะ computer
            "updated_at" => DATE_TIME, //วันที่แก้ไข
        );

        update('tb_computer', $data2, 'id = ' . $_POST['computer_id']);
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
                $oldImage = getDataDesc('image', 'tb_repair_list', 'id = ' . $_GET['id']);
                if (update('tb_repair_list', $data, 'id = ' . $_GET['id'])) {
                    @unlink($targetPath . $oldImage); //ลบภาพเก่า
                }
            }
        }
    }
    SetAlert('เพิ่ม แก้ไข ข้อมูลสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
}

//เช็คค่า id ต้องมีค่า และ ไม่เป็นค่าว่าง และ ต้องเป็นตัวเลขเท่านั้น
if (isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])) {

    //ดึงข้อมูลตาม  $_GET['id'] ที่รับมา
    $sql = "SELECT * FROM tb_repair_list WHERE repair_id = " . $_GET['id'];
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

            ข้อมูลการติดตามปัญหาที่แจ้ง

        </h1>

    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row hidden" >
    <div class="col-md-12 col-xs-12">
        <p id="breadcrumb">
            <a href="<?= ADDRESS ?>repair">ข้อมูลทั้งหมด</a>
            ข้อมูลการแจ้ง
        </p>

    </div>
</div>
<div class="row">
    <div class="col-md-12 ">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="icol-application-edit"></i> รหัสการแจ้งซ่อม

            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row da-form-row">
                            <label class="col-md-2">รหัสการแจ้งซ่อม <span class="required">*</span></label>
                            <div class="col-md-10">
                                <input class="form-control input-sm" readonly="" name="" type="text" value="<?= isset($_GET['id']) ? $_GET['id'] : '' ?>">
                                <p class="help-block"></p>
                            </div>
                        </div>
                        <div class="row da-form-row">
                            <label class="col-md-2">ชื่อผู้แจ้งซ่อม <span class="required">*</span></label>
                            <div class="col-md-10">
                                <input class="form-control input-sm" name="first_name" type="text" value="<?= getDataDesc('first_name', 'tb_repair', 'id=' . $_GET['id']) ?>">
                                <p class="help-block"></p>
                            </div>
                        </div>
                        <div class="row da-form-row">
                            <label class="col-md-2">นามสกุลผู้แจ้งซ่อม <span class="required">*</span></label>
                            <div class="col-md-10">
                                <input class="form-control input-sm" name="last_name" type="text" value="<?= getDataDesc('last_name', 'tb_repair', 'id=' . $_GET['id']) ?>">
                                <p class="help-block"></p>
                            </div>
                        </div>
                        <div class="row da-form-row">
                            <label class="col-md-2">วันที่แจ้ง <span class="required">*</span></label>
                            <div class="col-md-10">
                                <input class="form-control input-sm" name="created_at" type="text" value="<?= ShowDate(getDataDesc('created_at', 'tb_repair', 'id=' . $_GET['id'])) ?>">
                                <p class="help-block"></p>
                            </div>
                        </div>


                    </div>

                </div>
                <!-- /.col-lg-6 (nested) -->

                <!-- /.col-lg-6 (nested) -->
            </div>
            <!-- /.row (nested) -->
        </div>
        <!-- /.panel-body -->
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">

            ข้อมูลคอมพิวเตอร์ที่แจ้งซ่อม <?= getDataCount('repair_id', 'tb_repair_list', 'repair_id = ' . $_GET['id']) ?> รายการ

        </h1>

    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
<?php
$sql = "SELECT * FROM tb_repair_list WHERE repair_id = " . $_GET['id'];
$result = mysql_query($sql);

$targetPath = dirname($_SERVER['PHP_SELF']) . '/dist/images/media/';

if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
        ?>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="icol-application-edit"></i> รายการที่ <?= ++$c ?>

                    </div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-12">
                                <form role="form" action="<?= ADDRESS ?>repair_edit&id=<?= $_GET['id'] ?>" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="repair_id" value="">
                                    <div class="da-form-inline">

                                        <div class="row da-form-row">
                                            <label class="col-md-2">รหัสคอมพิวเตอร์ <span class="required">*</span></label>
                                            <div class="col-md-10">
                                                <input class="form-control input-sm" readonly="" name="computer_id" type="text" value="<?php echo $row['computer_id'] ?>">
                                                <p class="help-block"></p>
                                            </div>
                                        </div>
                                        <div class="row da-form-row">
                                            <label class="col-md-2">รายละเอียดปัญหา <span class="required">*</span></label>
                                            <div class="col-md-10">
                                                <textarea class="form-control" rows="5"  name=""><?= $row['problem_description'] ?></textarea>
                                                <p class="help-block"></p>
                                            </div>
                                        </div>
                                        <p>&nbsp;</p>
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">ข้อมูลคอมพิวเตอร์</legend>

                                            <div class="row da-form-row">
                                                <label class="col-md-2">รายละเอียด <span class="required"></span></label>
                                                <div class="col-md-10">
                                                    <textarea class="form-control" rows="5" name="detail" readonly=""><?= getDataDesc('detail', 'tb_computer', 'id=' . $row['computer_id']) ?></textarea>
                                                    <p class="help-block"></p>
                                                </div>
                                            </div>
                                            <div class="row da-form-row ">
                                                <label class="col-md-2">ภาพคอมพิวเตอร์</label>
                                                <div class="col-md-10">
        <?php if ($_GET['id'] != '') { ?>
                                                        <img src="<?= './dist/images/media/' . getDataDesc('image', 'tb_computer', 'id=' . $row['computer_id']) ?>" class="img-responsive"> 
                                                    <?php } ?>
                                                    <p class="help-block"></p>
                                                </div>
                                            </div>
                                        </fieldset>
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">ข้อมูลอาการปัจจุบัน </legend>

                                            <div class="row da-form-row">
                                                <label class="col-md-2">สถานะปัจจุบัน <span class="required">*</span></label>
                                                <div class="col-md-10">
                                                    <select class="form-control hidden" name="statuss" disabled="" >
                                                        <option value="" <?= $row['status'] == '' ? 'selected' : '' ?>>---- รอลงบันทึกอาการ ----</option> 
                                                        <option value="ซ่อมแล้ว" <?= $row['status'] == 'ซ่อมแล้ว' ? 'selected' : '' ?>>ซ่อมแล้ว</option> 
                                                        <option value="รอการแก้ไข" <?= $row['status'] == 'รอการแก้ไข' ? 'selected' : '' ?>>รอการแก้ไข</option>
                                                        <option value="ส่งซ่อมข้างนอก" <?= $row['status'] == 'ส่งซ่อมข้างนอก' ? 'selected' : '' ?>>ส่งซ่อมข้างนอก</option> 
                                                        <option value="แทงจำหน่าย" <?= $row['status'] == 'แทงจำหน่าย' ? 'selected' : '' ?>>แทงจำหน่าย</option> 
                                                    </select>
                                                    <input class="form-control input-sm" readonly="" name="computer_id" type="text" value="<?php echo $row['status'] == '' ? 'อยู่ระหว่างดำเนินการ' : $row['status']  ?>">
                                                    <p class="help-block">
                                                        - ซ่อมได้ทันที ลงบันทึกอาการ สถานะ <span class="badge">ซ่อมแล้ว</span> <br>
                                                        - ซ่อมได้แต่ต้องรออุปกรณ์ ลงบันทึกอาการ สถานะ <span class="badge">รอการแก้ไข</span><br>
                                                        - ซ่อมได้แต่ต้องไปส่งซ่อมข้างนอก ลงบันทึกอาการ สถานะ <span class="badge">ส่งซ่อมข้างนอก</span><br>
                                                        - ซ่อมไม่ได้ ลงบันทึกอาการ สถานะ <span class="badge">แทงจำหน่าย</span>

                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row da-form-row">
                                                <label class="col-md-2">วันที่ซ่อม <span class="required"></span></label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" value="<?= $row['date_success'] == '0000-00-00 00:00:00' ? '-' :  ShowDate($row['date_success'])   ?>">
                                                    <p class="help-block"></p>
                                                </div>
                                            </div>
                                            <div class="row da-form-row">
                                                <label class="col-md-2">หมายเหตุ <span class="required"></span></label>
                                                <div class="col-md-10">
                                                    <textarea class="form-control" rows="5" name="comment" ><?= $row['comment'] ?></textarea>
                                                    <p class="help-block"></p>
                                                </div>
                                            </div>
                                        </fieldset>



                                        <div class="row hidden">
                                            <div class="">
                                                <div class="btn-row">
                                                    <button type="submit" name="btn_submit" value="บันทึกข้อมูล" class="btn btn-sm btn-success">บันทึกข้อมูล</button>
                                                    <button type="reset" class="btn btn-sm btn-danger hidden">ยกเลิก</button>
                                                </div>
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

    <?php } ?>
    <?php } ?>

    <!-- /.panel -->
</div>

<!-- /.col-lg-12 -->

<script>
    $('form2').validate({
        rules: {
            status: {
                required: true,
            },
        },
        messages: {
            status: {
                required: 'กรุณาลงบันทึกอาการ',
            },
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
<style>
    fieldset.scheduler-border {
        border: 1px groove rgba(0, 0, 0, 0.2);
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
        box-shadow:  0px 0px 0px 0px #000;
    }

    legend.scheduler-border {
        font-size: 14px !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
    }
</style>
