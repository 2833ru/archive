<?php

if (!defined("OBJENABLED"))
    require_once(dirname(__FILE__)."/obj.class.php");

/**
 * Библиотека заказов
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopObj
 */
class PHPShopOrder extends PHPShopObj {
    var $objID;
    var $productID;
    var $default_valuta_iso;
    var $default_valuta_name;
    var $default_valuta_code;

    /**
     * Конструктор
     * @param int $objID ИД заказа
     */
    function PHPShopOrder($objID=false) {
        global  $PHPShopSystem;

        if($objID) {
            $this->objID=$objID;
            $this->objBase=$GLOBALS['SysValue']['base']['table_name1'];
            parent::PHPShopObj();
        }

        // Содержание корзины
        $paramOrder=parent::unserializeParam("orders");
        $this->order_metod_id=$paramOrder['Person']['order_metod'];
        parent::loadClass("system");

        // Системные настройки
        if(!$PHPShopSystem) $this->PHPShopSystem = new PHPShopSystem();
        else $this->PHPShopSystem = &$PHPShopSystem;

        $this->format = $this->PHPShopSystem->getSerilizeParam("admoption.price_znak");

        // Валюта
        $this->getDefaultValutaObj();
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
        $Payment= new PHPShopPayment($this->order_metod_id);
        $this->order_metod_name=$Payment->getName();
        return $this->order_metod_name;
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
        $this->valutaID=$Product->getValutaID();

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

        if(empty($this->ValutaIso)) {
            return $this->default_valuta_iso;
        }
        return $Valuta->getIso();
    }

    /**
     * Валюта по умолчанию в заказе
     * @param Obj $System системные настройки
     */
    function getDefaultValutaObj() {

        $this->default_valuta_id=$this->PHPShopSystem->getDefaultValutaId();
        parent::loadClass("valuta");

        $PHPShopValuta = new PHPShopValuta($this->default_valuta_id);
        $this->default_valuta_iso=$PHPShopValuta->getIso();
        $this->default_valuta_name=$PHPShopValuta->getName();
        $this->default_valuta_code=$PHPShopValuta->getCode();
        $this->default_valuta_kurs=$PHPShopValuta->getKurs();

        $kurs_beznal=$this->PHPShopSystem->getParam("kurs_beznal");
        $PHPShopValuta = new PHPShopValuta($kurs_beznal);
        $this->default_valuta_kurs_beznal=$PHPShopValuta->getKurs();
    }


    /**
     * Сумма c учетом скидки
     * @param float $sum сумма
     * @param float $disc скидка
     * @return float
     */
    function returnSumma($sum,$disc) {
        global $PHPShopSystem;

        if(!$PHPShopSystem) {
            $kurs=$this->default_valuta_kurs;
            $format = 0;
        }
        else {
            $kurs=$PHPShopSystem->getDefaultValutaKurs(true);
        }

        $sum*=$kurs;
        $sum=$sum-($sum*$disc/100);
        return number_format($sum,$this->format,".","");
    }

    /**
     * Поправки по курсу безнал
     * @param float $sum сумма
     * @param float $disc скидка
     * @return float
     */
    function returnSummaBeznal($sum,$disc) {
        $kurs=$this->default_valuta_kurs_beznal;
        $sum*=$kurs;
        $sum=$sum-($sum*$disc/100);
        return number_format($sum,$this->format,".","");
    }


    /**
     * Выдача максимальной скидки пользователя
     * @param float $mysum сумма заказа
     * @return float
     */
    function ChekDiscount($mysum) {

        if(!class_exists('PHPShopUserStatus'))
            PHPShopObj::loadClass("user");

        if(!class_exists('PHPShopOrm'))
            PHPShopObj::loadClass("orm");

        if(!class_exists('PHPShopSecurity'))
            PHPShopObj::loadClass("security");

        $maxsum=0;
        $maxdiscount=0;
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name23']);
        $row=$PHPShopOrm->select(array('sum','discount'),array('sum'=>"<'$mysum'",'enabled'=>"='1'"),false,array('limit'=>1));
        if(is_array($row)) {
            $sum=$row['sum'];
            if($sum>$maxsum) {
                $maxsum=$sum;
                $maxdiscount=$row['discount'];
            }
        }

        // Проверка статуса пользователя
        if(!empty($_SESSION['UsersStatus']) and PHPShopSecurity::true_num($_SESSION['UsersStatus'])) {
            $PHPShopUserStatus = new PHPShopUserStatus($_SESSION['UsersStatus']);
            $userdiscount=$PHPShopUserStatus->getDiscount();

            if($userdiscount>$maxdiscount)
                $maxdiscount=$userdiscount;
        }

        return $maxdiscount;
    }


    function cart($function) {
        $list=null;
        $order=$this->unserializeParam('orders');
        if(is_array($order['Cart']['cart'])) {
            $cart=$order['Cart']['cart'];
            foreach($order['Cart']['cart'] as $key=>$val) {
                $cart[$key]['price']=$this->ReturnSummaBeznal($val['price'],0);
                $cart[$key]['total']=$this->ReturnSummaBeznal($val['price']*$val['num'],0);
            }
        }

        if(is_array($cart))
            foreach($cart as $v)
                if(function_exists($function)) {
                    $list.= call_user_func($function,$v);
                }

        return $list;
    }

    function delivery($function) {
        $list=null;
        $order=$this->unserializeParam('orders');
        $PHPShopDelivery = new PHPShopDelivery($order['Person']['dostavka_metod']);
        $delivery['id']=$order['Person']['dostavka_metod'];
        $delivery['name']=$PHPShopDelivery->getCity();
        $delivery['price']=number_format($PHPShopDelivery->getPrice($order['Cart']['sum'],$order['Cart']['weight']),$this->format,'.','');

        if(function_exists($function)) {
            $list.= call_user_func($function,$delivery);
        }
        return $list;
    }

    function getCartSumma() {
        $order=$this->unserializeParam('orders');
        $sum=$this->ReturnSumma($order['Cart']['sum'],$order['Person']['discount']);
        return number_format($sum,$this->format,'.','');
    }

    function getDeliverySumma() {
        $order=$this->unserializeParam('orders');
        $sum=$this->ReturnSumma($order['Cart']['sum'],$order['Person']['discount']);
        $PHPShopDelivery = new PHPShopDelivery($order['Person']['dostavka_metod']);
        return $PHPShopDelivery->getPrice($sum,$order['Cart']['weight']);
    }

    function getDiscount() {
        $order=$this->unserializeParam('orders');
        return $order['Person']['discount'];
    }

    function getNum() {
        $order=$this->unserializeParam('orders');
        return $order['Cart']['num'];
    }

    function getTotal($nds=false) {
        $cart=$this->getCartSumma();
        $delivery=$this->getDeliverySumma();
        $total=$cart+$delivery;
        if(!empty($nds))
            $total=number_format($total*$this->PHPShopSystem->getParam('nds')/(100+$this->PHPShopSystem->getParam('nds')),$this->format,".","");
        else $total=number_format($total,$this->format,'.','');

        return $total;
    }


    function getSerilizeParam($param) {
        $param=explode(".",$param);
        $val=parent::unserializeParam($param[0]);
        if(count($param)>2) return $val[$param[1]][$param[2]];
        return $val[$param[1]];
    }

}
?>