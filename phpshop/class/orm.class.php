<?php
/**
 * Библиотека запросов к БД на основе объектов типа доступа
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopClass
 */
class PHPShopOrm {
    /**
     * @var string имя базы
     */
    var $Base;
    /**
     * @var bool режим отладки
     */
    var $debug=false;
    /**
     * @var bool коммент для отладчика
     */
    var $comment=false;
    /**
     * @var bool проверка установки
     */
    var $install=true;

    /**
     * Конструктор
     * @param string $Base имя таблицы
     */
    function PHPShopOrm($Base=false) {
        $this->objBase = $Base;
        $this->Option['where']=" and ";
        $this->nWhere=1;
        $this->nSelect=1;
        $this->sql=false;
    }

    /**
     * Выборка из БД SELECT по заданный параметрам
     * <code>
     * // example:
     * $PHPShopOrm= new PHPShopOrm('phpshop_categories');
     * $PHPShopOrm->select(array('id','name'),array('id'=>'=10'),array('order'=>'id DESC'),array('limit'=>1));
     * </code>
     * @param array $select массив ячеек для выборки
     * @param array $where массив параметра whree
     * @param array $order массив параметра order by
     * @param array $option массив параметра дополнительных опций [limit]
     * @return array
     */
    function select($select=array('*'),$where=false,$order=false,$option=false) {

        // Выборка по параметрам SELECT
        if(is_array($select)) {
            $this->_SQL.='select ';
            foreach($select as $value) {
                $this->_SQL.=$value;
                if($this->nSelect<count($select)) $this->_SQL.=',';
                $this->nSelect++;
            }
        }

        $this->_SQL.=' from '.$this->objBase;

        // Выборка по параметрам WHERE
        if(!empty($where) and is_array($where)) {
            $this->_SQL.=' where ';
            foreach($where as $pole=>$value) {
                $this->_SQL.=$pole.$value;
                if($this->nWhere<count($where)) $this->_SQL.=$this->Option['where'];
                $this->nWhere++;
            }
        }

        // Сортировка
        if(!empty($order) and is_array($order))
            foreach($order as $pole=>$value) {
                $this->_SQL.=' '.$pole.' by '.$value;
                if(!empty($option['order'])) $this->_SQL.=' '.$option['order'].' ';
            }

        // Опции LIMIT
        if(!empty($option['limit'])) $this->_SQL.=' limit '.$option['limit'];

        // Целиковый запрос
        if(!empty($this->sql)) $this->_SQL=$this->sql;

        // Трассировка
        if($this->debug) $this->setError("SQL Запрос: ",$this->_SQL);

        // Возвращаем данные в виде массива
        if($this->install)
            $result=mysql_query($this->_SQL) or die ($this->setError("SQL Ошибка для [".$this->_SQL."] ",mysql_error().""));
        else $result=mysql_query($this->_SQL) or die (PHPShopBase::errorConnect(102));

        $num=mysql_numrows($result);
        while($row = mysql_fetch_assoc($result))
            if($num>1 or $option['limit']>1 or strlen($option['limit'])>1) $this->_DATA[] = $row;
            else $this->_DATA = $row;

        // Счетчик запросов
        $GLOBALS['SysValue']['sql']['num']++;

        return stripslashes_deep($this->_DATA);
    }

    /**
     * Вывод сообщения об ошибке
     * @param string $name имя функции
     * @param string $action ошибка
     */
    function setError($name,$action) {
        global $_classPath;

        $error='<p style="BORDER: #000000 1px dashed;padding-top:10px;padding-bottom:10px;background-color:#FFFFFF;color:000000;font-size:12px">
<img hspace="10" style="padding-left:10px" align="left" src="'.$_classPath.'admpanel/img/i_display_settings_med[1].gif"
width="32" height="32" alt="PHPShopOrm Debug On"/ ><strong>'.$name.'</strong>
	 <br><em>'.$action.'</em>';
        if($this->comment) $error.='<em style="color:green"><br>Комментарий: '.$this->comment.'</em>';
        else $error.='</p>';
        echo $error;
    }

    /**
     * Распаковка массива в глобальные переменные
     */
    function var_export() {
        foreach($this->_DATA as $var)
            foreach($var as $name=>$value) $GLOBALS[$$name]=$value;
    }

    /**
     * Обновление БД update
     * <code>
     * // example:
     * $PHPShopOrm= new PHPShopOrm('phpshop_categories');
     * $PHPShopOrm->update($_POST,array('id'=>'=10'));
     * </code>
     * @param array $value массив значений
     * @param array $where массив параметра whree
     * @param string $prefix префикс полей в форме [_new]
     * @return mixed
     */
    function update($value,$where=false,$prefix='_new') {
        $this->_SQL='update '.$this->objBase.' set ';
        $_KEY=$this->findKey();

        foreach($_KEY as $key=>$v)
            if(isset($value[$key.$prefix])) {
                $this->_SQL.=$key."='".addslashes($value[$key.$prefix])."',";
            }
        $this->_SQL=substr($this->_SQL,0,strlen($this->_SQL)-1);

        // Выборка по параметрам WHERE
        if(!empty($where) and is_array($where)) {
            $this->_SQL.=' where ';
            foreach($where as $pole=>$value) {
                $this->_SQL.=$pole.$value;
                if($this->nWhere<count($where)) $this->_SQL.=$this->Option['where'];
                $this->nWhere++;
            }
        }

        // Целиковый запрос
        if(!empty($this->sql)) $this->_SQL=$this->sql;

        // Трассировка
        if($this->debug) $this->setError("SQL Запрос: ",$this->_SQL);

        // Выполнение
        if(mysql_query($this->_SQL)) return true;
        else return mysql_error();
    }

