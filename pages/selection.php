<?php

/**
 * Раздел подбора товаров по параметрам
 * @package PHPShopCoreDepricated
 */

// Функции подбора товара
include_once($SysValue['file']['selection']);

// Определяем переменые
$SysValue['other']['DispShop']=DispSelection($_REQUEST['v']);


// Подключаем шаблон 
ParseTemplate($SysValue['templates']['shop']);
?>