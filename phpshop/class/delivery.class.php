<?php

if (!defined("OBJENABLED"))
    require_once(dirname(__FILE__) . "/obj.class.php");

/**
 * Библиотека доставки
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopClass
 */
class PHPShopDelivery extends PHPShopObj {

    /**
     * Конструктор
     * @param Int $objID ИД доставки
     */
    function PHPShopDelivery($objID = false) {
        $this->objID = $objID;
        $this->objBase = $GLOBALS['SysValue']['base']['delivery'];
        parent::PHPShopObj();
    }

    /**
     * Вывод списка полей адреса доставки используя данные заказа и настройки доставки
     * @param array $option сумма заказа
     * @param string $delim разделитель значений
     * @return string
     */
    function getAdresListFromOrderData($option, $delim = "<br>") {
        $data_fields = unserialize($this->getParam('data_fields'));
        if (is_array($data_fields)) {
            $num = $data_fields[num];
            asort($num);
            $enabled = $data_fields[enabled];
            foreach ($num as $key => $value) {
                if ($enabled[$key]['enabled'] == 1) {
                    $adres .= $enabled[$key][name] . ": " . $option[$key . "_new"] . $delim;
                }
            }
        }

        if (!$adres)
            $adres = "Не требуется";

        return $adres;
    }

    /**
     * Расчет стоимости доставки
     * @param float $sum сумма заказа
     * @param float $weight вес заказа
     * @return float
     */
    function getPrice($sum, $weight = 0) {
        $row = $this->objRow;
        if ($row['price_null_enabled'] == 1 and $sum >= $row['price_null']) {
            return 0;
        } else {
            if ($row['taxa'] > 0) {
                $addweight = $weight - 500;
                if ($addweight < 0) {
                    $addweight = 0;
                }
                $addweight = ceil($addweight / 500) * $row['taxa'];
                $endprice = $row['price'] + $addweight;
                return $endprice;
            } else {
                return $row['price'];
            }
        }
    }

    /**
     * Вывод города доставки
     * @return string
     */
    function getCity() {
        return parent::getParam("city");
    }

    static function getPriceDefault() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['delivery']);
        $row = $PHPShopOrm->select(array('price'), array('flag' => "='1'", 'enabled' => "='1'"), false, array('limit' => 1));
        return $row['price'];
    }

}

/**
 * Массив доставок
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopArray
 */
class PHPShopDeliveryArray extends PHPShopArray {

    function PHPShopDeliveryArray() {
        $this->order = array('order' => 'id');
        $this->objBase = $GLOBALS['SysValue']['base']['delivery'];
        parent::PHPShopArray('id', "city", 'price', 'enabled', 'PID', 'is_folder');
    }

}

?>