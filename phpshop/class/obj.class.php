<?php
if (!defined("OBJENABLED")) define("OBJENABLED", dirname(__FILE__));

/**
 * Родительский класс Объекта
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopClass
 */
class PHPShopObj {
    /**
     * @var int ИД объекта в БД
     */
    var $objID;
    /**
     * @var string имя БД
     */
    var $objBase;
    /**
     * @var array массив данных
     */
    var $objRow;
    /**
     * @var bool режим отладки
     */
    var $debug=false;
    /**
     * @var bool проверка установки
     */
    var $install=true;
    /**
     * Конструктор
     * @var var поле выборки, по умолчанию id
     */
    function PHPShopObj($var='id') {
        $this->setRow($var);
    }
    /**
     * Запрос к БД
     * @var var поле выборки, по умолчанию id
     */
    function setRow($var) {
        $this->loadClass("orm");
        $PHPShopOrm = &new PHPShopOrm($this->objBase);
        $PHPShopOrm->debug=$this->debug;
        $PHPShopOrm->install=$this->install;
        $this->objRow = $PHPShopOrm->select(array('*'),array($var=>'='.$this->objID),false,array('limit'=>1));
    }

    /**
     * Выдача параметра из массива по ключу
     * @param string $paramName ключ
     * @return mixed
     */
    function getParam($paramName) {
        return $this->objRow[$paramName];
    }
    /**
     * Выдача параметра из массива по ключу, копия функции getParam($paramName)
     * @param string $paramName ключ
     * @return mixed
     */
    function getValue($paramName) {
        return $this->objRow[$paramName];
    }
    /**
     * Выдача массива значений целиком
     * @return array
     */
    function getArray() {
        return $this->objRow;
    }

    /**
     * Загрузка класса
     * @param string $class_name имя класса, согласно config.ini
     */
    function loadClass($class_name) {
        $class_path=OBJENABLED."/".$class_name.".class.php";
        if(file_exists($class_path)) require_once($class_path);
        else echo "Нет файла ".$class_path;
    }
    /**
     * Выдача десериализованного значения
     * @param string $paramName имя параметра
     * @return <type>
     */
    function unserializeParam($paramName) {
        return unserialize($this->getParam($paramName));
    }
}
?>