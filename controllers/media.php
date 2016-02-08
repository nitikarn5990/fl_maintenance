<?php
//ตรวจสอบถ้ามีการลบข้อมูล (ลบที่ละแถว)
if ($_GET['action'] == 'del' && is_numeric($_GET['id']) && $_GET['id'] != '') {

    if (delete("tb_media", "id = " . $_GET['id'])) {
        SetAlert('ลบข้อมูลสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
        header('location:' . ADDRESS . 'media');
        die();
    }
}
//ตรวจสอบถ้ามีการลบข้อมูล (ลบที่ละหลายแถว)
if (isset($_POST['select_all'])) {
    $all_id = implode(',', $_POST['select_all']);
    if (delete("tb_media", "id in(" . $all_id . ")")) {
        SetAlert('ลบข้อมูลสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
        header('location:' . ADDRESS . 'media');
        die();
    }
}

// แสดงการแจ้งเตือน
Alert(GetAlert('error'));

Alert(GetAlert('success'), 'success');
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">จัดการสื่อทัศนวัสดุ</h1>

    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <p id="breadcrumb">
            จัดการสื่อทัศนวัสดุ
        </p>
    </div>
</div>
<form action="" method="POST" id="frm_media">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    สื่อทัศนวัสดุ
                </div>
                <div class="panel-toolbar">
                    <div class="btn-group"> 
                        <a class="btn" href="<?= ADDRESS ?>media_add"><i class="icol-add"></i> เพิ่มข้อมูล</a> 

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
                                        <th>ประเภท</th>
                                        <th>ชื่อสื่อ</th>
                                        <th>ภาพ</th>
                                        <th>จำนวน</th>
                                        <th>ราคา</th>
                                        <th>สถานะ</th>
                                        <th>แก้ไขล่าสุด</th>
                                        <th>ตัวเลือก</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM tb_media";
                                    $result = mysql_query($sql);
                                    
                                    $targetPath = dirname($_SERVER['PHP_SELF']) . '/dist/images/media/' ;

                                    if (mysql_num_rows($result) > 0) {
                                        while ($row = mysql_fetch_assoc($result)) {
                                            ?>
                                            <tr class="">
                                                <td class="center"> <input type="checkbox" name="select_all[]" class="checkboxes" value="<?= $row['id'] ?>" onclick="countSelect()"></td>
                                                <td class="center"><?= $row['id']?></td>
                                                <td><?= getDataDesc('name', 'tb_category', 'id = ' . $row['category_id']) //เรียกใช่ฟังชั่น 1)ชื่อฟิลด์ 2)ชื่อตาราง 3)where (เงื่อนไข)     ?></td> 
                                                <td><?= $row['name'] ?></td>
                                                <td><img src="<?= $targetPath.$row['image'] ?>" style="width: 75px;"></td>
                                                <td class="center"><?= $row['qty'] ?></td>
                                                <td class="center"><?= $row['cost'] ?></td>
                                                <td><?= $row['status'] ?></td>
                                                <td class="center"><?= ShowDateThTime($row['updated_at']) ?></td>
                                                <td class="center "><a href="<?= ADDRESS ?>media_edit&id=<?= $row['id'] ?>" class="btn btn-primary btn-small">แก้ไข / ดู</a> <a href="javascript:;" onclick="if (confirm('คุณต้องการลบข้อมูลนี้หรือใม่?') == true) {
                                                                    document.location.href = '<?= ADDRESS ?>media&id=<?= $row['id'] ?>&action=del'
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
            $("#frm_media").submit();
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

        $('input.checkboxes[type=checkbox]').each(function () {
            if ($(this).is(":checked")) {
                $(this).closest('tr').css("background-color", "rgba(255, 235, 59, 0.46)");
            } else {
                $(this).closest('tr').css("background-color", "rgba(255, 235, 59, 0)");
            }
        });
    }


</script>
