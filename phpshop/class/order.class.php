<?php

if (!defined("OBJENABLED"))
    require_once(dirname(__FILE__) . "/obj.class.php");

/**
 * Библиотека для работы с заказами
 * @author PHPShop Software
 * @version 1.3
 * @package PHPShopObj
 */
class PHPShopOrderFunction extends PHPShopObj {

    var $objID;
    var $productID;
    var $default_valuta_iso;
    var $default_valuta_name;
    var $default_valuta_code;

    /**
     * Конструктор
     * @param int $objID ИД заказа
     * @param array $import_data массив импорта данных заказа
     */
    function __construct($objID = false, $import_data = null) {
        global $PHPShopSystem;

        if ($objID) {
            $this->objID = $objID;
            $this->objBase = $GLOBALS['SysValue']['base']['orders'];
            parent::__construct('id', $import_data);

            // Содержание корзины
            $paramOrder = parent::unserializeParam("orders");
            $this->order_metod_id = $paramOrder['Person']['order_metod'];
        }

        parent::loadClass("system");
        parent::loadClass("delivery");

        // Системные настройки
        if (!$PHPShopSystem)
            $this->PHPShopSystem = new PHPShopSystem();
        else
            $this->PHPShopSystem = &$PHPShopSystem;

        $this->format = intval($this->PHPShopSystem->getSerilizeParam("admoption.price_znak"));

        // Валюта
        $this->getDefaultValutaObj();
    }

    /**
     * Импорт данных
     * @param array $data массив данных
     */
    function import($data) {
        $this->objRow = $data;

        // Содержание корзины
        $paramOrder = parent::unserializeParam("orders");
        $this->order_metod_id = $paramOrder['Person']['order_metod'];
    }

    /**
     * Вывод ID метода оплаты
     * @return int
     */
    function getOplataMetodId() {
        return $this->order_metod_id;
    }

    /**
     * Вывод название метода оплаты
     * @return string
     */
    function getOplataMetodName() {
        parent::loadClass("payment");

        // Метод оплаты
        $Payment = new PHPShopPayment($this->order_metod_id);
        $this->order_metod_name = $Payment->getName();
        return $this->order_metod_name;
    }

    /**
     * Статус заказа
     * @return string
     */
    function getStatus() {
        global $PHPShopOrderStatusArray;

        if (empty($PHPShopOrderStatusArray))
            $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();

        $status = $this->getParam('statusi');
        if (!empty($status))
            return $PHPShopOrderStatusArray->getParam($this->getParam('statusi') . '.name');
        else
            return 'Новый заказ';
    }

    /**
     * Цвет статуса заказа
     * @return string
     */
    function getStatusColor() {
        global $PHPShopOrderStatusArray;

        if (empty($PHPShopOrderStatusArray))
            $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();

        $status = $this->getParam('statusi');
        if (!empty($status))
            return $PHPShopOrderStatusArray->getParam($this->getParam('statusi') . '.color');
    }

    /**
     * ID валюты товара в заказе
     * @param int $productID ИД товара
     * @return int
     */
    function getValutaId($productID) {
        parent::loadClass("product");

        // Данные по товару
        $Product = new PHPShopProduct($productID);
        $this->valutaID = $Product->getValutaID();

        return $this->valutaID;
    }

    /**
     * ISO валюты товара в заказе
     * @param int $productID ИД товара
     * @return string
     */
    function getValutaIso($productID) {
        $this->getValutaId($productID);
        parent::loadClass("valuta");
        $valutaID = $this->valutaID;

        // Валюта
        $Valuta = new PHPShopValuta($valutaID);
        $this->ValutaIso = $Valuta->getIso();

        if (empty($this->ValutaIso)) {
            return $this->default_valuta_iso;
        }
        return $Valuta->getIso();
    }

    /**
     * Валюта по умолчанию в заказе
     * @param Obj $System системные настройки
     */
    function getDefaultValutaObj() {

        $this->default_valuta_id = $this->PHPShopSystem->getDefaultValutaId();
        parent::loadClass("valuta");

        $PHPShopValuta = new PHPShopValuta($this->default_valuta_id);
        $this->default_valuta_iso = $PHPShopValuta->getIso();
        $this->default_valuta_name = $PHPShopValuta->getName();
        $this->default_valuta_code = $PHPShopValuta->getCode();
        $this->default_valuta_kurs = $PHPShopValuta->getKurs();

        $kurs_beznal = $this->PHPShopSystem->getParam("kurs_beznal");
        $PHPShopValuta = new PHPShopValuta($kurs_beznal);
        $this->default_valuta_kurs_beznal = $PHPShopValuta->getKurs();
    }

