<?php

/**
 * Раздел новинок
 * @package PHPShopCoreDepricated
 */

// Функции вывода новинок
include_once($SysValue['file']['newtip']);

$SysValue['other']['DispShop']=DispNewKratko();

// Подключаем шаблон 
ParseTemplate($SysValue['templates']['shop']);
?>	