<?php if ($_SESSION['group'] == '' || $_SESSION['group'] == 'ผู้บริหาร') { ?>
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 10px;">
            <p>&nbsp;</p>
            <img src="./dist/images/404.png" class="img-responsive" style="margin: auto;">
        </div>
    </div>
    <?php
} else {
//ตรวจสอบถ้ามีการลบข้อมูล (ลบที่ละแถว)
    if ($_GET['action'] == 'del' && is_numeric($_GET['id']) && $_GET['id'] != '') {


        //เมื่อลบ จะอัพเดรต computer ให้เป็นสถานะ ปกติ
        $sql_com_id = "SELECT * FROM tb_repair_list WHERE repair_id = " . $_GET['id'];
        $result2 = mysql_query($sql_com_id);
        if (mysql_num_rows($result2) > 0) {
            while ($row2 = mysql_fetch_assoc($result2)) {
                $data = array(
                    "status" => 'ปกติ',
                    "updated_at" => DATE_TIME, //
                );
                if (update("tb_computer", $data, 'id = ' . $row2['computer_id'])) {
                    
                }
            }
        }
        if (delete("tb_repair", "id = " . $_GET['id'])) {
            SetAlert('ลบข้อมูลสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
            header('location:' . ADDRESS . 'repair');
            die();
        }
    }
//ตรวจสอบถ้ามีการลบข้อมูล (ลบที่ละหลายแถว)
    if (isset($_POST['select_all'])) {
        $all_id = implode(',', $_POST['select_all']);

        //เมื่อลบ จะอัพเดรต computer ให้เป็นสถานะ ปกติ
        foreach ($_POST['select_all'] as $repair_id) {

            $sql_com_id = "SELECT * FROM tb_repair_list WHERE repair_id = " . $repair_id;
            $result2 = mysql_query($sql_com_id);
            if (mysql_num_rows($result2) > 0) {
                while ($row2 = mysql_fetch_assoc($result2)) {
                    $data = array(
                        "status" => 'ปกติ',
                        "updated_at" => DATE_TIME, //
                    );
                    if (update("tb_computer", $data, 'id = ' . $row2['computer_id'])) {
                        
                    }
                }
            }
        }
        ////


        if (delete("tb_repair", "id in(" . $all_id . ")")) {
            SetAlert('ลบข้อมูลสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
            header('location:' . ADDRESS . 'repair');
            die();
        }
    }

// แสดงการแจ้งเตือน
    Alert(GetAlert('error'));

    Alert(GetAlert('success'), 'success');
    ?>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">จัดการข้อมูลการแจ้งซ่อม</h1>

        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <p id="breadcrumb">
                จัดการข้อมูลการแจ้งซ่อม
            </p>
        </div>
    </div>
    <form action="" method="POST" id="frm_computer">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        ข้อมูลการแจ้งซ่อม 
                    </div>
                    <div class="panel-toolbar">
                        <div class="btn-group"> 
                            <a class="btn" href="<?= ADDRESS ?>repair_add"><i class="icol-add"></i> เพิ่มข้อมูล</a> 

                            <a href="javascript:;" onclick="frm_submit()" class="btn" id="btn-select-delete" ><i class="icol-cross"></i> ลบที่เลือก</a> 
                        </div>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body" style="padding-top: 15px;">
                        <div class="table-responsive">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example" style="width: 1000px;">
                                    <thead>
                                        <tr>
                                            <th class="center"></th>
                                            <th style="width: 15%;">รหัสแจ้งซ่อม</th>
                                            <th>ชื่อผู้แจ้ง</th>
                                            <th>จำนวนรายการ</th>
                                            <th>รหัสอุปกรณ์ | สถานะ</th>
                                            <th>วันเวลาที่แจ้งซ่อม</th>
                                             <th>ผู้ดำเนินการ</th>
                                            <th>ตัวเลือก</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM tb_repair";
                                        $result = mysql_query($sql);

                                        $targetPath = dirname($_SERVER['PHP_SELF']) . '/dist/images/media/';

                                        if (mysql_num_rows($result) > 0) {
                                            while ($row = mysql_fetch_assoc($result)) {
                                                ?>
                                                <tr class="">
                                                    <td class="center"> <input type="checkbox" name="select_all[]" class="checkboxes" value="<?= $row['id'] ?>" onclick="countSelect()"></td>
                                                    <td class="center"><?= $row['id'] ?></td>
                                                    <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
                                                    <td class="center"><?= getDataCount('repair_id', 'tb_repair_list', 'repair_id = ' . $row['id']) ?></td>

                                                    <td class="center">
                                                        <div class="">

                                                            <?php
                                                            $arr_com_id = arr_getDataDesc('computer_id', 'tb_repair_list', 'repair_id = ' . $row['id']);

                                                            foreach ($arr_com_id as $value) {
                                                                if (getDataDesc('status', 'tb_repair_list', 'repair_id = ' . $row['id'] . ' AND computer_id = ' . $value) == '') {
                                                                    $status = 'แจ้งซ่อม';
                                                                } else {
                                                                    $status = getDataDesc('status', 'tb_repair_list', 'repair_id = ' . $row['id'] . ' AND computer_id = ' . $value);
                                                                }
                                                                ?>
                                                                <p class="badge "><?= $value . ' | ' . $status ?></p><br>
                                                            <?php }
                                                            ?>
                                                        </div>
                                                    </td>
                                                    <td class="center"><?= ShowDate($row['created_at']) ?></td>
                                                    <td class="center"><?= getDataDesc('first_name','tb_staff','id= '.$row['staff_id']) ?></td>
                                                    <td class="center "><a href="<?= ADDRESS ?>repair_edit&id=<?= $row['id'] ?>" class="btn btn-primary btn-small">แก้ไข / ดู</a> <a href="javascript:;" onclick="if (confirm('คุณต้องการลบข้อมูลนี้หรือใม่?') == true) {
                                                                            document.location.href = '<?= ADDRESS ?>repair&id=<?= $row['id'] ?>&action=del'
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
                $("#frm_computer").submit();
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
    <?php
}?>