<?php

/**
 * Снятие с продаж товара с датой изменения менее Х дней
 */

// Включение [true/false]
$enabled = false;

// Дни [1-100]
$day=3;

// 1 - убирать с сайта, 2 - под заказ
$option=2;

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

switch($option){
    case 1:
        $sql="enabled='0'";
        break;
    
    case 2:
        $sql="sklad='1'";
    break;

    default: $sql="enabled='0'";
}

mysql_query("update phpshop_products set $sql where datas<".(time()-(86400*$day)));

echo "Выполнено";
?>