
<?php
//ยกเลิกการคืน
if ($_GET['action'] == 'delete' && is_numeric($_GET['id']) && $_GET['id'] != '') {

    
    $data = array(
        "status" => 'ยืม', // สถานะ
    );

    if (update("tb_borrow", $data, "id = " . $_GET['id'])) {
        $data = array(
            "status" => 'ยืม', // สถานะ
        );
        if (update("tb_borrow_list", $data, "borrow_id = " . $_GET['id'])) {

            SetAlert('ยกเลิกการคืนสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
            header('location:' . ADDRESS . 'return');
            die();
        }
    }

}
//ยกเลิกการคืน(ที่ละหลายแถว)
if (isset($_POST['select_all'])) {
    $all_id = implode(',', $_POST['select_all']);

    if (delete("tb_borrow", "id in(" . $all_id . ")")) {
        SetAlert('ลบการคืนสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
        header('location:' . ADDRESS . 'borrow');
        die();
    }

}

// แสดงการแจ้งเตือน
Alert(GetAlert('error'));

Alert(GetAlert('success'), 'success');
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">จัดการคืนสื่อทัศนวัสดุ</h1>

    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <p id="breadcrumb">
            ข้อมูลการคืนทั้งหมด
        </p>
    </div>
</div>
<form action="" method="POST" id="frm_borrow">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    การคืนสื่อทัศนวัสดุ
                </div>
                <div class="panel-toolbar">
                    <div class="btn-group"> 
                        <a class="btn" href="<?= ADDRESS ?>borrow"><i class="icol-add"></i> เพิ่มการคืน</a> 

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
                                        <th>รหัสการคืน</th>
                                        <th>รหัสบัตรประชาชน</th>
                                        <th>ชื่อ-สกุลผู้ยืม</th>
                                        <th>วันที่ยืม</th>
                                        <th>สถานะ</th>
                                        <th>แก้ไขล่าสุด</th>
                                        <th>ตัวเลือก</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM tb_borrow WHERE status = 'คืนแล้ว' ORDER BY id DESC";
                                    $result = mysql_query($sql);

                                    if (mysql_num_rows($result) > 0) {
                                        while ($row = mysql_fetch_assoc($result)) {
                                            $classStatus = 'success';

                                            //แจ้งสถานะถ้าคืนยังไม่ครบ
                                            if (getDataCount('borrow_id', 'tb_borrow_list', 'borrow_id=' . $row['id'] . ' AND status ="ยืม"') > 0) {
                                                $status = 'คืนยังไม่ครบ';
                                                $classStatus = 'warning';
                                            } else {
                                                $status = 'คืนครบแล้ว';
                                            }
                                            ?>
                                            <tr class="<?= $classStatus ?>" >
                                                <td class="center"> <input type="checkbox" name="select_all[]" class="checkboxes" value="<?= $row['id'] ?>" onclick="countSelect()"></td>
                                                <td class="center"><?= $row['id'] ?></td>
                                                <td><?= $row['id_card'] ?></td>
                                                <td><?= getDataDesc('first_name', 'tb_people', 'id = ' . $row['people_id']) . ' ' . getDataDesc('last_name', 'tb_people', 'id = ' . $row['people_id']) ?></td> 
                                                <td><?= $row['borrow_date'] ?></td>
                                                <td class="center"><?= $status ?></td>
                                                <td class="center"><?= $row['updated_at'] ?></td>
                                                <td class="center "><a href="<?= ADDRESS ?>return_edit&id=<?= $row['id'] ?>" class="btn btn-primary btn-small">แก้ไข / ดู</a> 

                                                    <a href="javascript:;" onclick="if (confirm('คุณต้องลบการคืนนี้หรือใม่?') == true) {
                                                                        document.location.href = '<?= ADDRESS ?>return&id=<?= $row['id'] ?>&action=delete'
                                                                    }" class="btn btn-danger btn-small">ยกเลิกการคืน</a>
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
        if (confirm("คุณแน่ใจที่จะลบการคืน?")) {
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
