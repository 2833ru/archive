<?php

// Библиотеки
include("../../phpshop/class/obj.class.php");
PHPShopObj::loadClass("readcsv");

class PHPShopReadCsvProTester extends PHPShopReadCsvPro{
    
    function PHPShopReadCsvProTester($CsvContent){
        $this->CsvContent=$CsvContent;
        parent::PHPShopReadCsvPro();
    }
    
    function Test(){
        print_r($this->CsvToArray);
    }
}


$fp=file('seamply.csv');
$PHPShopReadCsvProTester = new PHPShopReadCsvProTester($fp);
$PHPShopReadCsvProTester->Test();
?>
