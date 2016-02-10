<?php

include_once '../lib/application.php';



$sql = "SELECT * FROM tb_computer WHERE id in(" . $_GET['id'] . ")";
$result = mysql_query($sql);
$numRow = mysql_num_rows($result);
if ($numRow > 0) {
    $output = "";
    $order = 0;

    $output .= "<form method='POST' id='frm_media' action=''><div >
         <table class='table'>
                <thead>
                <tr>
                    <th class='hidden'></th>
              
                   <th class='center'>รหัส</th>
                   <th class='center'>ภาพ</th>
                    <th class='center'>แจ้งปัญหา</th>
                    <th class='center'>ตัวเลือก</th>
                </thead>
                <tbody>";


    while ($row = mysql_fetch_assoc($result)) {
        $id = $row['id'];
        //   $image = '..\\dist\\images\\media\\' .$row['image'];
        $image = './dist/images/media/' . $row['image'];
        $_ID = $row['id'];
        $order++;


        $output .= "<tr>
                    <td class='hidden'><input type='hidden' name='_media_id[]' value='$_ID'></td>
                
                    <td class='center'>$id</td>
                    <td class='center'><img src='$image' class='img-responsive' style='width: 100px;margin:auto;'></td>
                       <td class='center'><textarea rows='5' name='problem_description[]' required class='form-control'></textarea></td>
<td class='center'><a href='javascript:;' onclick='_submit($_ID);'  class='btn btn-sm btn-danger'>ลบ</a></td>
                
        </tr>";
    }
    $output .= "</tbody>
            </table>
        </div></form>";
}


echo $output;

