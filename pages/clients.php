<?php

/**
 * Клиентский раздел
 * @package PHPShopCoreDepricated
 */

if($_REQUEST['order'] and $_REQUEST['mail']) {

    // Функции проверки заказа пользователя
    include_once($SysValue['file']['clients']);
    include_once($SysValue['file']['usersorders']);

    $SysValue['other']['formaContent']=ClientsCheck($_REQUEST['order'],$_REQUEST['mail']);
}
else {
    $SysValue['other']['formaContent']=ParseTemplateReturn($SysValue['templates']['clients_forma']);
}

$SysValue['other']['DispShop']=ParseTemplateReturn($SysValue['templates']['clients_page_list']);

// Подключаем шаблон 
ParseTemplate($SysValue['templates']['shop']);
?>