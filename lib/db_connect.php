<?php

$username = "root"; //username 
$password = ""; //password
$db_name = "db_maintenance"; //ชื่อฐานข้อมูล
$hostname = "localhost"; 

//เชื่อมต่อฐานข้อมูล MySQL
$dbhandle = mysql_connect($hostname, $username, $password)
        or die("ไม่สามารถติดต่อ MySQL");

//เลือกฐานข้อมูล
$selected = mysql_select_db($db_name, $dbhandle)
        or die("ไม่สามารถติดต่อฐานข้อมูลได้");

$cs1 = "SET character_set_results=utf8";
mysql_query($cs1) or die('Error query: ' . mysql_error());

$cs2 = "SET character_set_client = utf8";
mysql_query($cs2) or die('Error query: ' . mysql_error());

$cs3 = "SET character_set_connection = utf8";
mysql_query($cs3) or die('Error query: ' . mysql_error());

function select($sql) {
    $result = array();
    $req = mysql_query($sql) or die("SQL Error: <br>" . $sql . "<br>" . mysql_error());
    while ($data = mysql_fetch_assoc($req)) {
        $result[] = $data;
    }
    return $result;
}

function getDataDesc($myID, $myTable, $myWhere) {


    if ($myWhere != "") {
        $SqlWhere = " WHERE " . $myWhere;
    }

    if ($myID != "") {
        $sql = "SELECT $myID FROM " . $myTable . $SqlWhere;
        $Query = mysql_query($sql);
        $RecordCount = mysql_num_rows($Query);

        if ($RecordCount > 0) {
            $Row = mysql_fetch_assoc($Query);
            return($Row["$myID"]);
        } else {
            return("");
        }
    } else {
        return("");
    }
}

function getDataCount($myID, $myTable, $myWhere) {


    if ($myWhere != "") {
        $SqlWhere = " WHERE " . $myWhere;
    }

    if ($myID != "") {
        $sql = "SELECT $myID FROM " . $myTable . $SqlWhere;
        $Query = mysql_query($sql);
        $RecordCount = mysql_num_rows($Query);

        return $RecordCount;
    } else {
        return("");
    }
}

function getDataDescLastID($myID, $myTable) {


    if ($myWhere != "") {
        $SqlWhere = " WHERE " . $myWhere;
    }

    if ($myID != "") {
        $sql = "SELECT $myID FROM " . $myTable . $SqlWhere . ' ORDER BY id DESC LIMIT 1';
        $Query = mysql_query($sql);
        $RecordCount = mysql_num_rows($Query);

        if ($RecordCount > 0) {
            $Row = mysql_fetch_assoc($Query);
            return($Row["$myID"]);
        } else {
            return("");
        }
    } else {
        return("");
    }
}

// the where clause is left optional incase the user wants to delete every row!
function delete($table, $where) {
    $sql = "DELETE FROM $table WHERE $where";
    if (mysql_query($sql)) {
        return true;
    } else {
        die("SQL Error: <br>" . $sql . "<br>" . mysql_error());
        return false;
    }
}

function insert($table, $data) {
    $fields = "";
    $values = "";
    $i = 1;
    foreach ($data as $key => $val) {
        if ($i != 1) {
            $fields.=", ";
            $values.=", ";
        }
        $fields.="$key";
        $values.="'$val'";
        $i++;
    }
    $sql = "INSERT INTO $table ($fields) VALUES ($values)";
    if (mysql_query($sql)) {
        return true;
    } else {
        die("SQL Error: <br>" . $sql . "<br>" . mysql_error());
        return false;
    }
}

function update($table, $data, $where) {
    $modifs = "";
    $i = 1;
    foreach ($data as $key => $val) {
        if ($i != 1) {
            $modifs.=", ";
        }
        // if (is_numeric($val)) {
        //    $modifs.=$key . '=' . $val;
        // } else {
        $modifs.=$key . ' = "' . $val . '"';
        // }
        $i++;
    }
    $sql = ("UPDATE $table SET $modifs WHERE $where");
    if (mysql_query($sql)) {
        return true;
    } else {
        die("SQL Error: <br>" . $sql . "<br>" . mysql_error());
        return false;
    }
}

function get_enum($table, $field) {
    $query = " SHOW COLUMNS FROM `$table` LIKE '$field' ";
    $result = mysql_query($query) or die('error getting enum field ' . mysql_error());
    $row = mysql_fetch_array($result);
#extract the values
#the values are enclosed in single quotes
#and separated by commas
    $regex = "/'(.*?)'/";
    preg_match_all($regex, $row[1], $enum_array);
    $enum_fields = $enum_array[1];
    return( $enum_fields );
}

function enumDropdown($table_name, $column_name, $echo = false) {
    $selectDropdown = "<select name=\"$column_name\">";
    $result = mysql_query("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
       WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME = '$column_name'")
            or die(mysql_error());

    $row = mysql_fetch_array($result);
    $enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE']) - 6))));

    foreach ($enumList as $value)
        $selectDropdown .= "<option value=\"$value\">$value</option>";

    $selectDropdown .= "</select>";

    if ($echo)
        echo $selectDropdown;

    return $selectDropdown;
}
