<?
/*
+-------------------------------------+
|  PHPShop 2.1 Enterprise             |
|  Модуль FailUrl                     |
+-------------------------------------+
*/

 $SysValue['other']['orderNum']="Abort Payment";
 $SysValue['other']['DispShop']= ParseTemplateReturn("error/error_payment.tpl");

// Подключаем шаблон 
@ParseTemplate($SysValue['templates']['shop']);
?>
