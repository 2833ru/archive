<?php

// Библиотеки
include("../../phpshop/class/obj.class.php");
PHPShopObj::loadClass("readcsv");

$PHPShopReadCsvNative = new PHPShopReadCsvNative('seamply.csv');
print_r($PHPShopReadCsvNative->CsvToArray);
?>
