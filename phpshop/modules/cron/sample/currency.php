<?php

/**
 * Обновление курсов валют из cbr.ru
 * Для включения поменяйте значение enabled на true
 */
// Включение
$enabled = true;

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
$db = @mysql_select_db($dbname, $con) or die("Could not select db");

$url = "http://www.cbr.ru/scripts/XML_daily.asp";
$curs = $iso = array();

function get_timestamp($date) {
    list($d, $m, $y) = explode('.', $date);
    return mktime(0, 0, 0, $m, $d, $y);
}

$sql = 'select * from `phpshop_valuta`';
$result = mysql_query($sql);
while (@$row = mysql_fetch_array(@$result)) {
    $iso[]=$row['iso'];
}

if (!$xml = simplexml_load_file($url))
    die('XML Error Library');


foreach ($xml->Valute as $m) {
    if(in_array($m->CharCode,$iso)){
        $curs[(string) $m->CharCode] = (float) str_replace(",", ".", 1 / (string) $m->Value);
    }
}

foreach ($curs as $key => $value) {
    $sql = "UPDATE `phpshop_valuta` SET `kurs` = '" . $value . "' WHERE `iso` ='" . $key . "';";
    mysql_query($sql);
}
?>