<?php

if (!defined("OBJENABLED"))
    require_once(dirname(__FILE__)."/obj.class.php");

/**
 * Библиотека доставки
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopClass
 */
class PHPShopDelivery extends PHPShopObj {

    /**
     * Конструктор
     * @param Int $objID ИД доставки
     */
    function PHPShopDelivery($objID) {
        $this->objID=$objID;
        $this->objBase=$GLOBALS['SysValue']['base']['table_name30'];
        parent::PHPShopObj();
    }

    /**
     * Расчет стоимости доставки
     * @param float $sum сумма заказа
     * @param float $weight вес заказа
     * @return float
     */
    function getPrice($sum,$weight=0) {
        $row = $this->objRow;
        if($row['price_null_enabled'] == 1 and $sum>=$row['price_null']) {
            return 0;
        } else {
            if ($row['taxa']>0) {
                $addweight=$weight-500;
                if ($addweight<0) {
                    $addweight=0;
                }
                $addweight=ceil($addweight/500)*$row['taxa'];
                $endprice=$row['price']+$addweight;
                return $at.$endprice;
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

}
?>