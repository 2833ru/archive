<?php

/**
 * Раздел ошибки 404
 * @package PHPShopCoreDepricated
 */

header("HTTP/1.0 404 Not Found");
header("Status: 404 Not Found");
$SysValue['other']['DispShop']=ParseTemplateReturn($SysValue['templates']['error_page_forma']);
ParseTemplate($SysValue['templates']['shop']);
?>