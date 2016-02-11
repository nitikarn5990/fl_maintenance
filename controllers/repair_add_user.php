
<?php
if ($_POST['btn_submit'] == 'บันทึกข้อมูล') { //เช็คว่ามีการกดปุ่ม บันทึกข้อมูล
    $c = 0;
    foreach ($_POST['problem_description'] as $problem_description) {
        if (trim($problem_description) == '') {
            $c = $c + 1;
        }
    }

    if ($_POST['media_id'] == '' || $c > 0) {
        if ($_POST['media_id'] = '') {
            SetAlert('กรุณาเลือกคอมพิวเตอร์ที่ต้องการแจ้งซ่อม'); //แสดงข้อมูลแจ้งเตือน
        }
        if ($c > 0) {
            SetAlert('กรุณาใส่ปัญหา'); //แสดงข้อมูลแจ้งเตือน
        }
    } else {
        //ถ้าว่างทำส่วนนี้ คือ เพิม่ลงฐานข้อมูล
        $data = array(
            "first_name" => $_POST['first_name'],
            "last_name" => $_POST['last_name'],
            "staff_id" => $_SESSION['user_id'],
            // "problem_description" => $_POST['problem_description'],
            "created_at" => DATE_TIME, //
            "updated_at" => DATE_TIME, //
        );

// insert ข้อมูลลงในตาราง tb_repair โดยฃื่อฟิลด์ และค่าตามตัวแปร array ชื่อ $data
        if (insert("tb_repair", $data)) { // บันทึกข้อมูลลงตาราง tb_repair 
            if ($_POST['media_id'] != '') { //ถ้ามีรหัสคอมพิวเตอร์
                $arrIDMedia = explode(',', $_POST['media_id']);

                $repair_id = getDataDescLastID("id", 'tb_repair');
                foreach ($arrIDMedia as $key => $value) {
                    $data = array(
                        "repair_id" => $repair_id, //รหัสการแจ้ง
                        "computer_id" => $value, //รหัสคอมพิวเตอร์
                        "status" => '', //
                        "problem_description" => $_POST['problem_description'][$key],
                        "created_at" => DATE_TIME, 
                        "updated_at" => DATE_TIME, 
                        "date_input" => DATE_TIME, 
                    );
                    insert("tb_repair_list", $data);

                    // อัพเดรต status คอมพิวเตอร์หากมีการแจ้งปัญหา
                    $dataStatusCpt = array(
                        "status" => 'ส่งซ่อม', 
                        "updated_at" => DATE_TIME, 
                    );
                    update("tb_computer", $dataStatusCpt, 'id = '.$value);
                }
            }
            
            SetAlert('เพิ่ม แก้ไข ข้อมูลสำเร็จ <br><br> <b>รหัสการแจ้งซ่อม : '.getDataDescLastID('id', 'tb_repair') .'<br> วันที่แจ้งซ่อม : '.ShowDate(getDataDescLastID('created_at', 'tb_repair')) .'</b>', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
            header('location:' . ADDRESS . 'repair_add_user');
            die();
        }
    }
}


//ลบคอมพิวเตอร์
if ($_POST['media_id'] != '') {

    $all_id = '';

    $arrr = explode(',', $_POST['media_id']);

    foreach ($arrr as $v) {
        if ($_POST['delete_id'] != $v) {
            $all_id .= ',' . $v;
        }
    }
    $all_id = substr($all_id, 1);
}

// แสดงการแจ้งเตือน
Alert(GetAlert('error'));

Alert(GetAlert('success'), 'success');
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">เพิ่มข้อมูลการแจ้ง</h1>

    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row hidden">
    <div class="col-lg-12">
        <p id="breadcrumb">
            <a class="" href="<?= ADDRESS ?>repair">ข้อมูลการแจ้งทั้งหมด</a>
            เพิ่มข้อมูล
        </p>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="icol-add"></i> เพิ่มข้อมูล
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <form role="form" id="frm_repair" action="<?= ADDRESS ?>repair_add_user" method="POST">

                            <div class="row da-form-row">
                                <label class="col-md-2">ชื่อ ผู้แจ้ง <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="first_name" value="<?= isset($_POST['first_name']) ? $_POST['first_name'] : '' ?>" required="">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row da-form-row">
                                <label class="col-md-2">นามสกุล ผู้แจ้ง <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="last_name" value="<?= isset($_POST['last_name']) ? $_POST['last_name'] : '' ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row da-form-row">
                                <label class="col-md-2">เบอร์ติดต่อ <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="tel" value="<?= isset($_POST['tel']) ? $_POST['tel'] : '' ?>">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row da-form-row">
                                <label class="col-md-2">เลือกคอมพิวเตอร์ที่มีปัญหา <span class="required">*</span></label>
                                <div class="col-md-8">
                                    <input type="hidden" name="delete_id" id="delete_id">

<?php if ($all_id != '') { ?>
                                        <input class="form-control input-sm " name="media_id" id="media_id" type="hidden"   value="<?= $all_id ?>">
                                    <?php } else { ?>
                                        <input class="form-control input-sm " name="media_id" id="media_id" type="hidden"  value="<?= $all_id ?>">

                                    <?php } ?>

                                    <p class="help-block"></p>
                                    <div id="table_computer_list"></div>
                                </div>
                                <div class="col-md-2">
                                    <a href="javascript:;" onclick="showMediaList()" class="btn btn-sm btn-primary">เลือก</a>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="">
                                    <div class="btn-row">
                                        <button type="submit" name="btn_submit" value="บันทึกข้อมูล" class="btn btn-sm btn-success">บันทึกข้อมูล</button>
                                        <button type="reset" class="btn btn-sm btn-danger hidden">ยกเลิก</button>
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
<SCRIPT LANGUAGE="JavaScript">
    $(document).ready(function () {

        if ($('#media_id').val() != '') {
            $.ajax({
                method: "GET",
                url: "./ajax/get_computer_table.php",
                data: {id: $('#media_id').val()}
            }).success(function (html) {
                //alert($('#media_id').val());
                $('#table_computer_list').html(html);
            });
        }
    });

    function _submit(delete_id) {
        $("#delete_id").val(delete_id);

        $("form").submit();


    }



    function showList() {
        var sList = PopupCenter("select_idcard.php?type=repair", "list", "900", "400");

    }
    function showMediaList() {


        var ID = $('#media_id').val();
        var sList = PopupCenter("computer_list.php?media_id=" + ID, "list", "900", "400");

    }

    function PopupCenter(url, title, w, h) {
        // Fixes dual-screen position Most browsers      Firefox
        var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
        var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

        width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        var left = ((width / 2) - (w / 2)) + dualScreenLeft;
        var top = ((height / 2) - (h / 2)) + dualScreenTop;
        var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        // Puts focus on the newWindow
        if (window.focus) {
            newWindow.focus();
        }
    }

</SCRIPT>
<script>
    $('#frm_repair').validate({
        rules: {
            first_name: {
                required: true,
            },
            last_name: {
                required: true,
            },
            tel: {
                required: true,
                number: true,
            },
            problem_description: {
                required: true,
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

<style>
    .datagrid table { border-collapse: collapse; text-align: left; width: 100%; } .datagrid {font: normal 12px/150% Arial, Helvetica, sans-serif; background: #fff; overflow: hidden; border: 1px solid #006699; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; }.datagrid table td, .datagrid table th { padding: 3px 9px; }.datagrid table thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; color:#FFFFFF; font-size: 15px; font-weight: bold; border-left: 1px solid #0070A8; } .datagrid table thead th:first-child { border: none; }.datagrid table tbody td { color: #00496B; border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal; }.datagrid table tbody .alt td { background: #E1EEF4; color: #00496B; }.datagrid table tbody td:first-child { border-left: none; }.datagrid table tbody tr:last-child td { border-bottom: none; }
</style>