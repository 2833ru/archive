<?php

class PHPShopOneclick extends PHPShopCore {

    var $empty_index_action = false;
    var $system;

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
        $this->description = $this->system['title'] . " " . $this->PHPShopSystem->getValue("name");
        $this->keywords = $this->system['title'] . ", " . $this->PHPShopSystem->getValue("name");
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
        if (!empty($message))
            $this->error($message);
        else
            return $this->setError404();
    }

    /**
     * Проверка ботов
     * @param array $option параметры проверки [url/captcha]
     * @return boolean
     */
    function security($option = array('url' => false, 'captcha' => true, 'referer' => true)) {
        global $PHPShopRecaptchaElement;
        return $PHPShopRecaptchaElement->security($option);
    }

    /**
     * Экшен записи при получении $_POST[returncall_mod_send]
     */
    function oneclick_mod_product_id() {

        if ($this->security(array('url' => false, 'captcha' => (bool) $this->system['captcha'], 'referer' => true))) {
            $product = new PHPShopProduct((int) $_POST['oneclick_mod_product_id']);

            if (isset($_POST['ajax'])) {
                $_POST['oneclick_mod_name'] = PHPShopString::utf8_win1251($_POST['oneclick_mod_name']);
                $_POST['oneclick_mod_message'] = PHPShopString::utf8_win1251($_POST['oneclick_mod_message']);
                $_POST['oneclick_mod_mail'] = PHPShopString::utf8_win1251($_POST['oneclick_mod_mail']);
            }

            if ($this->system['write_order'] == 0)
                $this->write($product);
            else
                $this->write_main_order($product);

            $this->sendMail($product);

            // SMS администратору
            $this->sms($product);

            if (isset($_POST['ajax'])) {
                if (empty($this->system['title']) && empty($this->system['title_end'])) {
                    $message = $GLOBALS['SysValue']['lang']['oneclick_done'];
                } else {
                    $message = $this->system['title'] . '<br>' . $this->system['title_end'];
                }

                echo json_encode([
                    'message' => PHPShopString::win_utf8($message),
                    'success' => true
                ]);
                exit;
            }

            header('Location: ./done.html');
            exit();
        }

        $message = __($GLOBALS['SysValue']['lang']['oneclick_error']);

        if (isset($_POST['ajax'])) {
            echo json_encode([
                'message' => PHPShopString::win_utf8($message),
                'success' => false
            ]);
            exit;
        }

        $this->index($message);
    }

    /**
     * SMS оповещение
     * @param PHPShopProduct $product
     */
    function sms($product) {

        if ($this->PHPShopSystem->ifSerilizeParam('admoption.sms_enabled')) {

            $msg = substr($this->lang('mail_title_adm'), 0, strlen($this->lang('mail_title_adm')) - 1) . ' ' . $product->getName();

            include_once($this->getValue('file.sms'));
            SendSMS($msg);
        }
    }

    /**
     * @param PHPShopProduct $product
     */
    function write($product) {

        $insert = array();
        $insert['name_new'] = PHPShopSecurity::TotalClean($_POST['oneclick_mod_name'], 2);
        $insert['tel_new'] = PHPShopSecurity::TotalClean($_POST['oneclick_mod_tel'], 2);
        $insert['date_new'] = time();
        $insert['message_new'] = PHPShopSecurity::TotalClean($_POST['oneclick_mod_message'], 2);
        $insert['ip_new'] = $_SERVER['REMOTE_ADDR'];
        $insert['product_name_new'] = $product->getName();
        $insert['product_image_new'] = $product->getImage();
        $insert['product_id_new'] = $product->objID;
        $insert['product_price_new'] = $this->getPrice($product);

        // Запись в базу
        $this->PHPShopOrm->insert($insert);
    }

    /**
     * @param PHPShopProduct $product
     */
    function write_main_order($product) {

        if (empty($_POST['oneclick_mod_name']))
            $name = 'Имя не указано';
        else
            $name = PHPShopSecurity::TotalClean($_POST['oneclick_mod_name'], 2);

        if (empty($_POST['oneclick_mod_tel']))
            $phone = 'тел. не указан';
        else
            $phone = PHPShopSecurity::TotalClean($_POST['oneclick_mod_tel'], 2);

        $mail = PHPShopSecurity::TotalClean($_POST['oneclick_mod_mail'], 2);
        $comment = PHPShopSecurity::TotalClean($_POST['oneclick_mod_message'], 2);

        // таблица заказов
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
        $qty = 1;

        $order['Cart']['cart'][$product->objID]['id'] = $product->getParam('id');
        $order['Cart']['cart'][$product->objID]['uid'] = $product->getParam("uid");
        $order['Cart']['cart'][$product->objID]['name'] = $product->getName();
        $order['Cart']['cart'][$product->objID]['price'] = $this->getPrice($product);
        $order['Cart']['cart'][$product->objID]['num'] = $qty;
        $order['Cart']['cart'][$product->objID]['weight'] = '';
        $order['Cart']['cart'][$product->objID]['ed_izm'] = '';
        $order['Cart']['cart'][$product->objID]['pic_small'] = $product->getImage();
        $order['Cart']['cart'][$product->objID]['parent'] = 0;
        $order['Cart']['cart'][$product->objID]['user'] = 0;

        $order['Cart']['num'] = $qty;
        $order['Cart']['sum'] = $this->getPrice($product) * $qty;
        $order['Cart']['weight'] = $product->getParam('weight');
        $order['Cart']['dostavka'] = '';

        $order['Person']['ouid'] = $this->order_num();
        $order['Person']['data'] = time();
        $order['Person']['time'] = '';
        $order['Person']['mail'] = $mail;
        $order['Person']['name_person'] = $name;
        $order['Person']['org_name'] = '';
        $order['Person']['org_inn'] = '';
        $order['Person']['org_kpp'] = '';
        $order['Person']['tel_code'] = '';
        $order['Person']['tel_name'] = '';
        $order['Person']['adr_name'] = '';
        $order['Person']['dostavka_metod'] = '';
        $order['Person']['discount'] = 0;
        $order['Person']['user_id'] = '';
        $order['Person']['dos_ot'] = '';
        $order['Person']['dos_do'] = '';
        $order['Person']['order_metod'] = '';
        $insert['dop_info_new'] = $comment;

        // данные для записи в БД
        $insert['datas_new'] = time();
        $insert['uid_new'] = $this->order_num();
        $insert['orders_new'] = serialize($order);
        $insert['fio_new'] = $name;
        $insert['tel_new'] = $phone;
        $insert['statusi_new'] = $this->system['status'];
        $insert['status_new'] = serialize(array("maneger" => __('Быстрый заказ')));

        // Запись в базу
        $PHPShopOrm->insert($insert);
    }

    // номер заказа
    function order_num() {
        // Рассчитываем номер заказа
        $PHPShopOrm = new PHPShopOrm();
        $res = $PHPShopOrm->query("select uid from " . $GLOBALS['SysValue']['base']['orders'] . " order by id desc LIMIT 0, 1");
        $row = mysqli_fetch_array($res);
        $last = $row['uid'];
        $all_num = explode("-", $last);
        $ferst_num = $all_num[0];

        if ($ferst_num < 100)
            $ferst_num = 100;
        $order_num = $ferst_num + 1;

        // Номер заказа
        $ouid = $order_num . "-" . substr(abs(crc32(uniqid(session_id()))), 0, 3);
        return $ouid;
    }

    /**
     * @param PHPShopProduct $product
     */
    public function sendMail($product) {
        PHPShopObj::loadClass("mail");

        $zag = $this->PHPShopSystem->getValue('name') . " - " . __('Быстрый заказ') . " - " . PHPShopDate::dataV();

        $productId = " / ID " . $product->objID . " / ";
        if ($product->getParam('uid') != "") {
            $productId .= "{Артикул} " . $product->getParam('uid') . " / ";
        }

        $message = "{Доброго времени}!
                ---------------

                {С сайта} " . $this->PHPShopSystem->getValue('name') . " {пришел быстрый заказ}

                {Данные о пользователе}:
                ----------------------

                {Имя}:                " . PHPShopSecurity::TotalClean($_POST['oneclick_mod_name'], 2) . "
                {Телефон}:            " . PHPShopSecurity::TotalClean($_POST['oneclick_mod_tel'], 2) . "
                {Товар}:              " . $product->getName() . $productId . $this->getPrice($product) . " " . $this->PHPShopSystem->getDefaultValutaCode() . "
                {Сообщение}:          " . PHPShopSecurity::TotalClean($_POST['oneclick_mod_message'], 2) . "
                {Дата}:               " . PHPShopDate::dataV(time()) . "
                IP:                   " . $_SERVER['REMOTE_ADDR'] . "

                ---------------

                http://" . $_SERVER['SERVER_NAME'];

        new PHPShopMail($this->PHPShopSystem->getValue('adminmail2'), $this->PHPShopSystem->getValue('adminmail2'), $zag, Parser($message));
    }

    /**
     * @param PHPShopProduct $product
     */
    private function getPrice($product) {
        global $PHPShopPromotions;

        $price = $product->getPrice();

        // Промоакции
        $promotions = $PHPShopPromotions->getPrice($product->objRow);
        if (is_array($promotions)) {
            $prices = [$promotions['price'], $product->objRow['price2'], $product->objRow['price3'], $product->objRow['price4'], $product->objRow['price5']];
            $price = PHPShopProductFunction::GetPriceValuta($product->objID, $prices, $product->objRow['baseinputvaluta']);
        }

        return $price;
    }

}

?>