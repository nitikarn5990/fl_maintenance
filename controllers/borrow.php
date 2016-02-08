
<?php
//ยกเลิกการยืม
if ($_GET['action'] == 'cancel' && is_numeric($_GET['id']) && $_GET['id'] != '') {

    if (delete("tb_borrow", "id = " . $_GET['id'])) {
        SetAlert('ลบการยืมสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
        header('location:' . ADDRESS . 'borrow');
        die();
    }

//
//    $data = array(
//        "status" => 'ยกเลิกการยืม', // สถานะ
//        "updated_at" => DATE_TIME, //วันที่แก้ไข
//    );
//
//    if (update("tb_borrow", $data, "id = " . $_GET['id'])) {
//
//        $data2 = array(
//            "status" => 'ยกเลิกการยืม', // สถานะ
//        );
//        if (update("tb_borrow_list", $data2, "borrow_id in(" . $_GET['id'] . ")")) {
//            SetAlert('ยกเลิกการยืมสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
//            header('location:' . ADDRESS . 'borrow');
//            die();
//        }
//    }
}
//ยกเลิกการยืม(ที่ละหลายแถว)
if (isset($_POST['select_all'])) {
    $all_id = implode(',', $_POST['select_all']);

    if (delete("tb_borrow", "id in(" . $all_id . ")")) {
        SetAlert('ลบการยืมสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
        header('location:' . ADDRESS . 'borrow');
        die();
    }
//    $data = array(
//        "status" => 'ยกเลิกการยืม', // สถานะ
//        "updated_at" => DATE_TIME, //วันที่แก้ไข
//    );
//    if (update("tb_borrow", $data, "id in(" . $all_id . ")")) {
//        $data2 = array(
//            "status" => 'ยกเลิกการยืม', // สถานะ
//        );
//        if (update("tb_borrow_list", $data2, "borrow_id in(" . $all_id . ")")) {
//
//            SetAlert('ยกเลิกการยืมสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
//            header('location:' . ADDRESS . 'borrow');
//
//            die();
//        }
//    }
}

// แสดงการแจ้งเตือน
Alert(GetAlert('error'));

Alert(GetAlert('success'), 'success');
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">จัดการยืมสื่อทัศนวัสดุ</h1>

    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <p id="breadcrumb">
            ข้อมูลการยืมทั้งหมด
        </p>
    </div>
</div>
<form action="" method="POST" id="frm_borrow">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    การยืมสื่อทัศนวัสดุ
                </div>
                <div class="panel-toolbar">
                    <div class="btn-group"> 
                        <a class="btn" href="<?= ADDRESS ?>borrow_add"><i class="icol-add"></i> เพิ่มการยืม</a> 

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
                                        <th>รหัสการยืม</th>
                                        <th>รหัสบัตรประชาชน</th>
                                        <th>ชื่อ-สกุลผู้จอง</th>
                                        <th>วันที่ยืม</th>
                                        <th>สถานะ</th>
                                        <th>แก้ไขล่าสุด</th>
                                        <th>ตัวเลือก</th>
                                          <th>การคืน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM tb_borrow WHERE status = 'ยืม' ORDER BY id DESC";
                                    $result = mysql_query($sql);

                                    if (mysql_num_rows($result) > 0) {
                                        while ($row = mysql_fetch_assoc($result)) {
                                            $classStatus = 'success';
                                            if ($row['status'] == 'ยืม') {
                                                $classStatus = 'warning';
                                            }
                                           
                                            ?>
                                            <tr class="<?= $classStatus ?>" >
                                                <td class="center"> <input type="checkbox" name="select_all[]" class="checkboxes" value="<?= $row['id'] ?>" onclick="countSelect()"></td>
                                                <td class="center"><?= $row['id'] ?></td>
                                                <td><?= $row['id_card'] ?></td>
                                                <td><?= getDataDesc('first_name', 'tb_people', 'id = ' . $row['people_id']) . ' ' . getDataDesc('last_name', 'tb_people', 'id = ' . $row['people_id']) ?></td> 
                                                <td><?= $row['borrow_date'] ?></td>
                                                <td class="center"><?= $row['status'] == 'ยืม' ? 'รอคืน':'คืนแล้ว' ?></td>
                                                <td class="center"><?= $row['updated_at'] ?></td>
                                                <td class="center "><a href="<?= ADDRESS ?>borrow_edit&id=<?= $row['id'] ?>" class="btn btn-primary btn-small">แก้ไข / ดู</a> 
                                                    
                                                    <a href="javascript:;" onclick="if (confirm('คุณต้องลบการยืมนี้หรือใม่?') == true) {
                                                                        document.location.href = '<?= ADDRESS ?>borrow&id=<?= $row['id'] ?>&action=cancel'
                                                                    }" class="btn btn-danger btn-small">ลบการยืม</a>
                                                </td>
                                                 <td class="center">
                                                     <a href="<?= ADDRESS ?>return_edit&id=<?= $row['id'] ?>" class="btn btn-success btn-small">คืน</a> 
                                                 </td>
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
        if (confirm("คุณแน่ใจที่จะลบการยืม?")) {
            $("#frm_borrow").submit();
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
