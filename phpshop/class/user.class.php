<?php

if (!defined("OBJENABLED")) {
    require_once(dirname(__FILE__) . "/obj.class.php");
}

/**
 * Библиотека данных администраторов
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopObj
 */
class PHPShopUser extends PHPShopObj {

    /**
     * Конструктор
     * @param Int $objID ИД пользователя
     */
    function PHPShopUser($objID) {
        $this->objID = $objID;
        $this->cache = true;
        $this->objBase = $GLOBALS['SysValue']['base']['table_name27'];
        parent::PHPShopObj();
    }

    /**
     * Вывод данных по ключу
     * @param string $str ключ
     * @return string 
     */
    function getParam($str) {
        return str_replace("\"", "&quot;", parent::getParam($str));
    }

    /**
     * Вывод списка адресов пользователя для выбора
     * @return string 
     */
    function getAdresList() {
        $data_adres = unserialize(parent::getParam('data_adres'));
        if (!is_array($data_adres) OR !count($data_adres['list']))
            return "";

        foreach ($data_adres['list'] as $index => $data_adres_one) {
            $dis = "";
            foreach ($data_adres_one as $key => $value) {
                if ($value)
                    $dis .= " ," . $value;
            }
            if ($dis) {
                if ($index == $data_adres['main'])
                    $sel = 'selected="selected"';
                else
                    $sel = "";
                $disp .= '<option value="' . $index . '" ' . $sel . '>' . substr($dis, 2) . '</option>';
            }
        }
        if ($disp)
            $disp = '
                <h2>Выбрать адрес доставки</h2>    
                <select name="adres_id" id="adres_id" style="margin-bottom: 15px;" size="5">
                <option value="none">Создать новый адрес</option>
                ' . $disp . '
                </select><br>
                <input type="checkbox" name="adres_this_default" value="1">  сделать выбранный вариант адресом по умолчанию
                <input type="hidden" class="adresListJson" value=\'' . PHPShopString::json_safe_encode($data_adres['list']) . '\'>
                ';
        return $disp;
    }

    /**
     * Вывод данных по ключу
     * @param string $str ключ
     * @return string 
     */
    function getValue($str) {
        return $this->getParam($str);
    }

    /**
     * Вывод имени пользователя
     * @return string
     */
    function getName() {
        return $this->getParam("name");
    }

    /**
     * Вывод ID статуса
     * @return int 
     */
    function getStatus() {
        return $this->getParam("status");
    }

    /**
     * Вывод названия статуса
     * @return string 
     */
    function getStatusName() {
        $PHPShopUserStatus = new PHPShopUserStatus($this->getStatus());
        return $PHPShopUserStatus->getParam("name");
    }

    /**
     * Вывод размера скидки
     * @return float 
     */
    function getDiscount() {
        $PHPShopUserStatus = new PHPShopUserStatus($this->getStatus());
        return $PHPShopUserStatus->getDiscount();
    }

}

/**
 * Библиотека данных пользователей
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopObj
 */
class PHPShopUserStatus extends PHPShopObj {

    /**
     * Конструктор
     * @param Int $objID ИД статуса пользователя
     * @param array $import_data массив импорта данных
     */
    function PHPShopUserStatus($objID, $import_data = null) {
        $this->objID = $objID;
        $this->cache = true;
        $this->objBase = $GLOBALS['SysValue']['base']['table_name28'];
        parent::PHPShopObj('id', $import_data);
    }

    /**
     * Вывод колонки прайса у пользователя
     * @return int
     */
    function getPrice() {
        return parent::getParam("price");
    }

    /**
     * Вывод скидки у пользователя
     * @return float
     */
    function getDiscount() {
        return parent::getParam("discount");
    }

}

/**
 * Библиотека функций для пользователей
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopClass
 */
class PHPShopUserFunction {

    /**
     * Проверка наивысшей скидки у пользователя
     * @param float $mysum стоимость заказа
     * @return array
     */
    function ChekDiscount($mysum) {
        global $PHPShopSystem;

        $maxsum = 0;
        $userdiscount = 0;
        $maxdiscount = 0;

        $sql = "select * from " . $GLOBALS['SysValue']['base']['table_name23'] . " where sum < '$mysum' and enabled='1'";
        $result = mysql_query($sql);
        while ($row = mysql_fetch_array($result)) {
            $sum = $row['sum'];
            if ($sum > $maxsum) {
                $maxsum = $sum;
                $maxdiscount = $row['discount'];
            }
        }

        if (!empty($_SESSION['UsersStatus'])) {
            $PHPShopUserStatus = new PHPShopUserStatus($_SESSION['UsersStatus']);
            $userdiscount = $PHPShopUserStatus->getDiscount();
        }
        else
            $userdiscoun = 0;

        if ($userdiscount > $maxdiscount)
            $maxdiscount = $userdiscount;

        $sum = $mysum - ($mysum * @$maxdiscount / 100);
        $format = $PHPShopSystem->getSerilizeParam("admoption.price_znak");
        $array = array(0 + @$maxdiscount, number_format($sum, $format, ".", ""));

        return $array;
    }

}

?>