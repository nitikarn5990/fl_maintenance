
<?php
//ยกเลิกการจอง
if ($_GET['action'] == 'cancel' && is_numeric($_GET['id']) && $_GET['id'] != '') {

    if (delete("tb_booking", "id = " . $_GET['id'])) {
        SetAlert('ลบการจองสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
        header('location:' . ADDRESS . 'booking');
        die();
    }

//
//    $data = array(
//        "status" => 'ยกเลิกการจอง', // สถานะ
//        "updated_at" => DATE_TIME, //วันที่แก้ไข
//    );
//
//    if (update("tb_booking", $data, "id = " . $_GET['id'])) {
//
//        $data2 = array(
//            "status" => 'ยกเลิกการจอง', // สถานะ
//        );
//        if (update("tb_booking_list", $data2, "booking_id in(" . $_GET['id'] . ")")) {
//            SetAlert('ยกเลิกการจองสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
//            header('location:' . ADDRESS . 'booking');
//            die();
//        }
//    }
}
//ยกเลิกการจอง(ที่ละหลายแถว)
if (isset($_POST['select_all'])) {
    $all_id = implode(',', $_POST['select_all']);

    if (delete("tb_booking", "id in(" . $all_id . ")")) {
        SetAlert('ลบการจองสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
        header('location:' . ADDRESS . 'booking');
        die();
    }
//    $data = array(
//        "status" => 'ยกเลิกการจอง', // สถานะ
//        "updated_at" => DATE_TIME, //วันที่แก้ไข
//    );
//    if (update("tb_booking", $data, "id in(" . $all_id . ")")) {
//        $data2 = array(
//            "status" => 'ยกเลิกการจอง', // สถานะ
//        );
//        if (update("tb_booking_list", $data2, "booking_id in(" . $all_id . ")")) {
//
//            SetAlert('ยกเลิกการจองสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
//            header('location:' . ADDRESS . 'booking');
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
        <h1 class="page-header">จัดการจองสื่อทัศนวัสดุ</h1>

    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <p id="breadcrumb">
            ข้อมูลการจองทั้งหมด
        </p>
    </div>
</div>
<form action="" method="POST" id="frm_booking">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    การจองสื่อทัศนวัสดุ
                </div>
                <div class="panel-toolbar">
                    <div class="btn-group"> 
                        <a class="btn" href="<?= ADDRESS ?>booking_add"><i class="icol-add"></i> เพิ่มการจอง</a> 

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
                                        <th>รหัสการจอง</th>
                                        <th>รหัสบัตรประชาชน</th>
                                        <th>ชื่อ-สกุลผู้จอง</th>
                                        <th>วันที่จอง</th>
                                        <th>สถานะ</th>
                                        <th>แก้ไขล่าสุด</th>
                                        <th>ตัวเลือก</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM tb_booking ORDER BY id DESC";
                                    $result = mysql_query($sql);

                                    if (mysql_num_rows($result) > 0) {
                                        while ($row = mysql_fetch_assoc($result)) {
                                            $classStatus = 'success';
                                            if ($row['status'] == 'ยกเลิกการจอง') {
                                                $classStatus = 'danger';
                                            }
                                            if ($row['status'] == 'จองอยู่') {
                                                $classStatus = 'warning';
                                            }
                                            ?>
                                            <tr class="<?= $classStatus ?>" >
                                                <td class="center"> <input type="checkbox" name="select_all[]" class="checkboxes" value="<?= $row['id'] ?>" onclick="countSelect()"></td>
                                                <td class="center"><?= $row['id'] ?></td>
                                                <td><?= $row['id_card'] ?></td>
                                                <td><?= getDataDesc('first_name', 'tb_people', 'id = ' . $row['people_id']) . ' ' . getDataDesc('last_name', 'tb_people', 'id = ' . $row['people_id']) ?></td> 
                                                <td><?= $row['booking_date'] ?></td>
                                                <td class="center"><?= $row['status'] ?></td>
                                                <td class="center"><?= $row['updated_at'] ?></td>
                                                <td class="center "><a href="<?= ADDRESS ?>booking_edit&id=<?= $row['id'] ?>" class="btn btn-primary btn-small">แก้ไข / ดู</a> 
                                                    <a href="javascript:;" onclick="if (confirm('คุณต้องลบการจองนี้หรือใม่?') == true) {
                                                                        document.location.href = '<?= ADDRESS ?>booking&id=<?= $row['id'] ?>&action=cancel'
                                                                    }" class="btn btn-danger btn-small">ลบการจอง</a>
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
        if (confirm("คุณแน่ใจที่จะลบการจอง?")) {
            $("#frm_booking").submit();
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
