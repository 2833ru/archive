<?php

PHPShopObj::loadClass('order');
PHPShopObj::loadClass('mail');
PHPShopObj::importCore('users');

$PHPShopOrder = new PHPShopOrderFunction();

/**
 * Обработчик записи заказа
 * @author PHPShop Software
 * @version 1.4
 * @package PHPShopCore
 */
class PHPShopDone extends PHPShopCore {

    /**
     * Очистка корзины после оплаты
     * @var bool 
     */
    public $cart_clean_enabled = true;
    public $delivery_mod = false;
    public $manager_comment = null;
    public $delivery_free = false;

    /**
     * Конструктор
     */
    function __construct() {
        global $PHPShopOrder;

        // Отладка
        $this->debug = false;

        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['orders'];

        // Список экшенов
        $this->action = array('nav' => 'index', "post" => 'send_to_order');
        parent::__construct();

        PHPShopObj::loadClass('cart');
        $this->PHPShopCart = new PHPShopCart();

        $this->PHPShopOrder = $PHPShopOrder;

        // Библиотека доставки
        if (PHPShopSecurity::true_num($_POST['d'])) {
            PHPShopObj::loadClass('delivery');
            $this->PHPShopDelivery = new PHPShopDelivery($_POST['d']);
        }

        // Библиотека платежных систем
        if (PHPShopSecurity::true_num($_POST['order_metod'])) {
            PHPShopObj::loadClass('payment');
            $this->PHPShopPayment = new PHPShopPayment($_POST['order_metod']);
        }

        // Навигация хлебные крошки
        $this->navigation(false, __('Оформление заказа'));
    }

