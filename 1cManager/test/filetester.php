<?php

// Библиотеки
include("../../phpshop/class/obj.class.php");
PHPShopObj::loadClass("readcsv");

$PHPShopReadCsvNative = new PHPShopReadCsvNative('upload_0.csv');
print_r($PHPShopReadCsvNative->CsvToArray);
?>
