
<?php
if ($_POST['btn_submit'] == 'บันทึกข้อมูล') { //เช็คว่ามีการกดปุ่ม บันทึกข้อมูล
    if (isset($_POST['cbox_return'])) {

        foreach ($_POST['cbox_return'] as $media_id_return) {
            $data = array(
                "status" => 'คืน',
                "return_date" => DATE,
            );
            update('tb_borrow_list', $data, 'borrow_id =' . $_GET['id'] . ' AND media_id =' . $media_id_return);
            $sql_find_all_return = 'SELECT * FROM tb_borrow_list WHERE status = "ยืม" AND borrow_id = ' . $_GET['id'] . ' AND media_id =' . $media_id_return;
            $result = mysql_query($sql_find_all_return);
            if (mysql_num_rows($result) == 0) { //ถ้าคืนครบทุกอันให้อัพเดรตสถานะเป็น 'คืนแล้ว'
                $data = array(
                    "status" => 'คืนแล้ว',
                );
                update('tb_borrow', $data, 'id =' . $_GET['id']);
            }
        }
    }




    // $data = array(
    //    "comment_return" => $_POST['comment_return']
    // );
    // update('tb_borrow', $data, 'id =' . $_GET['id']);
}
//ดึงข้อมูลตาม ID จาก $_GET['id'] ตาราง tb_borrow
if (isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])) {

    //ดึงข้อมูลตาม  $_GET['id'] ที่รับมา
    $sql = "SELECT * FROM tb_borrow WHERE id = " . $_GET['id'];
    $result = mysql_query($sql);
    $num_row = mysql_num_rows($result);
    if ($num_row == 1) {
        $row = mysql_fetch_assoc($result);

        $allID = '';
        $sql2 = "SELECT * FROM tb_borrow_list WHERE borrow_id = " . $_GET['id'];
        $result2 = mysql_query($sql2);
        $num_row2 = mysql_num_rows($result2);
        if ($num_row2 > 0) {

            while ($row2 = mysql_fetch_assoc($result2)) {
                $allID .= ',' . $row2['media_id'];
            }

            $allID = substr($allID, 1);
        }
    }





    if ($_POST['media_id_cancelReturn'] != '') {
        //ยกเลิกการคืนสื่อ

        $data = array(
            "status" => 'ยืม',
            "return_date" => '',
        );
        if (update('tb_borrow_list', $data, 'borrow_id =' . $_GET['id'] . ' AND media_id =' . $_POST['media_id_cancelReturn'])) {

            //ถ้าสถานะเป็น ยืมหมด จะไปอยู่ที่รายการยืม แต่ถ้า คืนมาบางส่วนจะอยู่ในส่วน ของรายการคืน
            if (getDataCount('borrow_id', 'tb_borrow_list', 'status="คืน" AND borrow_id=' . $_GET['id']) == 0) {
                $data = array(
                    "status" => 'ยืม',
                );
                update('tb_borrow', $data, 'id =' . $_GET['id']);
            }
        }
        SetAlert('ยกเลิกการคืน สำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
    }
}

// แสดงการแจ้งเตือน
Alert(GetAlert('error'));

Alert(GetAlert('success'), 'success');
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">ข้อมูลการยืม</h1>

    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <p id="breadcrumb">
            <a href="<?= ADDRESS ?>return">ข้อมูลการคืนทั้งหมด</a>
            ทำการคืน
        </p>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="icol-add"></i> ทำการคืน
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <form role="form" action="<?= ADDRESS ?>return_edit&id=<?= $_GET['id'] ?>" method="POST">
                            <input type="hidden" name="borrow_id" id="borrow_id" value="<?= $_GET['id'] ?>">
                            <input type="hidden" name="media_id_cancelReturn" id="media_id_cancelReturn" value="">

                            <div class="row da-form-row">
                                <label class="col-md-2">รหัสบัตรประชาชน <span class="required">*</span></label>
                                <div class="col-md-5">
                                    <input class="form-control input-sm" name="id_card" id="id_card" type="text" readonly="" value="<?= isset($_POST['id_card']) ? $_POST['id_card'] : $row['id_card'] ?>">
                                    <input name="id" id="id" type="hidden" value="<?= isset($_POST['id']) ? $_POST['id'] : $row['people_id'] ?>">
                                    <p class="help-block"></p>
                                </div>

                            </div>

                            <div class="row da-form-row">
                                <label class="col-md-2">สื่อทัศนวัสดุที่ยืม <span class="required">*</span></label>
                                <div class="col-md-10">
                                    <input type="hidden" name="delete_id" id="delete_id">
                                    <?php if ($allID != '') { ?>
                                        <input class="form-control input-sm " name="media_id" id="media_id" type="hidden"  value="<?= $allID ?>">
                                    <?php } else { ?>
                                        <input class="form-control input-sm " name="media_id" id="media_id" type="hidden"  value="<?= $allID ?>">

                                    <?php } ?>

                                    <p class="help-block"></p>
                                    <div id="table_media_list"></div>
                                </div>
                                <div class="col-md-2 hidden">
                                    <a href="javascript:;" onclick="showMediaList()" class="btn btn-sm btn-primary">เลือก</a>
                                </div>
                            </div>
                            <div class="row da-form-row">
                                <label class="col-md-2">รวมค่าปรับ (บาท) <span class="required">*</span></label>
                                <div class="col-md-5">
                                    <input class="form-control input-sm" name="totalAmt" id="totalAmt" type="text" readonly="" value="">

                                    <p class="help-block"></p>
                                </div>

                            </div>

                            <div class="row da-form-row">
                                <label class="col-md-2">หมายเหตุ </label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="comment_return"><?= isset($row['comment_return']) ? $row['comment_return'] : '' ?></textarea>
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
                data: {
                    id: $('#media_id').val(),
                    action: 'return',
                    borrow_id: $('#borrow_id').val()

                }
            }).success(function (html) {
                
               var obj =  $.parseJSON(html)
                $('#table_media_list').html(obj.html);
                $('#totalAmt').val(obj.totalAmt);
            });
        }
    });

    function _submit(delete_id) {
        $("#delete_id").val(delete_id);

        $("form").submit();


    }
    function xx(id) {
        $("#media_id_cancelReturn").val(id);

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
    function chk_all_return() {


        if ($(".chk_all_return").is(':checked')) {
            $(".cbox_return").each(function () {

                $(this).prop("checked", true);
            });
        } else {
            $(".cbox_return").each(function () {

                $(this).prop("checked", false);
            });
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
    .txt-nav-return{

        font-size: 13px;

        padding-top: 5px;
    }
    .datagrid table { border-collapse: collapse; text-align: left; width: 100%; } .datagrid {font: normal 12px/150% Arial, Helvetica, sans-serif; background: #fff; overflow: hidden; border: 1px solid #006699; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; }.datagrid table td, .datagrid table th { padding: 3px 9px; }.datagrid table thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; color:#FFFFFF; font-size: 15px; font-weight: bold; border-left: 1px solid #0070A8; } .datagrid table thead th:first-child { border: none; }.datagrid table tbody td { color: #00496B; border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal; }.datagrid table tbody .alt td { background: #E1EEF4; color: #00496B; }.datagrid table tbody td:first-child { border-left: none; }.datagrid table tbody tr:last-child td { border-bottom: none; }
</style>