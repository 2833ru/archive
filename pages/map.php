<?php
/**
 * Раздел карты сайта
 * @package PHPShopCoreDepricated
 */

// Функции карты сайта
include_once($SysValue['file']['map']);

// Определяем переменые
$SysValue['other']['DispShop']=Vivod_map();

// Подключаем шаблон 
ParseTemplate($SysValue['templates']['shop']);
?>