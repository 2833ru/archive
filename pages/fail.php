<?php
/**
 * Вывод сообщения ошибки оплаты заказа
 * @package PHPShopCoreDepricated
 */
$SysValue['other']['orderNum']="Abort Payment";
$SysValue['other']['DispShop']= ParseTemplateReturn("error/error_payment.tpl");

// Подключаем шаблон 
ParseTemplate($SysValue['templates']['shop']);
?>