<?php

/**
 * Раздел прайс-листа
 * @package PHPShopCoreDepricated
 */

// Функции вывода прайса
include_once($SysValue['file']['price']);

// Определяем переменные
$SysValue['other']['NavActive']="price";
$SysValue['other']['searchPageCategory']=DispCategoryPrice($SysValue['nav']['page']);
$SysValue['other']['PageCategory']=$SysValue['nav']['page'];

if($SysValue['nav']['page'] == "ALL" or $SysValue['nav']['id'] == "ALL")
    $SysValue['other']['DispShop']=Vivod_price("null");
elseif(!empty($SysValue['nav']['page'])) $SysValue['other']['DispShop']=Vivod_price($SysValue['nav']['page']);
else $SysValue['other']['DispShop']=ParseTemplateReturn($SysValue['templates']['price_page_list']);

if($SysValue['other']['DispShop'] == 404) {
    header("HTTP/1.0 404 Not Found");
    header("Status: 404 Not Found");
    include("pages/error.php");
}

// Подключаем шаблон 
ParseTemplate($SysValue['templates']['shop']);
?>