    /**
     * Экшен по умолчанию
     */
    function index() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        $this->set('mesageText', $this->message($this->lang('bad_cart_1'), $this->lang('bad_order_mesage_2')));
        $disp = ParseTemplateReturn($this->getValue('templates.order_forma_mesage'));
        $this->set('orderMesage', $disp);

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, false, 'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.order_forma_mesage_main'));
    }

    /**
     * Сообщение
     * @param string $title заголовок
     * @param string $content содержание
     * @return string
     */
    function message($title, $content) {

        // Перехват модуля
        $Arg = func_get_args();
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $Arg);
        if ($hook)
            return $hook;

        $message = PHPShopText::b(PHPShopText::notice($title, false, '14px')) . PHPShopText::br();
        $message .= PHPShopText::message($content, false, '12px', 'black');

        return $message;
    }

    /**
     * Экшен записи заказа
     */
    function send_to_order() {
        global $SysValue, $link_db, $PHPShopAnalitica, $PHPShopOrder;

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST, 'START'))
            return true;

        if ($this->PHPShopCart->getNum() > 0) {

            if (isset($_SESSION['UsersLogin']) AND ! empty($_SESSION['UsersLogin']))
                $_POST['mail'] = ($_SESSION['UsersMail']);


            // создаём нового пользователя, или авторизуем старого
            if (!class_exists('PHPShopUsers'))
                PHPShopObj::importCore('users');

            $PHPShopUsers = new PHPShopUsers();
            $this->userId = $PHPShopUsers->add_user_from_order($_POST['mail']);

            if (PHPShopSecurity::true_email($_POST['mail']) AND $this->userId) {
                $this->ouid = $_POST['ouid'];

                $order_metod = intval($_POST['order_metod']);
                $PHPShopOrm = new PHPShopOrm($this->getValue('base.payment_systems'));
                $row = $PHPShopOrm->select(array('path'), array('id' => '=' . $order_metod, 'enabled' => "='1'"), false, array('limit' => 1));
                $path = $row['path'];

                // Поддержка старого API
                $LoadItems['System'] = $this->PHPShopSystem->getArray();

                $this->sum = $this->PHPShopCart->getSum(false);
                $this->num = $this->PHPShopCart->getNum();
                $this->weight = $this->PHPShopCart->getWeight();

                // Почта для заказов
                $this->adminmail = $this->PHPShopSystem->getEmail();

                // Валюта
                $this->currency = $this->PHPShopOrder->default_valuta_code;

                // Стоимость доставки
                if ($this->PHPShopDelivery) {
                    $this->PHPShopDelivery->checkMod($this->delivery_mod);
                    $this->delivery = $this->PHPShopDelivery->getPrice($this->PHPShopCart->getSum(false), $this->PHPShopCart->getWeight());
                    $this->delivery = intval(str_replace(" ", "", $this->delivery));
                } else
                    $this->delivery = 0;

                // Скидка в %
                $this->discount = $this->PHPShopOrder->ChekDiscount($this->sum);

                $sum_cart = $this->PHPShopCart->getSum();
                $sum_discount_off = $this->PHPShopCart->getSumNoDiscount();
                $sum_discount_on = $PHPShopOrder->returnSumma($sum_cart, $this->discount);
                
                // Бонусы
                $this->bonus_minus = $PHPShopOrder->bonus_minus;
                $this->bonus_plus = $PHPShopOrder->bonus_plus;

                // Сумма скидки в руб
                if ($sum_cart > $sum_discount_on)
                    $discount_sum = $sum_discount_off - $sum_discount_on;
                elseif ($sum_discount_off > $sum_cart)
                    $discount_sum = $sum_discount_off - $sum_cart;
                else
                    $discount_sum = 0;

                $this->discount_sum = number_format($discount_sum * $this->PHPShopSystem->getDefaultValutaKurs(true), $PHPShopOrder->format, '.', ' ');

                // Перехат модуля в середине функции
                $this->setHook(__CLASS__, __FUNCTION__, $_POST, 'MIDDLE');

                // Итого
                $this->total = $this->PHPShopOrder->returnSumma($this->sum, $this->discount) + $this->delivery;
                $this->set('total', $this->total);

                // Аналитика
                $PHPShopAnalitica->init(__FUNCTION__, $this);

                // Подключение логики оплаты из файлов
                if (file_exists("./payment/$path/order.php"))
                    include_once("./payment/$path/order.php");
                elseif ($order_metod < 1000)
                    exit("Нет файла ./payment/$path/order.php");

                // Запись заказа в БД
                $orderId = $this->write();

                // Ссылка на счет
                if ($path == 'bank')
                    $this->set('account', '//' . $_SERVER['SERVER_NAME'] . '/phpshop/forms/account/forma.html?orderId=' . $orderId . '&tip=1&datas=' . $this->datas);

                // Данные от способа оплаты
                if (!empty($disp))
                    $this->set('orderMesage', Parser($disp));

                // Сообщения на E-mail
                $this->mail();

                // SMS администратору
                $this->sms();

                // PUSH администратору
                $this->push();

                // Запись бонусов
                $this->bonus($orderId);
                
                // Принудительная очистка корзины
                if ($this->cart_clean_enabled)
                    $this->PHPShopCart->clean();

                // Обнуление элемента корзины
                $PHPShopCartElement = new PHPShopCartElement(true);
                $PHPShopCartElement->init('miniCart');
            }
            else {
                $disp = PHPShopText::alert($this->lang('bad_order_mesage_2'), 'danger');
                $this->set('orderMesage', $disp);
            }
        } else {
            $disp = PHPShopText::alert($this->lang('bad_order_mesage_2'), 'danger');
            $this->set('orderMesage', $disp);
        }

        // Перехат модуля в конце функции
        $this->setHook(__CLASS__, __FUNCTION__, $_POST, 'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.order_forma_mesage_main'));
    }
    
    /**
     *  Обновление бонусов
     */
    function bonus($orderId){
        $PHPShopBonus = new PHPShopBonus($_SESSION['UsersId']);
        $PHPShopBonus->updateUserBonus($this->bonus_minus,0);
        $PHPShopBonus->updateBonusLog($orderId, $this->ouid, $this->bonus_minus, 0);
    }

    /**
     *  Сообщение об успешном заказе
     */
    function mail() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST, 'START'))
            return true;

        $this->set('sum', $this->currencyMultibase($this->sum));
        $this->set('cart', $this->PHPShopCart->display('mailcartforma', array('currency' => $this->currency,'rate'=>$this->rate)));
        $this->set('currency', $this->currency);
        $this->set('discount', $this->discount);
        $this->set('discount_sum', $this->discount_sum);
        $this->set('deliveryPrice', $this->currencyMultibase($this->delivery));
        $this->set('total', $this->currencyMultibase($this->total));
        $this->set('shop_name', $this->PHPShopSystem->getName());
        $this->set('ouid', $this->ouid);
        $this->set('date', date("d-m-y"));
        $this->set('adr_name', PHPShopSecurity::CleanStr($_POST['adr_name']));
        $this->set('mail', $_POST['mail']);

        if ($this->PHPShopPayment)
            $this->set('payment', $this->PHPShopPayment->getName());

        $this->set('company', $this->PHPShopSystem->getParam('name'));

        // формируем список данных полей доставки.
        if ($this->PHPShopDelivery) {
            $this->set('deliveryCity', $this->PHPShopDelivery->getCity());
            $this->set('adresList', $this->PHPShopDelivery->getAdresListFromOrderData($_POST));
        }

        // метки письма о заказе для старых версий системы.
        $this->set('dos_ot', @$_POST['dos_ot']);
        $this->set('dos_do', @$_POST['dos_do']);
        $this->set('tel', @$_POST['tel_code'] . "-" . @$_POST['tel_name']);

        //если авторизован, имя берём из сессии, иначе из формы.
        if (!empty($_SESSION['UsersId']) and PHPShopSecurity::true_num($_SESSION['UsersId']))
            $this->set('user_name', $_SESSION['UsersName']);
        elseif (!empty($_POST['name_new']))
            $this->set('user_name', $_POST['name_new']);
        else
            $this->set('user_name', $_POST['name_person']);

        // Дополнительная информация по заказу
        if (!empty($_POST['dop_info']))
            $this->set('dop_info', $_POST['dop_info']);

        // Заголовок письма покупателю
        $title = $this->lang('mail_title_user_start') . $_POST['ouid'] . $this->lang('mail_title_user_end');

        // Перехват модуля в середине функции
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST, 'MIDDLE'))
            return true;

        // Отсылаем письмо покупателю
        $PHPShopMail = new PHPShopMail($_POST['mail'], $this->adminmail, $title, '', true, true);
        $content = ParseTemplateReturn('./phpshop/lib/templates/order/usermail.tpl', true);
        $PHPShopMail->sendMailNow($content);

        $this->set('shop_admin', "http://" . $_SERVER['SERVER_NAME'] . $this->getValue('dir.dir') . "/phpshop/admpanel/");
        $this->set('time', date("d-m-y H:i a"));
        $this->set('ip', $_SERVER['REMOTE_ADDR']);

        $title_adm = $this->lang('mail_title_adm') . $_POST['ouid'] . "/" . date("d-m-y");

        // Отсылаем письмо администратору
        $PHPShopMail = new PHPShopMail($this->adminmail, $this->adminmail, $title_adm, '', true, true, array('replyto' => $_POST['mail']));

        $content_adm = ParseTemplateReturn('./phpshop/lib/templates/order/adminmail.tpl', true);
        // Перехват модуля в конце функции
        if ($this->setHook(__CLASS__, __FUNCTION__, $content_adm, 'END'))
            return true;


        // Отсылаем письмо администратору
        $PHPShopMail->sendMailNow($content_adm);
    }

    /**
     * PUSH оповещение
     */
    function push() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        if ($this->PHPShopSystem->ifSerilizeParam('admoption.push_enabled')) {

            $msg = $this->lang('mail_title_adm') . $this->ouid . " - " . $this->total . " " . $this->currency;

            PHPShopObj::loadClass(array("push"));
            $PHPShopPush = new PHPShopPush();
            $PHPShopPush->send($msg);
        }
    }

    /**
     * SMS оповещение
     */
    function sms() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        if ($this->PHPShopSystem->ifSerilizeParam('admoption.sms_enabled')) {

            $msg = $this->lang('mail_title_adm') . $this->ouid . " - " . $this->total . " " . $this->currency;
            $phone = $this->getValue('sms.phone');

            include_once($this->getValue('file.sms'));
            SendSMS($msg, $phone);
        }
    }
    
    /**
     * Учет валюты витрины
     * @param float $sum
     */
    function currencyMultibase($sum){

         if (defined("HostID")){
               $this->rate = $this->PHPShopSystem->getDefaultValutaKurs(true);
               $sum = $sum * $this->rate;
               $sum = number_format($sum, $this->PHPShopOrder->format, '.', ' ');
               $this->currency = $this->PHPShopSystem->getDefaultValutaCode(true);
         }
         else {
              $this->rate=1;
         }
         
         return $sum;
    }

    /**
     * Отправка данных в ОФД [выключено/тест]
     * @param array $order_id ID заказа
     */
    function ofd($order_id) {
        global $_classPath;

        $ofd = 'atol';
        include_once($_classPath . 'modules/' . substr($ofd, 0, 15) . '/api.php');

        if (function_exists('OFDStart')) {
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
            $PHPShopOrm->debug = false;
            $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($order_id)), false, array('limit' => '1'));
            OFDStart($data);
        }
    }

    /**
     * Запись заказа в БД
     */
    function write() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST, 'START'))
            return true;

        // Данные покупателя // Старая логика
        $person = array(
            "ouid" => $this->ouid,
            "data" => date("U"),
            "time" => date("H:s a"),
            "mail" => PHPShopSecurity::TotalClean($_POST['mail'], 3),
            "name_person" => PHPShopSecurity::TotalClean($this->get('user_name')),
            "dostavka_metod" => intval($_POST['dostavka_metod']),
            "discount" => $this->discount,
            "user_id" => $this->userId,
            "order_metod" => intval($_POST['order_metod']));

        // Данные по корзине
        $cart = array(
            "cart" => $this->PHPShopCart->getArray(),
            "num" => $this->num,
            "sum" => $this->sum,
            "weight" => $this->weight,
            "dostavka" => $this->delivery);

        // Бесплатная доставка
        if ($this->delivery_free)
            $cart['delivery_free'] = true;

        // Статус заказа
        $this->status = array(
            "maneger" => $this->manager_comment,
            "time" => "");

        // Серелиазованный массив заказа
        $this->order = serialize(array("Cart" => $cart, "Person" => $person));

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST, 'MIDDLE'))
            return true;

        // Данные для записи
        $insert = $_POST;
        $insert['datas_new'] = $insert['date_new'] = $this->datas = time();
        $insert['uid_new'] = $this->ouid;
        $insert['orders_new'] = $this->order;
        $insert['status_new'] = serialize($this->status);
        $insert['user_new'] = $this->userId;
        $insert['dop_info_new'] = PHPShopSecurity::CleanStr($_POST['dop_info']);
        $insert['sum_new'] = $this->total;
        $insert['servers_new'] = HostID;
        $insert['admin_new'] = HostAdmin;
        $insert['bonus_minus_new']=$this->bonus_minus;
        $insert['bonus_plus_new']=$this->bonus_plus;

        // формируем данные для записи адреса к пользователю в аккаунт
        // записываем новый адрес или обновляем старый.
        // Импортируем роутер личного кабинета для возможности регистрации со страницы оформления заказа
        if (!class_exists('PHPShopUsers'))
            PHPShopObj::importCore('users');
        $PHPShopUsers = new PHPShopUsers();
        $adresData = $PHPShopUsers->update_user_adres();

        if (is_array($adresData))
            $insert = array_merge($insert, $adresData);

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $insert, 'END'))
            return true;

        // Запись заказа в БД
        $result = $this->PHPShopOrm->insert($insert);

        // ОФД Тест
        //$this->ofd($result);
        // Проверка ошибок при записи заказа
        $this->error_report($result, array("Cart" => $cart, "Person" => $person, 'insert' => $insert));

        return $result;
    }

    /**
     * Отчет администратору об ошибке
     * @param mixed $result результат выполнения записи данных в БД
     * @param array $var массив данных
     * @return boolean 
     */
    function error_report($result, $var) {

        if (!is_int($result)) {

            // Заголовок письма администратору
            $title = 'Ошибка записи заказа №' . $_POST['ouid'] . ' на ' . $this->PHPShopSystem->getName() . "/" . date("d-m-y");

            $content = 'Причина отказа записи: ' . $result . '
Дамп:
';
            ob_start();
            print_r($var);
            $content .= ob_get_clean();

            // Перехват модуля в конце функции
            if ($this->setHook(__CLASS__, __FUNCTION__, $content))
                return true;

            // Отсылаем письмо с ошибкой администратору
            new PHPShopMail($this->PHPShopSystem->getParam('adminmail2'), $this->PHPShopSystem->getParam('adminmail2'), $title, $content);
        }
    }

}

/**
 * Шаблон вывода таблицы корзины
 */
function mailcartforma($val, $option) {
    global $PHPShopModules,$PHPShopOrder;

    if (empty($val['name']))
        return true;

    // Перехват модуля
    $hook = $PHPShopModules->setHookHandler(__FUNCTION__, __FUNCTION__, array(&$val), $option);
    if ($hook)
        return $hook;

    // Артикул
    if (!empty($val['parent_uid']))
        $val['uid'] = $val['parent_uid'];
    
    $val['price']*=$option['rate'];
    $val['price'] = number_format($val['price'], $PHPShopOrder->format, '.', '');

    $dis = '<p><img style="max-width:50px;max-height:50px" src="http://'.$_SERVER['SERVER_NAME'].$val['pic_small'].'" align="left" alt="">'.$val['uid'] . "  " . $val['name'] . " (" . $val['num'] . " " . $val['ed_izm'] . " * " . $val['price'] . ") -- " . ($val['price'] * $val['num']) . " " . $option['currency'] . "</p>
";
    return $dis;
}

?>