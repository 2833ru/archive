<?php

/**
 * Раздел распродаж
 * @package PHPShopCoreDepricated
 */

// Функции вывода распродаж
include_once($SysValue['file']['newprice']);

$SysValue['other']['DispShop']=DispNewpriceKratko();

// Подключаем шаблон 
ParseTemplate($SysValue['templates']['shop']);
?>	