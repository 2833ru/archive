<?php

/**
 * Раздел поиска товаров
 * @package PHPShopCoreDepricated
 */

// Функции поиска
include_once($SysValue['file']['search']);

// Определяем переменые
$SysValue['other']['DispShop']=DisSearch($_REQUEST['words'],$_REQUEST['cat']);

// Подключаем шаблон 
ParseTemplate($SysValue['templates']['shop']);
?>