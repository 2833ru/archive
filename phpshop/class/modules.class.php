<?php
/**
 * Подключение модулей
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopClass
 */

class PHPShopModules {
    /**
     * @var mixed массив системных настроек модулей
     */
    var $ModValue;
    /**
     * @var string Относительное размещение модулей
     */
    var $ModDir;
    /**
     * @var bool режим отладки
     */
    var $debug=false;
    /**
     * Конструктор
     * @param string $ModDir  Относительное размещение модулей
     */
    function PHPShopModules($ModDir="phpshop/modules/") {
        $this->ModDir=$ModDir;
        $this->objBase=$GLOBALS['SysValue']['base']['modules'];
        
        $this->PHPShopOrm = &new PHPShopOrm($this->objBase);
        $this->PHPShopOrm->debug=$this->debug;
        
        $data=$this->PHPShopOrm->select(array('*'),false,false,array('limit'=>100));
        if(is_array($data))
            foreach($data as $row) {
                $path=$row['path'];
                $this->getIni($path);
            }
    }
    
    /**
     * Обработка паметров конфига модулей
     * @param string $path путь до конфигурации модуля
     */
    function getIni($path) {
        $ini=$this->ModDir.$path."/inc/config.ini";
        if(file_exists($ini)) {
            $SysValue = parse_ini_file($ini,1);
            
            if(is_array($SysValue['autoload']))
                foreach($SysValue['autoload'] as $k=>$v) $this->ModValue['autoload'][$k]=$v;
            
            if(is_array($SysValue['core']))
                foreach($SysValue['core'] as $k=>$v) $this->ModValue['core'][$k]=$v;

            if(is_array($SysValue['class']))
                foreach($SysValue['class'] as $k=>$v) $GLOBALS['SysValue']['class'][$k]=$v;

            if(is_array($SysValue['lang']))
                foreach($SysValue['lang'] as $k=>$v) $GLOBALS['SysValue']['lang'][$k]=$v;

            if(is_array($SysValue['admpanel']))
                foreach($SysValue['admpanel'] as $k=>$v) $this->ModValue['admpanel'][$k]=$v;

            
            $this->ModValue['templates']=$SysValue['templates'];
            $GLOBALS['SysValue']['templates'][$path]=$SysValue['templates'];
            $this->ModValue['base'][$path]=$SysValue['base'];
            $GLOBALS['SysValue']['base'][$path]=$SysValue['base'];
            $this->ModValue['class']=$SysValue['class'];
            $this->ModValue['field'][$path]=$SysValue['field'];

        }
    }
    
    /**
     * Загрузка параметра автозагрузки модулей
     */
    function doLoad() {
        global $SysValue,$PHPShopSystem,$PHPShopNav;
        if(is_array($this->ModValue['autoload']))
            foreach($this->ModValue['autoload'] as $k=>$v) {
                if(file_exists($v)) require_once($v);
                else echo("Ошибка загрузки модуля ".$k."<br>Путь: ".$v);
            }
    }
    
    /**
     * Загрузка ядра модулей
     * @param string $path путь размещения core файла модуля
     * @return <type> 
     */
    function doLoadPath($path) {
        global $SysValue;
        if(!empty($this->ModValue['core'][$path])) {
            if(is_file($this->ModValue['core'][$path])) {
                require_once($this->ModValue['core'][$path]);
                $classname = 'PHPShop'.ucfirst($SysValue['nav']['path']);
                
                if(class_exists($classname)) {
                    $PHPShopCore = new $classname ();
                    $PHPShopCore->loadActions();
                    return true;
                } else echo PHPShopCore::setError($classname,"не определен класс phpshop/modules/*/core/$classname.core.php");
            }
            else PHPShopCore::setError($path,"Ошибка загрузки модуля ".$path."<br>Путь: ".$this->ModValue['core'][$path]);
        }else return false;
    }
    /**
     * Выдача конфигурационных настроек модулей
     * @param string имя параметра формы раздел.наименование [раздел.подраздел.наименование]
     * @return <type> 
     */
    function getParam($param) {
        $param=explode(".",$param);
        if(count($param)>2) return $this->ModValue[$param[0]][$param[1]][$param[2]];
        return $this->ModValue[$param[0]][$param[1]];
    }
    /**
     * Выдача конфигурационных настроек модулей
     * @return array 
     */
    function getModValue() {
        return $this->ModValue;
    }
    
    /**
     * Парсер с заменой данных на лету
     * <code>
     * // example: 
     * $PHPShopModules->Parser(array('page'=>'market'),'catalog_page_1');
     * </code>
     * @param array $preg массив заменяемых занчений
     * @param string $TemplateName имя шаблона
     * @return string 
     */
    function Parser($preg,$TemplateName) {
        $file = newGetFile($GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin'].chr(47).$TemplateName);
        
        // Замена
        foreach($preg as $k=>$v)
            $file = str_replace($k, $v, $file);
        
        $dis = newParser($file);
        return @$dis;
    }
    
    /**
     * Выдача XML настрек модуля
     * @param string $path путь до xml настроек модуля
     * @return array
     */
    function getXml($path) {
        PHPShopObj::loadClass("xml");
        if(function_exists("xml_parser_create")) {
            if(@$db=readDatabase($path,"module")) {
                
                if(count($db)>1) return $db;
                else return $db[0];
            }
        }
    }
    /**
     * Проверка на ликвидность серийного номера
     * @param string $serial серийный номер
     * @return bool
     */
    function true_serial($serial) {
        if (preg_match('/^\d{5}-\d{5}-\d{5}$/',$serial)) {
            return true;
        }
    }

    /**
     * Проверка на обработчик файла для панели управления
     * @param string $path имя файла
     * @param string $function_name имя функции для добавления
     * @param array $data данные
     * @return string
     */
    function setAdmHandler($path,$function_name,$data){
        global $PHPShopGUI;
        $file=pathinfo($path);
        $mod=$this->ModValue['admpanel'][substr($file['basename'],0,-4)];
        if($mod) 
            if(is_file($this->ModDir.$mod)){
                include_once($this->ModDir.$mod);
                $addHandler[$function_name];
            }
            else $this->PHPShopOrm->setError('setAdmHandler',"Ошибка размещения модуля ".$this->ModDir.$mod);
    }
}

?>