    /**
     * Анализатор БД на наличие ячеек с заданным именем
     * @return array
     */
    function findKey() {
        $result=mysql_query('select * from '.$this->objBase.' limit 1');
        $num=mysql_numrows($result);
        if($num>0) $row = mysql_fetch_assoc($result);
        else {
            $fields = mysql_list_fields($GLOBALS['SysValue']['connect']['dbase'],$this->objBase);
            $columns = mysql_num_fields($fields);
            for ($i = 0; $i < $columns; $i++) {
                $row_name = mysql_field_name($fields, $i);
                $row[$row_name]="";
            }
        }
        return $row;
    }

    /**
     * Удаление из БД delete
     * <code>
     * // example:
     * $PHPShopOrm = new PHPShopOrm('phpshop_categories');
     * $PHPShopOrm->delete(array('id'=>'=10'));
     * </code>
     * @param array $where массив параметра whree
     * @return mixed
     */
    function delete($where) {
        $this->_SQL='delete from '.$this->objBase;

        // Выборка по параметрам WHERE
        if(!empty($where) and is_array($where)) {
            $this->_SQL.=' where ';
            foreach($where as $pole=>$value) {
                $this->_SQL.=$pole.$value;
                if($this->nWhere<count($where)) $this->_SQL.=$this->Option['where'];
                $this->nWhere++;
            }
        }

        // Целиковый запрос
        if(!empty($this->sql)) $this->_SQL=$this->sql;

        // Трассировка
        if($this->debug) $this->setError("SQL Запрос: ",$this->_SQL);

        // Выполнение
        if(mysql_query($this->_SQL)) return true;
        else return mysql_error();
    }

    /**
     * Универсальный запрос к БД
     * // example:
     * $PHPShopOrm = new PHPShopOrm();
     * $PHPShopOrm->query('select id,name from phpshop_categories where id=1 order by id DESC limit 1');
     * </code>
     * @param string $sql запро к БД в формате SQL
     * @return mixed
     */
    function query($sql) {
        $this->_SQL=$sql;

        // Трассировка
        if($this->debug) $this->setError("SQL Запрос: ",$this->_SQL);

        $result=mysql_query($this->_SQL) or die ($this->setError("SQL Ошибка для [".$this->_SQL."] ",mysql_error().""));

        // Счетчик запросов
        $GLOBALS['SysValue']['sql']['num']++;

        return $result;
    }

    /**
     * Вывод отладочной информации
     * @param mixed $var данные для вывода
     */
    function trace($var) {
        echo '<pre style="BORDER: #000000 1px dashed;padding-top:10px;padding-bottom:10px;background-color:#FFFFFF;color:000000;font-size:12px">';
        print_r($var);
        echo "</pre>";
    }

    /**
     * Вставка данных в БД insert
     * <code>
     * // example:
     * $PHPShopOrm = new PHPShopOrm('phpshop_categories');
     * $PHPShopOrm->insert(array('name_new'=>'Hi Test2'));
     * </code>
     * @param array $value массив значений
     * @param <type> $prefix префикс полей в форме [_new]
     * @return mixed
     */
    function insert($value,$prefix='_new') {
        $this->_SQL='insert into '.$this->objBase.' set ';
        $_KEY=$this->findKey();
        foreach($_KEY as $key=>$v)
            if(!empty($value[$key.$prefix])) {
                $this->_SQL.=$key."='".addslashes($value[$key.$prefix])."',";
            }
        $this->_SQL=substr($this->_SQL,0,strlen($this->_SQL)-1);

        // Целиковый запрос
        if(!empty($this->sql)) $this->_SQL=$this->sql;

        // Трассировка
        if($this->debug) $this->setError("SQL Запрос: ",$this->_SQL);

        // Выполнение
        if(mysql_query($this->_SQL)) return true;
        else return mysql_error();
    }
}


/**
 * Убираем слеши в массиве
 * @param array $value массив данных
 * @return array
 */
function stripslashes_deep($value) {
    $value = is_array($value) ?
            array_map(stripslashes_deep, $value) :
            stripslashes($value);
    return $value;
}


/*
Пример выполнения ORM:

1. Выборка
$PHPShopOrm->select(array('id','name'),array('id'=>'=10'),array('order'=>'id DESC'),array('limit'=>1));
или
$PHPShopOrm->sql='select id,name from phpshop_categories where id=1 order by id DESC limit 1';
$PHPShopOrm->select();

2. Обновление
$PHPShopOrm->update($_REQUEST,array('id'=>'=10'));

3. Вставка
$PHPShopOrm->insert(array('name_new'=>'Hi Test2'));

4. Удаление
$PHPShopOrm->delete(array('id'=>'=10'));
*/
?>