<?php

if (!defined("OBJENABLED")) {
    require_once(dirname(__FILE__)."/obj.class.php");
    require_once(dirname(__FILE__)."/array.class.php");
}

/**
 * Библиотека данных по товарам
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopObj
 */
class PHPShopProduct extends PHPShopObj {

    /**
     * Конструктор
     * @param Int $objID ИД товара
     */
    function PHPShopProduct($objID) {
        $this->objID=$objID;
        $this->objBase=$GLOBALS['SysValue']['base']['table_name2'];

        // Учет подтипов для выборки по артикулу
        if(PHPShopProductFunction::true_parent($objID)) $var='uid';
        else {
            $objID = PHPShopSecurity::TotalClean($objID,1);
            $var='id';
        }

        parent::PHPShopObj($var);
    }

    /**
     * Имя товара
     * @return string
     */
    function getName() {
        return parent::getParam("name");
    }

    /**
     * ИД валюты товара
     * @return int
     */
    function getValutaID() {
        return parent::getParam("baseinputvaluta");
    }
}

/**
 * Массив данных по товарам
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopArray
 */
class PHPShopProductArray extends PHPShopArray {

    /**
     * Конструктор
     * @param string $sql SQL условие выборки
     */
    function PHPShopProductArray($sql="") {
        $this->objSQL=$sql;
        $this->objBase=$GLOBALS['SysValue']['base']['table_name2'];
        parent::PHPShopArray('id','uid','name','category','price','price_n','sklad','odnotip','vendor','title_enabled',
                'datas','page','user','descrip_enabled','keywords_enabled','pic_small','pic_big','parent','baseinputvaluta');
    }
}

/**
 * Библиотека функций по товарам
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopClass
 */
class PHPShopProductFunction {

    /**
     * Проверка на подтип товара из 1С
     * @param string $str арткул товара
     * @return bool
     */
    function true_parent($str) {
        return preg_match("/^[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}$/",$str);
    }

    /**
     * Цена с учетом валюты
     * @param int $id ИД товара
     * @param float $price стоимость товара
     * @param int $formats кол-во знаков после запятой в стоимости
     * @param int $baseinputvaluta ИД валюты товара
     * @param bool $order параметр расчета заказе [true/false]
     * @return format
     */
    function GetPriceValuta($id,$price,$formats=0,$baseinputvaluta=false,$order=false) {
        global $SysValue,$LoadItems,$PHPShopValutaArray,$PHPShopSystem;

        if(!$LoadItems) {
            $LoadItems['Valuta']=$PHPShopValutaArray->getArray();
            $LoadItems['System']=$PHPShopSystem->getArray();
        }

        // Если выбрана другая валюта
        $format = $PHPShopSystem->getSerilizeParam("admoption.price_znak");

        // Выборка из базы нужной колонки цены для автор. пользователя
        if(!empty($_SESSION['UsersStatus'])) {
            $PHPShopUser = new PHPShopUserStatus($_SESSION['UsersStatus']);
            $GetUsersStatusPrice=$PHPShopUser->getPrice();
            if($GetUsersStatusPrice>1) {
                $pole="price".$GetUsersStatusPrice;
                $PHPShopProduct = new PHPShopProduct($id);

                $user_price=$PHPShopProduct->getParam($pole);
                if(!empty($user_price)) $price=$user_price;

            }
        }

        // Учет валюты товара
        if ($baseinputvaluta) { //Если прислали баз. валюту
            if ($baseinputvaluta!==$LoadItems['System']['dengi']) {//Если присланная валюта отличается от базовой
                $price=$price/$LoadItems['Valuta'][$baseinputvaluta]['kurs']; //Приводим цену в базовую валюту
            }
        }

        // Если выбрана другая валюта, order - флаг для расчета корзины толкьо в деф. валюте
        if($order) $valuta=$LoadItems['System']['dengi'];
        elseif (isset($_SESSION['valuta'])) $valuta=$_SESSION['valuta'];
        else $valuta=$LoadItems['System']['dengi'];

        // Правка на курс
        $price=$price*$LoadItems['Valuta'][$valuta]['kurs'];

        // Наценка
        $price=($price+(($price*$LoadItems['System']['percent'])/100));
        return number_format($price,$format,'.','');
    }
}
?>