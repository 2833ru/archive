<?php
/**
 * Библиотека работы с массивами данных
 * <code>
 * // example:
 *class PHPShopCategoryArray extends PHPShopArray{
 *	 function PHPShopCategoryArray(){
 *	 $this->objBase=$GLOBALS['SysValue']['base']['table_name'];
 *	 parent::PHPShopArray("id","name","PID");
 *	 }
 }
 * </code>
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopClass
 */
class PHPShopArray {
    /**
     * @var string имя БД
     */
    var $objBase;
    /**
     * @var bool режим отладки
     */
    var $debug=false;
    var $cache=true;
    /**
     * @var int многомерный [1] или одномерный масив [2]
     */
    var $objType=1;
    /**
     * Сортировка выборки
     * @var array
     */
    var $order=array();

    function PHPShopArray() {
        $this->objArg=func_get_args();
        $this->objArgNum=func_num_args();
        $this->setArray();
    }
    /**
     * Создание массива выбранных элементов из БД
     * @param mixed $param имя параметра через запятую
     */
    function setArray() {
        if($this->objArgNum>0) {
            foreach($this->objArg as $v) {
                $select[]=$v;
            }
        }
        else $select[]="*";


        $PHPShopOrm = &new PHPShopOrm($this->objBase);
        $PHPShopOrm->debug=$this->debug;
        $PHPShopOrm->cache=$this->cache;
        $data=$PHPShopOrm->select($select,false,$this->order,array('limit'=>1000));

        if(is_array($data))
            foreach($data as $objRow) {
                switch($this->objType) {
                    case(1):
                        foreach($this->objArg as $val) $_array[$val]=$objRow[$val];
                        $array[$objRow[$this->objArg[0]]]=$_array;
                        break;

                    case(2):
                        $array[$objRow[$this->objArg[0]]]=$objRow[$this->objArg[1]];
                        break;

                    case(3):
                        foreach($this->objArg as $val) $array[$val]=$objRow[$val];
                        break;
                }
            }
        $this->objArray=$array;
    }
    /**
     * Выдача общего массива
     * @return array
     */
    function getArray() {
        return $this->objArray;
    }
    /**
     * Выдача элемента массива
     * @param string $param имя параметра
     * @return string
     */
    function getParam($param) {
        $param=explode(".",$param);
        return $this->objArray[$param[0]][$param[1]];
    }

    /**
     * Преобразование в ключевой массив по первому параметру при указании метода
     * @param string $param имя параметра
     * @param bool $type при совпадении ключей создается многомерный массив, иначе берется FIFO
     * @return array
     */
    function getKey($param,$type=false) {
        $param=explode(".",$param);
        $array = $this->objArray;
        if(is_array($array))
            foreach($array as $val)
                foreach($val as $key=>$v)
                    if($key == $param[1]) {
                        if(empty($type)) {
                            $newArray[$val[$param[0]]]=$v;
                        }
                        else {
                            if(empty($newArray[$val[$param[0]]]))
                                $newArray[$val[$param[0]]][]=$v;
                            else $newArray[$val[$param[0]]][]=$v;
                        }
                    }
        return $newArray;
    }

    /**
     * Подсчет элементов  в массиве
     * @return int
     */
    function getNum(){
        return count($this->objArray);
    }
}
?>