<?php

/**
 * Раздел вывода спецпредложений
 * @package PHPShopCoreDepricated
 */

// Функции спецпредложений
include_once($SysValue['file']['spec']);

$SysValue['other']['DispShop']=DispSpecKratko();

// Подключаем шаблон 
ParseTemplate($SysValue['templates']['shop']);
?>