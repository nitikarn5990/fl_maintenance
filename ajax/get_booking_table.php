<?php

include_once '../lib/application.php';


if ($_GET['id'] != '') {

    $output = '';

    $order = 0;
//    if ($numRow > 0) {
//        $output .= "<form method='POST' id='frm_media' action=''><div class=datagrid>
//         <table>
//                <thead>
//                <tr>
//                    <th class='hidden'></th>
//                   <th class='center'>ลำดับ</th>
//                   <th>booking_date</th>
//              
//                    <th class='center'>ตัวเลือก</th>
//                </thead>
//                <tbody>";
//
//
//        $row = mysql_fetch_assoc($result);
//
//        $booking_date = $row['booking_date'];
//        $_ID = $row['id'];
//        $order++;
//        $output .= "<tr>
//                    <td class='hidden'><input type='hidden' name='_media_id[]' value='$_ID'></td>
//                    <td class='center'>$order</td>
//                    <td>$booking_date</td>
//               
//                     <td class='center'><a href='javascript:;' onclick='_submit($_ID);'  class='btn btn-sm btn-danger'>ลบ</a></td>
//                </tr>";
//
//        $output .= "</tbody>
//            </table>
//        </div></form>";
//    }

    $booking_id = getDataDesc('id', 'tb_booking', 'status = "จองอยู่" AND people_id =' . $_GET['id']);
    if ($booking_id != '') {



        $sql_list = "SELECT * FROM tb_booking_list WHERE booking_id in(" . $booking_id . ")";
        $result2 = mysql_query($sql_list);
        $numRow2 = mysql_num_rows($result2);

        if ($numRow2 > 0) {
            $output .= "<form method='POST' id='frm_media' action=''><div class=datagrid>
         <table>
                <thead>
                <tr>
                    <th class='hidden'></th>
                    <th>ลำดับ</th>
                   <th>รหัสสื่อ</th>
                   <th>ชื่อสื่อทัศนวัสดุ</th>
            
                    <th class='center'>ตัวเลือก</th>
                </thead>
                <tbody>";

            while ($row2 = mysql_fetch_assoc($result2)) {
                $mediaName = getDataDesc('name', 'tb_media', 'id=' . $row2['media_id']);
                $mediaID = $row2['media_id'];
                $allMediaID .= ',' . $row2['media_id'];
                $order++;
//           $check_media_id_null = getDataDesc('media_id', 'tb_booking_list', 'booking_id ='.$booking_id.' AND media_id ='.$mediaID);
//           $booked = 'highlight';
//           if ($check_media_id_null == '') {
//               $booked = '';
//           }
                $output .= "<tr class=''>
                    <td class='hidden'><input type='hidden' name='_media_id[]' value='$mediaID'></td>
                    <td>$order</td>   
                    <td><span class='mID'>$mediaID</span></td>   
                    <td>$mediaName</td>
                     <td class='center'><a href='javascript:;' onclick='_submit($mediaID);'  class='btn btn-sm btn-danger'>ลบ</a></td>
                </tr>";
            }

            $output .= "</tbody>
            </table>
        </div></form>";
        }

        $booking_date = getDataDesc('booking_date', 'tb_booking', 'people_id =' . $_GET['id']);
        $date_booking = "<p class='date_booking'>วันที่ได้ทำการจอง : $booking_date</p>";

        $arr = array(
            'html' => $date_booking . $output,
            'media_id' => substr($allMediaID, 1),
            'id_booking' => $booking_id
        );
        echo json_encode($arr);
    }
}
