<?php

/**
 * Обработчик жалобы на цену
 * @author PHPShop Software
 * @version 1.3
 * @package PHPShopCore
 */
class PHPShopPricemail extends PHPShopCore {

    /**
     * Конструктор
     */
    function __construct() {

        // Отладка
        $this->debug = false;

        // список экшенов
        $this->action = array("nav" => "index", "post" => "send_price_link");
        parent::__construct();
    }

    /**
     * Экшен по умолчанию 
     */
    function index() {

        if (PHPShopSecurity::true_num($this->PHPShopNav->getId()))
            $this->forma();
        else
            $this->setError404();
    }
    
    
    /**
     * Проверка ботов
     * @param array $option параметры проверки [url|captcha|referer]
     * @return boolean
     */
    function security($option = array('url' => false, 'captcha' => true, 'referer' => true)) {
        global $PHPShopRecaptchaElement;

        return $PHPShopRecaptchaElement->security($option);
    }

    /**
     * Экшен отправка формы при получении $_POST[send_price_link]
     */
    function send_price_link() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST))
            return true;

        if ($this->security()) {
            $this->send();
            $this->set('Error', PHPShopText::alert(__("Сообщение успешно отправлено"),'success'));
        }
        else
            $this->set('Error', PHPShopText::alert(__("Ошибка ключа, повторите попытку ввода ключа"))  );
        $this->index();
    }

    /**
     * Отправление данных на почту и добавление в БД
     */
    function send() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST, 'START'))
            return true;

        if (PHPShopSecurity::true_param($_POST['mail'], $_POST['name_person'], $_POST['link_to_page'])) {

            // Заголовок e-mail пользователю
            $title = __('Сообщение о меньшей цене');

            $this->set('user_name', $_POST['name_person']);
            $this->set('user_org_name', $_POST['org_name']);
            $this->set('user_tel_code', $_POST['tel_code']);
            $this->set('user_tel', $_POST['tel_name']);
            $this->set('user_info', $_POST['adr_name']);
            $this->set('user_mail', $_POST['mail']);
            $this->set('link_to_page', $_POST['link_to_page']);

            // Данные по товару
            $PHPShopProduct = new PHPShopProduct($this->PHPShopNav->getId());
            $this->set('product_name', $PHPShopProduct->getName());
            $this->set('product_art', $PHPShopProduct->getParam('uid'));
            $this->set('product_id', $this->PHPShopNav->getId());
            $this->set('product_link', $_SERVER['SERVER_NAME'] . "/shop/UID_" . $this->PHPShopNav->getId() . ".html");

            // Перехват модуля
            $this->setHook(__CLASS__, __FUNCTION__, $_POST);

            // Содержание e-mail пользователю
            $content = ParseTemplateReturn('./phpshop/lib/templates/users/mail_pricemail.tpl', true);

            if (PHPShopSecurity::true_email($_POST['mail'])) {
                PHPShopObj::loadClass('mail');
                new PHPShopMail($this->PHPShopSystem->getEmail(), $this->PHPShopSystem->getEmail(), $title, $content, true);
            }

            $this->redirect();
        }
    }

    /**
     * Переход на описание товара
     */
    function redirect() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        header("Location: ../shop/UID_" . $this->PHPShopNav->getId() . ".html?pricemail=done");
    }

    /**
     * Валюта
     * @param string $name имя поля в таблице валют для выдачи
     * @return string
     */
    function currency($name = 'code') {

        if (isset($_SESSION['valuta']))
            $currency = $_SESSION['valuta'];
        else
            $currency = $this->PHPShopSystem->getParam('dengi');
        
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.currency'));

        $row = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($currency)), false, array('limit' => 1), __FUNCTION__, array('base' => $this->getValue('base.currency'), 'cache' => 'true'));

        if ($name == 'code' and ($row['iso'] == 'RUR' or $row['iso'] == "RUB"))
            return 'p';

        return $row[$name];
    }

    /**
     * Прорисовка формы
     */
    function forma() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        $PHPShopProduct = new PHPShopProduct($this->PHPShopNav->getId());

        $this->set('productName', $PHPShopProduct->getName());
        $this->set('productDes', $PHPShopProduct->getParam('description'));
        $this->set('productImg', $PHPShopProduct->getImage());
        $this->set('productPrice', $PHPShopProduct->getPrice());
        $this->set('productUid', $this->PHPShopNav->getId());
        $this->set('productValutaName', $this->currency());

        // Данные пользователя
        if (PHPShopSecurity::true_num($_SESSION['UsersId'])) {
            $PHPShopUsers = new PHPShopUser($_SESSION['UsersId']);
            $this->set('UserMail', $PHPShopUsers->getParam('mail'));
            $this->set('UserName', $PHPShopUsers->getParam('name'));
            $this->set('UserTel', $PHPShopUsers->getParam('tel'));
            $this->set('UserTelCode', $PHPShopUsers->getParam('tel_code'));
            $this->set('UserAdres', $PHPShopUsers->getParam('adres'));
            $this->set('UserComp', $PHPShopUsers->getParam('company'));
            $this->set('formaLock', 'readonly=1');
        }
        // Запоминаем данные при неудачной отправке формы
        elseif (!empty($_POST['send_price_link'])) {
            $this->set('UserMail', $_POST['mail']);
            $this->set('UserName', $_POST['name_person']);
            $this->set('UserTel', $_POST['tel_name']);
            $this->set('UserTelCode', $_POST['tel_code']);
            $this->set('UserAdres', $_POST['adr_name']);
            $this->set('UserComp', $_POST['org_name']);
            $this->set('UserLink', $_POST['link_to_page']);
        }

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $PHPShopProduct, 'END');

        // Заголовок
        $this->title = $PHPShopProduct->getName() . ' - ' . __('Пожаловаться на цену') . " - " . $this->PHPShopSystem->getName();
        $this->description = __('Пожаловаться на цену') . ': ' . $PHPShopProduct->getName();
        $this->keywords = $PHPShopProduct->getName();

        // Вставка данных в шаблон
        $this->ParseTemplate($this->getValue('templates.pricemail_forma'));
    }

}

?>