<?php
// Генерации ссылки из Excel по артикулу

if(!empty($_GET['UID'])) {
    $product_uid=base64_decode($_GET['UID']);
    $sql="select id from ".$SysValue['base']['table_name2']." where uid=\"$product_uid\" limit 1";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    if(!empty($row['id'])) {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ".$SysValue['dir']['dir']."/shop/UID_".$row['id'].".html");
        exit();
    }
}

header("HTTP/1.1 404 Not Found");
header("Status: 404 Not Found");
exit();
?>