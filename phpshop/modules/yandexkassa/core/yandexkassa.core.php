<?php

/**
 * Обработчик оплаты яндекс касса
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopCore
 */
class PHPShopYandexkassa extends PHPShopCore {

    /**
     * Конструктор
     */
    function PHPShopYandexkassa() {
        // Список экшенов
        parent::PHPShopCore();
    }

    /**
     * Экшен по умолчанию
     */
    function index() {
        if ($_REQUEST['act'] == 'success')
            $this->parseTemplate($GLOBALS['SysValue']['templates']['yandexkassa']['yandexmoney_success_forma'], true);
        elseif ($_REQUEST['act'] == 'fail')
            $this->parseTemplate($GLOBALS['SysValue']['templates']['yandexkassa']['yandexmoney_fail_forma'], true);
        else
            die();
    }

}

?>