
<?php
if ($_POST['btn_submit'] == 'บันทึกข้อมูล') { //เช็คว่ามีการกดปุ่ม บันทึกข้อมูล
    if ($_POST['media_id'] == '') {
        SetAlert('กรุณาเลือกสื่อที่ต้องการจอง'); //แสดงข้อมูลแจ้งเตือน
    } else {
        //ถ้าว่างทำส่วนนี้ คือ เพิม่ลงฐานข้อมูล
        $data = array(
            "people_id" => $_POST['id'], //ID สมาชิก
            "id_card" => $_POST['id_card'], //รหัสบัตรประชาชน
           // "booking_date" => DATE, //วันที่จอง
           // "status" => 'จองอยู่', // สถานะ
            "comment" => $_POST['comment'], // สถานะ
           // "created_at" => DATE_TIME, //วันที่บันทึก
            "updated_at" => DATE_TIME, //วันที่แก้ไข
        );

// insert ข้อมูลลงในตาราง tb_booking โดยฃื่อฟิลด์ และค่าตามตัวแปร array ชื่อ $data
        if (update("tb_booking", $data,'id = '.$_GET['id'])) { // บันทึกข้อมูลลงตาราง tb_booking 
            if ($_POST['media_id'] != '') { //ถ้ามีรหัสสื่อ
                
                $arrIDMedia = explode(',', $_POST['media_id']);

                 delete('tb_booking_list', 'booking_id = '.$_GET['id']);
                foreach ($arrIDMedia as $value) {
                    $data = array(
                        "booking_id" => $_GET['id'], //รหัสการจอง
                        "media_id" => $value, //รหัสสื่อ
                        "status" => 'จองอยู่',
                    );
                   
                    insert("tb_booking_list", $data);
                }
            }

            SetAlert('เพิ่ม แก้ไข ข้อมูลสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
            header('location:' . ADDRESS . 'booking');
            die();
        }
    }
}
//ดึงข้อมูลตาม ID จาก $_GET['id'] ตาราง tb_booking
if (isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])) {

    //ดึงข้อมูลตาม  $_GET['id'] ที่รับมา
    $sql = "SELECT * FROM tb_booking WHERE id = " . $_GET['id'];
    $result = mysql_query($sql);
    $num_row = mysql_num_rows($result);
    if ($num_row == 1) {
        $row = mysql_fetch_assoc($result);

        $allID = '';
        $sql2 = "SELECT * FROM tb_booking_list WHERE booking_id = " . $_GET['id'];
        $result2 = mysql_query($sql2);
        $num_row2 = mysql_num_rows($result2);
        if ($num_row2 > 0) {
           
             while ( $row2 = mysql_fetch_assoc($result2)){
                  $allID .= ',' .  $row2['media_id'];
             }
            
         $allID = substr($allID, 1);
         
        }
    }
}


//ลบสื่อ
if ($_POST['media_id'] != '') {

    $allID = '';

    $arrr = explode(',', $_POST['media_id']);

    foreach ($arrr as $v) {
        if ($_POST['delete_id'] != $v) {
            $allID .= ',' . $v;
        }
    }
    $allID = substr($allID, 1);
}

// แสดงการแจ้งเตือน
Alert(GetAlert('error'));

Alert(GetAlert('success'), 'success');
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">แก้ไขข้อมูลการจอง</h1>

    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <p id="breadcrumb">
            <a href="<?= ADDRESS ?>booking">ข้อมูลการจองทั้งหมด</a>
            แก้ไขข้อมูล
        </p>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="icol-add"></i> แก้ไขข้อมูล
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <form role="form" action="<?= ADDRESS ?>booking_edit&id=<?=$_GET['id']?>" method="POST">
                            <div class="row da-form-row">
                                <label class="col-md-2">รหัสบัตรประชาชน <span class="required">*</span></label>
                                <div class="col-md-5">
                                    <input class="form-control input-sm" name="id_card" id="id_card" type="text" readonly="" value="<?= isset($_POST['id_card']) ? $_POST['id_card'] : $row['id_card'] ?>">
                                    <input name="id" id="id" type="hidden" value="<?= isset($_POST['id']) ? $_POST['id'] : $row['people_id'] ?>">
                                    <p class="help-block"></p>
                                </div>
                                <div class="col-md-2">
                                    <a href="javascript:;" onclick="showList()" class="btn btn-sm btn-primary">เลือก</a>
                                </div>
                            </div>
                            <div class="row da-form-row">
                                <label class="col-md-2">สื่อทัศนวัสดุ <span class="required">*</span></label>
                                <div class="col-md-5">
                                    <input type="hidden" name="delete_id" id="delete_id">
                                    <?php if ($allID != '') { ?>
                                        <input class="form-control input-sm " name="media_id" id="media_id" type="hidden"  value="<?= $allID ?>">
                                    <?php } else { ?>
                                        <input class="form-control input-sm " name="media_id" id="media_id" type="hidden"  value="<?= $allID ?>">

                                    <?php } ?>

                                    <p class="help-block"></p>
                                    <div id="table_media_list"></div>
                                </div>
                                <div class="col-md-2">
                                    <a href="javascript:;" onclick="showMediaList()" class="btn btn-sm btn-primary">เลือก</a>
                                </div>
                            </div>

                            <div class="row da-form-row">
                                <label class="col-md-2">หมายเหตุ </label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="comment"><?= isset($row['comment']) ? $row['comment'] : '' ?></textarea>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="">
                                    <div class="btn-row">
                                        <button type="submit" name="btn_submit" value="บันทึกข้อมูล" class="btn btn-sm btn-success">บันทึกข้อมูล</button>
                                        <button type="reset" class="btn btn-sm btn-danger">ยกเลิก</button>
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
                url: "./ajax/get_media_table.php",
                data: {id: $('#media_id').val()}
            }).success(function (html) {

                $('#table_media_list').html(html);
            });
        }
    });

    function _submit(delete_id) {
        $("#delete_id").val(delete_id);

        $("form").submit();


    }



    function showList() {
        var sList = PopupCenter("select_idcard.php", "list", "900", "400");

    }
    function showMediaList() {


        var ID = $('#media_id').val();
        var sList = PopupCenter("media_list.php?media_id=" + ID, "list", "900", "400");

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
    $('form').validate({
        rules: {
            media_id: {
                required: true
            },
            id_card: {
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