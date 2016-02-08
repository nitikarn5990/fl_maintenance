
<?php
ob_start();
session_start();
include_once './lib/application.php';

if ($_COOKIE['user'] == '') {

    header('location:login.php?page=select_idcard');
     die();

}
if ($_SESSION ['user_id'] != "") {

} else {
     header('location:login.php?page=select_idcard');
     die();
}

?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>ระบบจัดการยืม-คืน อุปกรณ์สื่อทัศนวัสดุ</title>

        <!-- Bootstrap Core CSS -->
        <link href="./bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="./dist/css/dataTables.bootstrap.min.css" rel="stylesheet">




        <!-- MetisMenu CSS -->
        <link href="./bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

        <!-- DataTables CSS -->
        <link href="./bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

        <!-- DataTables Responsive CSS -->
        <link href="./bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

        <!-- Timeline CSS -->
        <link href="./dist/css/timeline.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="./dist/css/sb-admin-2.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="./bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">



        <script src="./dist/js/jquery.min.js"></script>
        <script src="./dist/js/jquery.validate.min.js"></script>
        <link href="dist/css/custom.css" rel="stylesheet" type="text/css">
        <SCRIPT LANGUAGE="JavaScript">

            function pick_borrow(idcard,id) {
                if (window.opener && !window.opener.closed) {
                  //  window.opener.document.stockForm.stockBox.value = symbol;
                    window.opener.document.getElementById('id_card').value = idcard ;
                      window.opener.document.getElementById('id').value = id ;
                     window.opener.loadBooking(id);
                    window.close();
                    

                }
            }
               function pick_booking(idcard,id) {
                if (window.opener && !window.opener.closed) {
                  //  window.opener.document.stockForm.stockBox.value = symbol;
                    window.opener.document.getElementById('id_card').value = idcard ;
                      window.opener.document.getElementById('id').value = id ;
                   
                    window.close();
                    

                }
            }

        </SCRIPT>
    </head>

    <body> 
        <div id="wrapper">
            <div id="" style="padding: 15px;">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">เลือกข้อมูลประชาชน</h1>

                    </div>
                    <!-- /.col-lg-12 -->
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                ข้อมูลประชาชน
                            </div>

                            <!-- /.panel-heading -->
                            <div class="panel-body" style="padding-top: 15px;">
                                <div class="table-responsive">
                                    <div class="dataTable_wrapper">
                                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                            <thead>
                                                <tr>

                                                    <th>รหัสบัตรประชาชน</th>
                                                    <th>ชื่อ-สกุล</th>
                                                    <th>Email</th>
                                                    <th>เบอร์ติดต่อ</th>
                                                    <th>แก้ไขล่าสุด</th>
                                                    <th>ตัวเลือก</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT * FROM tb_people";
                                                $result = mysql_query($sql);

                                                if (mysql_num_rows($result) > 0) {
                                                    while ($row = mysql_fetch_assoc($result)) {
                                                        ?>
                                                        <tr class="">

                                                            <td class="center"><?= $row['id_card'] ?></td>
                                                            <td><?= $row['first_name'] . '  ' . $row['last_name'] ?></td>
                                                            <td><?= $row['email'] ?></td>
                                                            <td><?= $row['tel'] ?></td>

                                                            <td class="center"><?= ShowDateThTime($row['updated_at']) ?></td>
                                                            <?php if ($_GET['type'] == 'booking') { ?>
                                                                     <td class="center "><a href="javascript:;" onclick="pick_booking('<?=$row['id_card']?>','<?=$row['id']?>')" class="btn btn-primary btn-small">เลือก</a>    
                                                             <?php }?>
                                                             <?php if ($_GET['type'] == 'borrow') { ?>
                                                                       <td class="center "><a href="javascript:;" onclick="pick_borrow('<?=$row['id_card']?>','<?=$row['id']?>')" class="btn btn-primary btn-small">เลือก</a>    
                                                           <?php }?>
                                                            

                                                            </td>
                                                        </tr>


                                                        <?php
                                                    }
                                                }
                                                ?>



                                            </tbody>
                                        </table>
                                        <div class="row" style="margin-bottom: 20px;">

                                           

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
            </div>
        </div>
        <!-- Bootstrap Core JavaScript -->
        <script src="dist/js/jquery.dataTables.min.js"></script>
        <script src="dist/js/dataTables.bootstrap.min.js"></script>

        <script src="./bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- Metis Menu Plugin JavaScript -->
        <script src="./bower_components/metisMenu/dist/metisMenu.min.js"></script>

        <!-- Morris Charts JavaScript -->
        <script src="./bower_components/raphael/raphael-min.js"></script>
        <script src="./bower_components/morrisjs/morris.min.js"></script>
        <script src="./js/morris-data.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="./dist/js/sb-admin-2.js"></script>
        <script>

               $('#dataTables-example').dataTable({
                   "aoColumnDefs": [{"bSortable": false, "aTargets": []},
                   ]
               });

               // Setup - add a text input to each header cell
               var k = 0;
               $('#dataTables-example thead th').each(function () {
                   var title = $('#dataTables-example thead th').eq($(this).index()).text();
                   if (k === 0) {

                   } else {
                       $(this).html('<input type="text" placeholder="' + title + '" />');
                   }



                   k++;
               });

               // DataTable
               var table = $('#dataTables-example').DataTable();

               // Apply the search
               table.columns().eq(0).each(function (colIdx) {
                   $('input', table.column(colIdx).header()).on('keyup change', function () {
                       table
                               .column(colIdx)
                               .search(this.value)
                               .draw();
                   });

                   $('input', table.column(colIdx).header()).on('click', function (e) {
                       e.stopPropagation();
                   });
               });



        </script>
        <style>
            tr{
                font-size: 12px;
            }
        </style>



    </body>

</html>
