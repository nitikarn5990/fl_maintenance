<?php if ($_SESSION['group'] == '' || $_SESSION['group'] == 'ผู้บริหาร') { ?>
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 10px;">
            <p>&nbsp;</p>
            <img src="./dist/images/404.png" class="img-responsive" style="margin: auto;">
        </div>
    </div>
<?php

}else{

if ($_SESSION['group'] != 'ผู้ดูแลระบบ') {
    echo "<h1>ไม่พบหน้าที่คุณต้องการ</h1>";
    die();
}
//ตรวจสอบถ้ามีการลบข้อมูล (ลบที่ละแถว)
if ($_GET['action'] == 'del' && is_numeric($_GET['id']) && $_GET['id'] != '') {

    if (delete("tb_staff", "id = " . $_GET['id'])) {
        SetAlert('ลบข้อมูลสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
        header('location:' . ADDRESS . 'staff');
        die();
    }
}
//ตรวจสอบถ้ามีการลบข้อมูล (ลบที่ละหลายแถว)
if (isset($_POST['select_all'])) {
    $all_id = implode(',', $_POST['select_all']);
    if (delete("tb_staff", "id in(" . $all_id . ")")) {
        SetAlert('ลบข้อมูลสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
        header('location:' . ADDRESS . 'staff');
        die();
    }
}

// แสดงการแจ้งเตือน
Alert(GetAlert('error'));

Alert(GetAlert('success'), 'success');
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">จัดการข้อมูลเจ้าหน้าที่</h1>

    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <p id="breadcrumb">
            จัดการข้อมูลพนักงาน
        </p>
    </div>
</div>
<form action="" method="POST" id="frm_staff">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    ข้อมูลเจ้าหน้าที่
                </div>
                <div class="panel-toolbar">
                    <div class="btn-group"> 
                        <a class="btn" href="<?= ADDRESS ?>staff_add"><i class="icol-add"></i> เพิ่มข้อมูล</a> 

                        <a href="javascript:;" onclick="frm_submit()" class="btn" id="btn-select-delete" ><i class="icol-cross"></i> ลบที่เลือก</a> 
                    </div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body" style="padding-top: 15px;">
                    <div class="table-responsive">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th class="center"></th>
                                        <th>รหัส</th>
                                        <th>ชื่อ-สกุล</th>
                                        <th>สถานะ</th>
                                        <th>เบอร์โทร</th>
                                        <th>แก้ไขล่าสุด</th>
                                        <th>ตัวเลือก</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM tb_staff WHERE username != 'admin' AND status != 'ผู้ใช้บริการ'";
                                    $result = mysql_query($sql);

                                    if (mysql_num_rows($result) > 0) {
                                        while ($row = mysql_fetch_assoc($result)) {
                                            ?>
                                            <tr class="">
                                                <td class="center"> <input type="checkbox" name="select_all[]" class="checkboxes" value="<?= $row['id'] ?>" onclick="countSelect()"></td>
                                                <td class="center"><?= $row['id'] ?></td>
                                                <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                                                <td class="center"><span class="badge"><?= $row['status'] ?></span></td>
                                                <td class="center"><?= $row['tel'] ?></td>
                                                <td class="center"><?= ShowDateThTime($row['updated_at']) ?></td>
                                                <td class="center "><a href="<?= ADDRESS ?>staff_edit&id=<?= $row['id'] ?>" class="btn btn-primary btn-small">แก้ไข / ดู</a> <a href="javascript:;" onclick="if (confirm('คุณต้องการลบข้อมูลนี้หรือใม่?') == true) {
                                                                    document.location.href = '<?= ADDRESS ?>staff&id=<?= $row['id'] ?>&action=del'
                                                                }" class="btn btn-danger btn-small">ลบ</a></td>
                                            </tr>


                                            <?php
                                        }
                                    }
                                    ?>



                                </tbody>
                            </table>
                            <div class="row" style="margin-bottom: 20px;">

                                <div class="col-md-2">
                                    <select class="form-control input-small" id="bulk-action">
                                        <option value="">ตัวเลือก</option>
                                        <option value="เลือกทั้งหมด">เลือกทั้งหมด</option>
                                        <option value="ยกเลิกเลือกทั้งหมด">ยกเลิกเลือกทั้งหมด</option>
                                    </select>
                                </div>

                            </div>
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
        if (confirm("คุณแน่ใจที่จะลบ?")) {
            $("#frm_staff").submit();
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
<?php }?>