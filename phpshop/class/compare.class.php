<?php

if (!defined("OBJENABLED")) {
    require_once(dirname(__FILE__) . "/product.class.php");
    require_once(dirname(__FILE__) . "/security.class.php");
}

/**
 * Добавление товаров в сравнение
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopClass
 */
class PHPShopCompare {

    var $_COMPARE;
    var $message;

    /**
     * Конструктор
     */
    function PHPShopCompare() {
        $this->_COMPARE = &$_SESSION['compare'];
    }

    /**
     * Добавление в сравнение товара
     * @param int $objID ИД товара
     */
    function add($objID) {
        $objID = PHPShopSecurity::TotalClean($objID, 1);
        $objProduct = new PHPShopProduct($objID);
        $name = PHPShopSecurity::CleanStr($objProduct->getParam("name"));
        if (!is_array($this->_COMPARE[$objID])) {
            $new = array(
                "id" => $objID,
                "name" => $name,
                "category" => $objProduct->getParam("category"));
            $this->_COMPARE[$objID] = $new;

            // сообщение для вывода во всплывающее окно
            $this->message = "Вы успешно добавили <a href='/shop/UID_$objID.html' title='Подробное описание'>$name</a> 
            в  <a href='/compare/' title='Перейти в вашу корзину'>сравнение</a>";
        } else {
            // сообщение для вывода во всплывающее окно
            $this->message = "Товар <a href='/shop/UID_$objID.html' title='Подробное описание'>$name</a> 
            уже добавлен в <a href='/compare/' title='Перейти в вашу корзину'>сравнение</a>";
        }
    }

    /**
     * Получение сообщения для всплывающего окна
     */
    function getMessage($objID) {
        return $this->message;
    }

    /**
     * Подсчет количества товаров в сравнении
     * @return <type>
     */
    function getNum() {
        return count($this->_COMPARE);
    }

}

?>