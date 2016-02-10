<!-- Latest compiled and minified CSS -->
<link href="./plugins/datepicker/jquery.datepick.css" rel="stylesheet">

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
            ติดตามปัญหาการแจ้งซ่อมคอมพิวเตอร์
        </h1>

    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row hidden">
    <div class="col-lg-12">
        <p id="breadcrumb">
            <a href="<?= ADDRESS ?>report_type">รายงาน</a>

            ติดตามปัญหาการแจ้งซ่อมคอมพิวเตอร์
        </p>
    </div>
</div>
<form id="s_data" method="POST" action="<?= ADDRESS ?>repair_user">
    <div class="row">
        <div class="">
            <div class="col-md-9">
                <div class="row" style="margin-bottom: 15px;">
                    <label class="col-md-2">รหัสการแจ้ง <span class="required">*</span></label>
                    <div class="col-md-10">
                        <input type="text" class="form-control " id="" name="repair_id"  value="<?= isset($_POST['repair_id']) ? $_POST['repair_id'] : '' ?>"  >
                        <p class="help-block"></p>
                    </div>

                </div>
                <div class="row" style="margin-bottom: 15px;">
                    <label class="col-md-2">วันที่แจ้ง <span class="required">*</span></label>
                    <div class="col-md-10">
                        <input type="text" class="form-control " id="st_date" name="st_date"  value="<?= isset($_POST['st_date']) ? $_POST['st_date'] : '' ?>" >
                    </div>

                </div>

            </div>
            <div class="col-md-3">
                <div class="col-md-12">
                    <p><button type="submit" value="ค้นหา" name="btn_submit" class="form-control btn btn-primary f-12">ค้นหา</button></p>
                    <p><a href="<?= ADDRESS ?>repair_user" type="button" class="form-control btn btn-danger f-12">ยกเลิก</a></p>
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
                    ติดตามปัญหาการแจ้งซ่อมคอมพิวเตอร์
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
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th class="center hidden"></th>
                                        <th>รหัสการแจ้งซ่อม</th>
                                        <th>ชื่อผู้แจ้ง</th>
                                        <th>จำนวนรายการ</th>
                                        <th>รหัสอุปกรณ์ | สถานะ</th>
                                        <th>วันที่แจ้งซ่อม</th>
                                   
                                        <th>ตัวเลือก</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($_POST['repair_id'] != '' && $_POST['st_date'] != '') {

                                        $sql_repair_id = " WHERE id = " . $_POST['repair_id'];
                                        $sql_date = " AND created_at between '" . $_POST['st_date'] . " 00:00:00' AND '" . $_POST['st_date'] . " 23:59:59'";

                                        $sql = "SELECT * FROM tb_repair " . $sql_repair_id . $sql_date;
                                    } else {
                                        if ($_POST['repair_id'] == '' && $_POST['st_date'] == '') {
                                            $sql = "SELECT * FROM tb_repair WHERE id = -1";
                                        } else {
                                            //ค้นหาตาม รหัสการแจ้ง
                                            if ($_POST['repair_id'] != '') {
                                                $sql_repair_id = " WHERE id = " . $_POST['repair_id'];
                                            }

                                            //ค้นหาตามวันที่แจ้ง
                                            if ($_POST['st_date'] != '') {
                                                $sql_date = " WHERE created_at between '" . $_POST['st_date'] . " 00:00:00' AND '" . $_POST['st_date'] . " 23:59:59'";
                                            }

                                            $sql = "SELECT * FROM tb_repair " . $sql_repair_id . $sql_date;
                                        }
                                    }

                                    $result = mysql_query($sql);

                                    $targetPath = dirname($_SERVER['PHP_SELF']) . '/dist/images/media/';

                                    //  echo $sql;

                                    if (mysql_num_rows($result) > 0) {
                                        while ($row = mysql_fetch_assoc($result)) {
                                            ?>
                                            <tr class="">
                                                <td class="center hidden"> <input type="checkbox" name="select_all[]" class="checkboxes" value="<?= $row['id'] ?>" onclick="countSelect()"></td>
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
                                              
                                                <td class="center "><a href="<?= ADDRESS ?>repair_edit_user&id=<?= $row['id'] ?>" class="btn btn-primary btn-small">แก้ไข / ดู</a> <a href="javascript:;" onclick="if (confirm('คุณต้องการลบข้อมูลนี้หรือใม่?') == true) {
                                                                    document.location.href = '<?= ADDRESS ?>repair&id=<?= $row['id'] ?>&action=del'
                                                                }" class="btn btn-danger btn-small hidden">ลบ</a></td>
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
    $('#s_data').validate({
        rules: {
            repair_id: {
                number: true,
            },
        },
        messages: {
            repair_id: {
                number: 'กรุณาระบุเป็นตัวเลข',
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


<script>
    $(function () {
        // $('#transfer_date').datepick();
        $('#st_date').datepick({
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
    .help-block{
        color: red;
    }
</style>