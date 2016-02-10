<!-- Latest compiled and minified CSS -->
<link href="./plugins/datepicker/jquery.datepick.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="./plugins/datepicker/jquery.plugin.js"></script>
<script src="./plugins/datepicker/jquery.datepick.js"></script>
<script src="./plugins/datepicker/jquery.datepick-th.js"></script>
<?php
//ยกเลิกการยืม
if ($_GET['action'] == 'cancel' && is_numeric($_GET['id']) && $_GET['id'] != '') {

    if (delete("tb_repair", "id = " . $_GET['id'])) {
        SetAlert('ลบการยืมสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
        header('location:' . ADDRESS . 'repair2');
        die();
    }
}
//ยกเลิกการยืม(ที่ละหลายแถว)
if (isset($_POST['select_all'])) {
    $all_id = implode(',', $_POST['select_all']);

    if (delete("tb_repair", "id in(" . $all_id . ")")) {
        SetAlert('ลบการยืมสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
        header('location:' . ADDRESS . 'repair2');
        die();
    }
}

// แสดงการแจ้งเตือน
Alert(GetAlert('error'));

Alert(GetAlert('success'), 'success');
?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?php
            if ($_SESSION['group'] == 'ผู้บริหาร' || $_SESSION['group'] == 'ผู้ดูแลระบบ') {
                echo "รายงานสรุปผลการปฏิบัติงาน";
            } else {
                echo "รายงานสรุปผลการปฏิบัติงานของตนเอง";
            }
            ?>
        </h1>

    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <p id="breadcrumb">
            <a href="<?= ADDRESS ?>report_type">รายงาน</a>
            <?php
            if ($_SESSION['group'] == 'ผู้บริหาร' || $_SESSION['group'] == 'ผู้ดูแลระบบ') {
                echo "รายงานสรุปผลการปฏิบัติงาน";
            } else {
                echo "รายงานสรุปผลการปฏิบัติงานของตนเอง";
            }
            ?>
        </p>
    </div>
</div>
<form method="POST" action="<?= ADDRESS ?>report_repair2">
    <div class="row">
        <div class="">
            <div class="col-md-9">
                <div class="row" style="margin-bottom: 15px;">
                    <label class="col-md-2">วันที่แจ้ง <span class="required"></span></label>
                    <div class="col-md-4">
                        <input type="text" class="form-control " id="st_date" name="st_date"  value="<?= isset($_POST['st_date']) ? $_POST['st_date'] : '' ?>" readonly="" required="">
                    </div>
                    <div class="col-md-2 center">ถึง</div>
                    <div class="col-md-4">
                        <input type="text" class="form-control " id="ed_date" name="ed_date" readonly="" value="<?= isset($_POST['ed_date']) ? $_POST['ed_date'] : '' ?>" required="">
                    </div>
                </div>
                <div class="row" style="margin-bottom: 15px;">
                    <label class="col-md-2">วันที่ลงบันทึก <span class="required"></span></label>
                    <div class="col-md-4">
                        <input type="text" class="form-control " id="st_date2" name="st_date2" value="<?= isset($_POST['st_date2']) ? $_POST['st_date2'] : '' ?>" readonly="" required="">
                    </div>
                    <div class="col-md-2 center">ถึง</div>
                    <div class="col-md-4">
                        <input type="text" class="form-control " id="ed_date2" name="ed_date2" readonly="" value="<?= isset($_POST['ed_date2']) ? $_POST['ed_date2'] : '' ?>" required="">
                    </div>
                </div>
                <div class="row"  style="margin-bottom: 15px;">
                    <label class="col-md-2">ประเภทอุปกรณ์ <span class="required"></span></label>
                    <div class="col-md-10">

                        <select class="form-control" name="category_id">
                            <option value="">เลือกประเภท</option> 
                            <?php
                            $sql = "SELECT * FROM tb_category";
                            $result = mysql_query($sql);
                            $numRow = mysql_num_rows($result);
                            if ($numRow > 0) {
                                while ($row = mysql_fetch_assoc($result)) {
                                    ?>
                                    <option value="<?= $row['id'] ?>" <?= isset($_POST['category_id']) && $_POST['category_id'] == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option> 
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        <p class="help-block"></p>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 15px;">
                    <label class="col-md-2">สถานะ <span class="required"></span></label>
                    <div class="col-md-10">

                        <select class="form-control" name="status">
                            <option value="">เลือกสถานะ</option> 
                            <option value="ซ่อมแล้ว" <?= isset($_POST['status']) && $_POST['status'] == 'ซ่อมแล้ว' ? 'selected' : '' ?>>ซ่อมแล้ว</option> 
                            <option value="รอการแก้ไข" <?= isset($_POST['status']) && $_POST['status'] == 'รอการแก้ไข' ? 'selected' : '' ?>>รอการแก้ไข</option> 
                            <option value="ส่งซ่อมข้างนอก" <?= isset($_POST['status']) && $_POST['status'] == 'ส่งซ่อมข้างนอก' ? 'selected' : '' ?>>ส่งซ่อมข้างนอก</option> 
                            <option value="แทงจำหน่าย" <?= isset($_POST['status']) && $_POST['status'] == 'แทงจำหน่าย' ? 'selected' : '' ?>>แทงจำหน่าย</option> 

                        </select>
                        <p class="help-block"></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="col-md-12">
                    <p><button type="submit" value="ค้นหา" name="btn_submit" class="form-control btn btn-primary f-12">ค้นหา</button></p>
                    <p><a href="<?= ADDRESS ?>report_repair2" type="button" class="form-control btn btn-danger f-12">ยกเลิก</a></p>
                </div>
            </div>
        </div>

    </div>
</form>

<form action="" method="POST" id="frm_repair2">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php
                    if ($_SESSION['group'] == 'ผู้บริหาร' || $_SESSION['group'] == 'ผู้ดูแลระบบ') {
                        echo "สรุปผลการปฏิบัติงาน";
                    } else {
                        echo "สรุปผลการปฏิบัติงานของตนเอง";
                    }
                    ?>
                </div>
                <div class="panel-toolbar hidden">
                    <div class="btn-group"> 
                        <a class="btn" href="<?= ADDRESS ?>repair_add"><i class="icol-add"></i> เพิ่มการยืม</a> 

                        <a href="javascript:;" onclick="frm_submit()" class="btn" id="btn-select-delete" ><i class="icol-cross"></i> ลบที่เลือก</a> 
                    </div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body" style="padding-top: 15px;">
                    <div class="table-responsive">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example2">
                                <thead>
                                    <tr>

                                        <th class="center">รหัสการแจ้ง</th>
                                        <th class="center">ประเภท</th>
                                        <th class="center">รหัสคอมพิวเตอร์</th>
                                        <th class="center">ภาพ</th>
                                        <th class="center">ปัญหา</th>

                                        <th class="center">วันที่แจ้ง</th>
                                        <th class="center ">สถานะ</th>
                                        <th class="center">วันที่ลงบันทึก</th>
                                        <?php if ($_SESSION['group'] == 'ผู้บริหาร' || $_SESSION['group'] == 'ผู้ดูแลระบบ') { ?>
                                            <th class="center">ผู้แจ้งเรื่อง</th>
                                        <?php } ?>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    //ประเภท
                                    if ($_POST['category_id'] != '') {
                                        $sql_category = " AND category_id = '" . $_POST['category_id'] . "' ";
                                    } else {
                                        $sql_category = "";
                                    }

                                    //สถานะ
                                    if ($_POST['status'] != '') {
                                        $sql_status = " AND b.status = '" . $_POST['status'] . "' ";
                                    } else {
                                        $sql_status = "";
                                    }

                                    //วันที่ลงบันทึก
                                    if ($_POST['st_date2'] != '' && $_POST['ed_date2'] != '') {
                                        $sql_datesuccess = " AND date_success between '" . $_POST['st_date2'] . " 00:00:00' and '" . $_POST['ed_date2'] . " 23:59:59' ";
                                    } else {
                                        $sql_datesuccess = "";
                                    }

                                    if ($_SESSION['group'] == 'ผู้บริหาร' || $_SESSION['group'] == 'ผู้ดูแลระบบ') {
                                        if ($_POST['st_date'] != '' && $_POST['ed_date'] != '') {
                                            $sql = "SELECT * FROM tb_repair_list WHERE date_input between '" . $_POST['st_date'] . " 00:00:00' and '" . $_POST['ed_date'] . " 23:59:59'  ORDER BY date_input DESC";

                                            $sql = "SELECT b.*,c.category_id,a.staff_id FROM " .
                                                    "(SELECT * FROM `tb_repair`)a " .
                                                    "LEFT JOIN " .
                                                    "(SELECT * FROM tb_repair_list  ORDER BY date_input DESC)b " .
                                                    "ON a.id = b.repair_id " .
                                                    "LEFT JOIN " .
                                                    "(SELECT * FROM tb_computer )c " .
                                                    "ON b.computer_id = c.id " .
                                                    "WHERE a.staff_id != 0  AND date_input between '" . $_POST['st_date'] . " 00:00:00' and '" . $_POST['ed_date'] . " 23:59:59' " . $sql_status . $sql_datesuccess . $sql_category .
                                                    " ORDER BY b.date_input DESC";
                                        } else {
                                            $sql = "SELECT b.*,c.category_id,a.staff_id FROM " .
                                                    "(SELECT * FROM `tb_repair`)a " .
                                                    "LEFT JOIN " .
                                                    "(SELECT * FROM tb_repair_list  ORDER BY date_input DESC)b " .
                                                    "ON a.id = b.repair_id " .
                                                    "LEFT JOIN " .
                                                    "(SELECT * FROM tb_computer )c " .
                                                    "ON b.computer_id = c.id " .
                                                    "WHERE a.staff_id != 0 " . $sql_status . $sql_datesuccess . $sql_category .
                                                    " ORDER BY b.date_input DESC";
                                        }
                                    } else {
                                        if ($_POST['st_date'] != '' && $_POST['ed_date'] != '') {
                                            $sql = "SELECT * FROM tb_repair_list WHERE date_input between '" . $_POST['st_date'] . " 00:00:00' and '" . $_POST['ed_date'] . " 23:59:59'  ORDER BY date_input DESC";

                                            $sql = "SELECT b.*,c.category_id,a.staff_id FROM " .
                                                    "(SELECT * FROM `tb_repair`)a " .
                                                    "LEFT JOIN " .
                                                    "(SELECT * FROM tb_repair_list  ORDER BY date_input DESC)b " .
                                                    "ON a.id = b.repair_id " .
                                                    "LEFT JOIN " .
                                                    "(SELECT * FROM tb_computer )c " .
                                                    "ON b.computer_id = c.id " .
                                                    "WHERE a.staff_id = " . $_SESSION['user_id'] . " AND date_input between '" . $_POST['st_date'] . " 00:00:00' and '" . $_POST['ed_date'] . " 23:59:59' " . $sql_status . $sql_datesuccess . $sql_category .
                                                    " ORDER BY b.date_input DESC";
                                        } else {
                                            $sql = "SELECT b.*,c.category_id,a.staff_id FROM " .
                                                    "(SELECT * FROM `tb_repair`)a " .
                                                    "LEFT JOIN " .
                                                    "(SELECT * FROM tb_repair_list  ORDER BY date_input DESC)b " .
                                                    "ON a.id = b.repair_id " .
                                                    "LEFT JOIN " .
                                                    "(SELECT * FROM tb_computer )c " .
                                                    "ON b.computer_id = c.id " .
                                                    "WHERE a.staff_id = " . $_SESSION['user_id'] . $sql_status . $sql_datesuccess . $sql_category .
                                                    " ORDER BY b.date_input DESC";
                                        }
                                    }



                                    // echo $sql;

                                    $result = mysql_query($sql);

                                    if (mysql_num_rows($result) > 0) {
                                        while ($row = mysql_fetch_assoc($result)) {
                                            $classStatus = '';

                                            $image = getDataDesc('image', 'tb_computer', 'id = ' . $row['computer_id']);
                                            // $cat_id = getDataDesc('category_id', 'tb_computer', 'id =' . $row['computer_id'])
                                            ?>
                                            <tr class="<?= $classStatus ?>" >

                                                <td class="center"><?= $row['repair_id'] ?></td>
                                                <td class="center"><?= getDataDesc('name', 'tb_category', 'id = ' . $row['category_id']) ?></td> 
                                                <td class="center"><?= $row['computer_id'] ?></td> 
                                                <td class="center"> <img src="./dist/images/media/<?= $image ?>" style="width: 75px;"></td> 
                                                <td class="center"> <?= $row['problem_description'] ?></td>

                                                <td class="center"> <?= ShowDate($row['date_input']) ?></td>
                                                <td class="center "> <?= $row['status'] ?></td>
                                                <td class="center"> <?= ShowDate($row['date_success']) ?></td>
                                                <?php if ($_SESSION['group'] == 'ผู้บริหาร' || $_SESSION['group'] == 'ผู้ดูแลระบบ') { ?>
                                                    <td class="center"> <?= getDataDesc('first_name', 'tb_staff', 'id = ' . $row['staff_id']) ?></td>
                                                <?php } ?>
                                                </td>

                                            </tr>


                                            <?php
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>

                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
</form>
<script>
    function frm_submit() {
        if (confirm("คุณแน่ใจที่จะลบการยืม?")) {
            $("#frm_repair").submit();
        }


    }

    $("#bulk-action").change(function () {

        if ($(this).val() === 'เลือกทั้งหมด') {
            $(".checkboxes").each(function () {

                $(this).prop("checked", true);
            });
        }
        if ($(this).val() === 'ยกเลิกเลือกทั้งหมด') {
            $(".checkboxes").each(function () {
                $(this).prop("checked", false);
            });
        }
        countSelect();
    });
    $('#btn-select-delete').hide();
    function countSelect() {
        var len = $('.checkboxes:checked').length;
        if (len === 0) {
            $('#btn-select-delete').hide();
        } else {
            $('#btn-select-delete').show();
        }
    }


</script>


<script>
    $(function () {
        // $('#transfer_date').datepick();
        $('#st_date').datepick({
            dateFormat: 'yyyy-mm-dd'
        });
        $('#ed_date').datepick({
            dateFormat: 'yyyy-mm-dd'
        });

        $('#st_date2').datepick({
            dateFormat: 'yyyy-mm-dd'
        });
        $('#ed_date2').datepick({
            dateFormat: 'yyyy-mm-dd'
        });
        // $('#inlineDatepicker').datepick({onSelect: showDate});
    });

</script>
<style>
    #st_date,#ed_date{
        background-color: #FFF;
    }
    .f-12{
        font-size: 12px;
    }
</style>