    /**
     * Сумма c учетом скидки
     * @param float $sum сумма
     * @param float $disc скидка
     * @return float
     */
    function returnSumma($sum, $disc = 0, $def = '') {
        global $PHPShopSystem;

        if (!$PHPShopSystem) {
            $kurs = $this->default_valuta_kurs;
            $this->format = 0;
        } else {
            $kurs = $PHPShopSystem->getDefaultValutaKurs(true);
        }

        $sum*=$kurs;
        $sum = $sum - ($sum * $disc / 100);
        return number_format($sum, $this->format, ".", $def);
    }

    /**
     * Поправки по курсу безнал
     * @param float $sum сумма
     * @param float $disc скидка
     * @return float
     */
    function returnSummaBeznal($sum, $disc) {
        $kurs = $this->default_valuta_kurs_beznal;
        $sum*=$kurs;
        $sum = $sum - ($sum * $disc / 100);
        return number_format($sum, $this->format, ".", "");
    }

    /**
     * Выдача максимальной скидки пользователя
     * @param float $mysum сумма заказа
     * @return float
     */
    function ChekDiscount($mysum) {

        if (!class_exists('PHPShopUserStatus'))
            PHPShopObj::loadClass("user");

        if (!class_exists('PHPShopOrm'))
            PHPShopObj::loadClass("orm");

        if (!class_exists('PHPShopSecurity'))
            PHPShopObj::loadClass("security");

        $maxsum = 0;
        $maxdiscount = 0;
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['discount']);
        $row = $PHPShopOrm->select(array('sum', 'discount'), array('sum' => "<='$mysum'", 'enabled' => "='1'"), array('order' => 'sum desc'), array('limit' => 1));
        if (is_array($row)) {
            $sum = $row['sum'];
            if ($sum > $maxsum) {
                $maxsum = $sum;
                $maxdiscount = $row['discount'];
            }
        }

        // Проверка статуса пользователя
        if (!empty($_SESSION['UsersStatus']) and PHPShopSecurity::true_num($_SESSION['UsersStatus'])) {
            $PHPShopUserStatus = new PHPShopUserStatus($_SESSION['UsersStatus']);
            $userdiscount = $PHPShopUserStatus->getDiscount();

            if ($userdiscount > $maxdiscount)
                $maxdiscount = $userdiscount;
        }

