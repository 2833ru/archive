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
        $title = __('Форма связи');
        $this->title = $title . $this->PHPShopSystem->getValue("name");

        // Определяем переменные
        $this->set('pageTitle', $title);

        // Навигация хлебные крошки
        $this->navigation(null, $title);

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
     * Проверка ботов
     * @param array $option параметры проверки [url|captcha|referer]
     * @return boolean
     */
    function security($option = array('url' => false, 'captcha' => true, 'referer' => true)) {
        global $PHPShopRecaptchaElement;

        return $PHPShopRecaptchaElement->security($option);
    }
    /**
     * Экшен отправка формы при получении $_POST[content]
     */
    function content() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST))
            return true;

        // Безопасность
        if ($this->security()) {
            $this->send();
        }
        else
            $this->set('Error', __("Ошибка ключа, повторите попытку ввода ключа"));
        
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
            $message = "{Вам пришло сообщение с сайта} " . $this->PHPShopSystem->getValue('name') . "

{Данные о пользователе}:
----------------------
";
            unset($_POST['g-recaptcha-response']);

            // Информация по сообщению
            foreach ($_POST as $k => $val) {
                $message.=$val . "
";
                unset($_POST[$k]);
            }

            $message.="
{Дата}: " . date("d-m-y H:s a") . "
IP: " . $_SERVER['REMOTE_ADDR'];

            new PHPShopMail($this->PHPShopSystem->getEmail(), $this->PHPShopSystem->getEmail(), $subject, Parser($message), false, false, array('replyto' => $_POST['mail']));

            $this->set('Error', __("Сообщение успешно отправлено"));
        }
        else
            $this->set('Error', __("Не заполнены обязательные поля"));
    }
}
?>