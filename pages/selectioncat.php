<?php

/**
 * Раздел пожбора товаров по параметрам в категории
 * @package PHPShopCoreDepricated
 */

// Функции подбора товара
include_once($SysValue['file']['selection']);

// Определяем переменые
$SysValue['other']['DispShop']=DispSelectionCat();


// Подключаем шаблон 
ParseTemplate($SysValue['templates']['shop']);
?>