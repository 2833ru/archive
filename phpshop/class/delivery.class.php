<?php

if (!defined("OBJENABLED")){
    require_once(dirname(__FILE__) . "/obj.class.php");
    require_once(dirname(__FILE__) . "/array.class.php");
}

/**
 * Библиотека доставки
 * @author PHPShop Software
 * @version 1.3
 * @package PHPShopClass
 */
class PHPShopDelivery extends PHPShopObj {
    
    /**
     * Тариф расчета массы покупки в граммах
     * @var int 
     */
    var $fee = 500;
    
    var $mod_price = false;

    /**
     * Конструктор
     * @param Int $objID ИД доставки
     */
    function __construct($objID = false) {
        $this->objID = $objID;
        $this->objBase = $GLOBALS['SysValue']['base']['delivery'];
        parent::__construct();
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
        
        if($this->mod_price)
            return $this->mod_price;
        
        $row = $this->objRow;
        if ($row['price_null_enabled'] == 1 and $sum >= $row['price_null']) {
            return 0;
        } else {
            if ($row['taxa'] > 0) {
                $addweight = $weight - $this->fee;
                if ($addweight < 0) {
                    $addweight = 0;
                }
                $addweight = ceil($addweight / $this->fee) * $row['taxa'];
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
    
    /**
     * Используется модулем, не менять стоимость
     * @paramm $sum float сумма доставки в модуле
     * @return bool
     */
    function checkMod($sum){
        
        $mod = parent::getParam("is_mod");
        if($mod == 2 and !empty($sum))
            $this->mod_price = $sum;
    }
   

    static function getPriceDefault() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['delivery']);
        $row = $PHPShopOrm->select(array('price'), array('flag' => "='1'", 'is_folder' => "='0'",'enabled' => "='1'"), false, array('limit' => 1));
        return $row['price'];
    }

}

/**
 * Массив доставок
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopArray
 */
class PHPShopDeliveryArray extends PHPShopArray {

    function __construct($sql = false,$args=array()) {
        $this->objSQL = $sql;
        $this->order = array('order' => 'id');
        $this->objBase = $GLOBALS['SysValue']['base']['delivery'];
        
        if(is_array($args))
            $this->args=$args;

        parent::__construct('id', "city", 'price', 'enabled', 'PID', 'is_folder');
    }

}

?>