        return $maxdiscount;
    }

    /**
     * Шаблонизатор вывода корзины в заказе
     * @param string $function имя функции шаблона вывода
     * @param atrray $option дополнительные опции, передающиеся в шаблон
     * @return string 
     */
    function cart($function, $option = false) {
        $list = null;
        $order = $this->unserializeParam('orders');
        if (is_array($order['Cart']['cart'])) {
            $cart = $order['Cart']['cart'];
            foreach ($order['Cart']['cart'] as $key => $val) {
                $cart[$key]['price'] = $this->ReturnSummaBeznal($val['price'], 0);
                $cart[$key]['total'] = $this->ReturnSummaBeznal($val['price'] * $val['num'], 0);
            }
        }

        if (is_array($cart))
            foreach ($cart as $v)
                if (function_exists($function)) {
                    $list.= call_user_func_array($function, array($v, $option));
                }

        return $list;
    }

    /**
     * Вывод юр. данных по заказу.
     * @param atrray $row данные заказа
     * @return string 
     */
    function yurData($row) {
        $fielsName = array(
            "org_name" => "Наименование организации ",
            "org_inn" => "ИНН",
            "org_kpp" => "КПП",
            "org_yur_adres" => "Юридический адрес",
            "org_fakt_adres" => "Фактический адрес",
            "org_ras" => "Расчётный счёт",
            "org_bank" => "Наименование банка",
            "org_kor" => "Корреспондентский счет",
            "org_bik" => "БИК",
            "org_city" => "Город",
        );

        $disp = "";
        foreach ($fielsName as $key => $value) {
            if (!empty($row[$key]))
                $disp .= PHPShopText::b($value . ": ") . $row[$key] . "<br>";
        }

        return $disp;
    }

    /**
     * Шаблонизатор вывода доставки в заказе
     * @param string $function имя функции шаблона вывода
     * @param atrray $option дополнительные опции, передающиеся в шаблон
     * @return string 
     */
    function delivery($function, $option = false) {
        $list = null;
        $i = 0;
        $order = $this->unserializeParam('orders');

        $PHPShopDelivery = new PHPShopDelivery($order['Person']['dostavka_metod']);
        $delivery['id'] = $order['Person']['dostavka_metod'];
        $name = $PHPShopDelivery->getCity();

        if (empty($order['Cart']['dostavka']))
            $delivery['price'] = number_format($PHPShopDelivery->getPrice($order['Cart']['sum'], $order['Cart']['weight']), $this->format, '.', '');
        else
            $delivery['price'] = number_format($order['Cart']['dostavka'], $this->format, '.', '');
        $delivery['data_fields'] = $PHPShopDelivery->getParam('data_fields');

        $PID = $PHPShopDelivery->getParam('PID');
        while ($PID AND $i < 5) {
            $PHPShopDeliveryTemp = new PHPShopDelivery($PID);
            $PID = $PHPShopDeliveryTemp->getParam('PID');
            $name = $PHPShopDeliveryTemp->getCity() . ", $name";
            $i++;
        }

        $delivery['name'] = $name;

        if (function_exists($function)) {
            $list = call_user_func_array($function, array($delivery, $option));
            return $list;
        }
        else
            return $delivery;
    }

    /**
     * Выдача суммы товаров в заказе
     * @return float 
     */
    function getCartSumma() {
        $order = $this->unserializeParam('orders');
        if (!empty($order['Person']['discount']))
            $discount = $order['Person']['discount'];
        else
            $discount = 0;

        if (!empty($order['Cart']['sum'])) {
            $sum = $this->ReturnSumma($order['Cart']['sum'], $discount);
            return number_format($sum, $this->format, '.', '');
        }
    }

    /**
     * Выдача стоимости доставки в заказе
     * @return float 
     */
    function getDeliverySumma() {
        $order = $this->unserializeParam('orders');

        if (isset($order['Cart']['dostavka']))
            return $order['Cart']['dostavka'];

        if (!empty($order['Person']['discount']))
            $discount = $order['Person']['discount'];
        else
            $discount = 0;
        if (!empty($order['Cart']['sum']))
            $sum = $this->ReturnSumma($order['Cart']['sum'], $discount);
        else
            $sum = 0;
        if (!empty($order['Person']['dostavka_metod']) and !empty($sum)) {
            $PHPShopDelivery = new PHPShopDelivery($order['Person']['dostavka_metod']);
            return $PHPShopDelivery->getPrice($sum, $order['Cart']['weight']);
        }
    }

    /**
     * Выдача скидки в заказе
     * @return float 
     */
    function getDiscount() {
        $order = $this->unserializeParam('orders');
        if (!empty($order['Person']['discount']))
            return $order['Person']['discount'];
        else
            return 0;
    }

    /**
     * Выдача количества товаров в заказе
     * @return int 
     */
    function getNum() {
        $order = $this->unserializeParam('orders');
        if (!empty($order['Cart']['num']))
            return $order['Cart']['num'];
        else
            return 0;
    }

    /**
     * Выдача почты покупателя в заказе
     * @return string 
     */
    function getMail() {
        $order = $this->unserializeParam('orders');
        return $order['Person']['mail'];
    }

    /**
     * Выдача итоговой сумма заказа
     * @param bool $nds учет НДС
     * @return float 
     */
    function getTotal($nds = false, $def = '') {

        if ($this->getValue('sum') > 0)
            $total = $this->getValue('sum');
        else {
            $cart = $this->getCartSumma();
            $delivery = $this->getDeliverySumma();
            $total = $cart + $delivery;
        }
        if (!empty($nds))
            $total = $total * $this->PHPShopSystem->getParam('nds') / (100 + $this->PHPShopSystem->getParam('nds'));
        else
            $total = $total;

        $total = number_format($total, $this->format, '.', $def);
        return $total;
    }

    /**
     * Выдача времени изменения состояния заказа
     * @return string 
     */
    function getStatusTime() {
        return $this->getSerilizeParam('status.time');
    }

    /**
     * Выдача сериализованного значения
     * @param string $param
     * @return string 
     */
    function getSerilizeParam($param) {
        $param = explode(".", $param);
        $val = parent::unserializeParam($param[0]);
        if (count($param) > 2)
            return $val[$param[1]][$param[2]];
        return $val[$param[1]];
    }

    /**
     * Проверка статуса электронной оплаты
     * @return string дата оплаты
     */
    function checkPay() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['payment']);
        $data = $PHPShopOrm->select(array('*'), array('uid' => "=" . intval(str_replace('-', '', $this->getParam('uid'))),'sum'=>'='.$this->getParam('sum')), array('order' => 'datas desc'), array('limit' => 1));
        if (is_array($data))
            return $data['datas'];
    }

}

PHPShopObj::loadClass('array');

/**
 * Массив статусов заказов
 * Упрощенный доступ к статусам заказов
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopObj
 */
class PHPShopOrderStatusArray extends PHPShopArray {

    /**
     * Конструктор
     */
    function __construct() {
        $this->objBase = $GLOBALS['SysValue']['base']['order_status'];
        parent::__construct('id', 'name', 'color', 'sklad_action', 'cumulative_action', 'mail_action', 'mail_message');
    }

}

?>