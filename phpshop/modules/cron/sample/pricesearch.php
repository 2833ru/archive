<?php

/**
 * Расчет цены для сортировки по прайсу среди мультивалютных товаров
 */
// Включение [true/false]
$enabled = false;


// Авторизация
if (empty($enabled))
    exit("Ошибка авторизации!");

$_classPath = "../../../";
$SysValue = parse_ini_file($_classPath . "inc/config.ini", 1);


// MySQL hostname
$host = $SysValue['connect']['host'];
//MySQL basename
$dbname = $SysValue['connect']['dbase'];
// MySQL user
$uname = $SysValue['connect']['user_db'];
// MySQL password
$upass = $SysValue['connect']['pass_db'];

$con = @mysql_connect($host, $uname, $upass) or die("Could not connect");
mysql_select_db($dbname, $con) or die("Could not select db");
$sql = "select * from " . $SysValue['base']['currency'];
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
    if (empty($row['kurs']))
        $row['kurs'] = 1;
    mysql_query("update phpshop_products set price_search=price/" . $row['kurs'] . " where baseinputvaluta=" . $row['id']) or die(mysql_error());
}

echo "Выполнено";
?>