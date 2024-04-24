<?php
header("Content-type:Application/json");
include_once '../model/operations.php';
$m['status'] = 0;
if(insert_edit_form("places")) {
    $sql = "select * from places order by id desc limit 1";
    $row = select_rows($sql);
    $m['data'] = $row[0];
    $m['status'] = 1;
}
echo json_encode($m);