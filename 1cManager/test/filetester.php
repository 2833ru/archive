<?php

// Библиотеки
include("../../phpshop/class/obj.class.php");
PHPShopObj::loadClass("readcsv");

$PHPShopReadCsvNative = new PHPShopReadCsvNative('upload_0_090217.csv');
print_r($PHPShopReadCsvNative->CsvToArray);
?>
