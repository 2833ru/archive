<?php


class PHPShopOneclick extends PHPShopCore {

    /**
     * Конструктор
     */
    function __construct() {

        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['oneclick']['oneclick_jurnal'];

        // Отладка
        $this->debug = false;

        // Настройка
        $this->system();

        // Список экшенов
        $this->action = array(
            'post' => 'oneclick_mod_product_id',
            'name' => 'done',
            'nav' => 'index'
        );
        parent::__construct();

        // Хлебные крошки
        $this->navigation(null, __('Быстрый заказ'));

        // Мета
        $this->title = $this->system['title'] . " - " . $this->PHPShopSystem->getValue("name");
    }

    /**
     * Настройка
     */
    function system() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['oneclick']['oneclick_system']);
        $this->system = $PHPShopOrm->select();
    }

    /**
     * Сообщение об удачной заявке
     */
    function done() {
        $message = $this->system['title_end'];
        if (empty($message))
            $message = $GLOBALS['SysValue']['lang']['oneclick_done'];
        $this->set('pageTitle', $this->system['title']);
        $this->set('pageContent', $message);
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }
    
    /**
     * Сообщение о неудачной заявке
     */
    function error($message) {
        $this->set('pageTitle', __('Ошибка'));
        $this->set('pageContent', $message);
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * Экшен по умолчанию, вывод формы звонка
     */
    function index($message = false) {
        if(!empty($message)) 
            $this->error($message);
        else 
        return $this->setError404();
    }
    
     /**
     * Проверка ботов
     * @param array $option параметры проверки [url/captcha]
     * @return boolean
     */
    function security($option = array('url' => false, 'captcha' => true,'referer' => true)) {
        global $PHPShopRecaptchaElement;
        
        return $PHPShopRecaptchaElement->security($option);
        
    }

    /**
     * Экшен записи при получении $_POST[returncall_mod_send]
     */
    function oneclick_mod_product_id() {

        if ($this->security()) {
            $this->write();
            header('Location: ./done.html');
            exit();
        } else {
            $message = __($GLOBALS['SysValue']['lang']['oneclick_error']);
        }
        $this->index($message);
    }

    /**
     * Запись в базу
     */
    function write() {

        $PHPShopProduct = new PHPShopProduct(intval($_POST['oneclick_mod_product_id']));

        // Подключаем библиотеку отправки почты
        PHPShopObj::loadClass("mail");
        $insert = array();
        $insert['name_new'] = PHPShopSecurity::TotalClean($_POST['oneclick_mod_name'], 2);
        $insert['tel_new'] = PHPShopSecurity::TotalClean($_POST['oneclick_mod_tel'], 2);
        $insert['date_new'] = time();
        $insert['message_new'] = PHPShopSecurity::TotalClean($_POST['oneclick_mod_message'], 2);
        $insert['ip_new'] = $_SERVER['REMOTE_ADDR'];
        $insert['product_name_new'] = $PHPShopProduct->getName();
        $insert['product_id_new'] = intval($_POST['oneclick_mod_product_id']);
        $insert['product_price_new'] = $PHPShopProduct->getPrice() . ' ' . $this->PHPShopSystem->getDefaultValutaCode();

        // Запись в базу
        $this->PHPShopOrm->insert($insert);

        $zag = $this->PHPShopSystem->getValue('name') . " - ".__('Быстрый заказ')." - " . PHPShopDate::dataV();
        $message = "{Доброго времени}!
---------------

{С сайта} " . $this->PHPShopSystem->getValue('name') . " {пришел быстрый заказ}

{Данные о пользователе}:
----------------------

{Имя}:                " . $insert['name_new'] . "
{Телефон}:            " . $insert['tel_new'] . "
{Товар}:              " . $insert['product_name_new'] . " / ID " . $insert['product_id_new'] . " / " . $insert['product_price_new'] . "
{Сообщение}:          " . $insert['message_new'] . "
{Дата}:               " . PHPShopDate::dataV($insert['date_new']) . "
IP:                 " . $_SERVER['REMOTE_ADDR'] . "

---------------

http://" . $_SERVER['SERVER_NAME'];

        new PHPShopMail($this->PHPShopSystem->getValue('adminmail2'), $this->PHPShopSystem->getValue('adminmail2'), $zag, $message);
    }

}

?>