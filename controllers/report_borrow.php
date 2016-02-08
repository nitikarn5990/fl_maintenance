<!-- Latest compiled and minified CSS -->
<link href="./plugins/datepicker/jquery.datepick.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="./plugins/datepicker/jquery.plugin.js"></script>
<script src="./plugins/datepicker/jquery.datepick.js"></script>
<script src="./plugins/datepicker/jquery.datepick-th.js"></script>
<?php
//ยกเลิกการยืม
if ($_GET['action'] == 'cancel' && is_numeric($_GET['id']) && $_GET['id'] != '') {

    if (delete("tb_borrow", "id = " . $_GET['id'])) {
        SetAlert('ลบการยืมสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
        header('location:' . ADDRESS . 'borrow');
        die();
    }
}
//ยกเลิกการยืม(ที่ละหลายแถว)
if (isset($_POST['select_all'])) {
    $all_id = implode(',', $_POST['select_all']);

    if (delete("tb_borrow", "id in(" . $all_id . ")")) {
        SetAlert('ลบการยืมสำเร็จ', 'success'); //แสดงข้อมูลแจ้งเตือนถ้าสำเร็จ
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
        <h1 class="page-header">รายงานการยืมสื่อทัศนวัสดุตามช่วงเวลา</h1>

    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <p id="breadcrumb">
              <a href="<?=ADDRESS?>report_type">รายงาน</a>
            รายงานการยืมสื่อทัศนวัสดุตามช่วงเวลา
        </p>
    </div>
</div>
<form method="POST" action="<?=ADDRESS?>report_borrow">
<div class="row">
    <div class="">
    <label class="col-md-1">ค้นหาตามวันที่ <span class="required">*</span></label>
    <div class="col-md-3">
        <input type="text" class="form-control " id="st_date" name="st_date" value="<?=isset($_POST['st_date']) ? $_POST['st_date'] : '' ?>" readonly="" required="">
    </div>
    <div class="col-md-1 center">ถึง</div>
     <div class="col-md-3">
         <input type="text" class="form-control " id="ed_date" name="ed_date" readonly="" value="<?=isset($_POST['ed_date']) ? $_POST['ed_date'] : '' ?>" required="">
    </div>
    <div class="col-md-3">
        <p><button type="submit" value="ค้นหา" name="btn_submit" class="form-control btn btn-primary f-12">ค้นหา</button></p>
        <p><a href="<?=ADDRESS?>report_borrow" type="button" class="form-control btn btn-danger f-12">ยกเลิก</a></p>
    </div>
    </div>

</div>
</form>

<form action="" method="POST" id="frm_borrow">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    การยืมสื่อทัศนวัสดุ
                </div>
                <div class="panel-toolbar hidden">
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
                                        
                                        <th class="center">รหัสหนังสือ</th>
                                        <th class="center">ประเภท</th>
                                        <th class="center">ชื่อสื่อ</th>
                                        <th class="center">วันที่ยืม</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($_POST['st_date'] != '' && $_POST['ed_date'] != '') {
                                        $sql = "SELECT * FROM tb_borrow_list WHERE borrow_date between '".$_POST['st_date'] . " 00:00:00' and '".$_POST['ed_date'] ." 00:00:00'  ORDER BY media_id DESC";
                                       
                                     }else{
                                         $sql = "SELECT * FROM tb_borrow_list ORDER BY media_id DESC";
                                    }
                                    
                                    $result = mysql_query($sql);

                                    if (mysql_num_rows($result) > 0) {
                                        while ($row = mysql_fetch_assoc($result)) {
                                            $classStatus = '';
                                            
                                            $catID = getDataDesc('category_id', 'tb_media', 'id = ' . $row['media_id']) ;
                                            
                                            ?>
                                            <tr class="<?= $classStatus ?>" >
                                
                                                <td class="center"><?= $row['media_id'] ?></td>
                                                <td class="center"><?= getDataDesc('name', 'tb_category', 'id = ' . $catID)  ?></td> 
                                                <td class="center"><?= getDataDesc('name', 'tb_media', 'id = ' . $row['media_id']) ?></td> 
                                                <td class="center"> <?= $row['borrow_date'] ?></td>
                                   
                                                </td>

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


<script>
    $(function () {
        // $('#transfer_date').datepick();
        $('#st_date').datepick({
            dateFormat: 'yyyy-m-dd'
        });
        $('#ed_date').datepick({
            dateFormat: 'yyyy-m-dd'
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
</style>