<?php

/**
 * Обработчик формы сообщения с сайта
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopForma
 * @version 1.0
 * @package PHPShopCore
 */
class PHPShopForma extends PHPShopCore {

    /**
     * Конструктор
     */
    function __construct() {
        $this->debug = false;

        // список экшенов
        $this->action = array("post" => "content", "post" => "name", "nav" => "index");
        parent::__construct();
    }

    /**
     * Экшен по умолчанию, вывод формы связи
     */
    function index() {

        // Мета
        $this->title = "Форма связи - " . $this->PHPShopSystem->getValue("name");

        // Определяем переменные
        $this->set('pageTitle', 'Форма связи');

        // Навигация хлебные крошки
        $this->navigation(null, 'Форма связи');

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__);

        // Подключаем шаблон
        $this->addToTemplate("forma/page_forma_list.tpl");

        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * Экшен отправка формы при получении $_POST[name]
     */
    function name() {
        $this->content();
    }

    /**
     * Экшен отправка формы при получении $_POST[content]
     */
    function content() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST))
            return true;
        
        preg_match_all('/http:?/', $_POST['content'], $url, PREG_SET_ORDER);

        if (!empty($_SESSION['text']) and strtoupper($_POST['key']) == strtoupper($_SESSION['text']) and strpos($_SERVER["HTTP_REFERER"], $_SERVER['SERVER_NAME']) and count($url)==0) {
            $this->send();
        }
        else
            $this->set('Error', "Ошибка ключа, повторите попытку ввода ключа");
        $this->index();
    }

    /**
     * Генерация сообщения
     */
    function send() {

        // Подключаем библиотеку отправки почты
        PHPShopObj::loadClass("mail");

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST))
            return true;


        if (!empty($_POST['tema']) and !empty($_POST['name']) and !empty($_POST['content'])) {

            $subject = $_POST['tema'] . " - " . $this->PHPShopSystem->getValue('name');

            $message = "Вам пришло сообщение с сайта " . $this->PHPShopSystem->getValue('name') . "

Данные о пользователе:
----------------------
";

            // Информация по сообщению
            foreach ($_POST as $k=>$val){
                $message.=$val . "
";
            unset($_POST[$k]);   

            }

            $message.="
Дата: " . date("d-m-y H:s a") . "
IP: " . $_SERVER['REMOTE_ADDR'];
            
            new PHPShopMail($this->PHPShopSystem->getEmail(), $this->PHPShopSystem->getEmail(), $subject, $message, false, false, array('replyto'=>$_POST['mail']));
            
            $this->set('Error', "Сообщение успешно отправлено");
        }
        else
            $this->set('Error', "Не заполнены обязательные поля");
    }

}

?>