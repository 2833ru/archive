<?php
$_SESSION['theme']='classic';

/**
 * Библиотека оконных административных интерфейсов
 * @author PHPShop Software
 * @version 1.5
 * @package PHPShopGUI
 */
class PHPShopGUI {

    var $css;
    var $title;
    var $size;
    var $theme;
    var $dir;
    /**
     * @var string подключение дополнительных JavaScript файлов
     */
    var $includeJava;
    /**
     * @var string подключение дополнительных стилей
     */
    var $includeCss;
    /**
     * @var int общий отступ padding
     */
    var $padding=5;
    /**
     * @var int общий отступ margin
     */
    var $margin=5;
    /**
     * @var bool интерфейс окна или панели.
     */
    var $window=false;
    /**
     * @var string параметр перезагрузки контента при закрытии окна
     */
    // var $reload="top";
    /**
     * @var bool режим отладки, закрывает окно
     */
    var $debug_close_window=true;
    /**
     * @var bool режим отладки, используется вместе с debug_close_window
     */
    var $debug=false;
    /**
     * @var string кодировка
     */
    var $charset="windows-1251";
    /**
     * @var bool испрльзовать закладки
     */
    var $cssTab=true;
    /**
     * @var string путь до иконок офрмления
     */
    var $imgPath;


    /**
     * Конструктор
     */
    function PHPShopGUI($reload='top',$ajax=false) {

        // Ajax On
        $this->ajax=$ajax;

        $this->_BODY='<body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0">';

        if(empty($_SESSION['theme'])) $this->theme="classic";
        else $this->theme=$_SESSION['theme'];

        // Языковой файл
        PHPShopObj::loadClass("lang");
    }
    /**
     * Прорисовка элемента Form
     * @param string $value содержание
     * @param string $action action
     * @param string $name имя
     * @return string
     */
    function setForm($value,$action=false,$name="product_edit") {
        $CODE.='<form method="post" enctype="multipart/form-data" action="'.$action.'" name="'.$name.'" id="'.$name.'">
            '.$value.'</form>';
        return $CODE;
    }

    /**
     * Компиляция результата
     */
    function Compile() {
        $this->_HEAD='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
     <html>
     <head>
     <title>'.$this->title.'</title>
     <meta http-equiv="Content-Type" content="text/html; charset='.$this->charset.'">
     <link href="'.$this->dir.'skins/'.$this->theme.'/tab.css" type=text/css rel=stylesheet>
	 <script type="text/javascript" src="'.$this->dir.'java/tabpane.js"></script>
	 <link href="'.$this->dir.'skins/'.$this->theme.'/texts.css" type=text/css rel=stylesheet>'.$this->includeCss.'
     <script src="'.$this->dir.'java/phpshop.js" type="text/javascript"></script>'.$this->includeJava.'
	 ';

        if(!empty($this->size)) $this->_HEAD.='
     <script>window.resizeTo('.$this->size.');</script>';

        $this->_HEAD.='</head>';


        echo $this->_HEAD.'
	 '.$this->_BODY.'
	 <form method="post" enctype="multipart/form-data" action="'.$_SERVER['PHP_SELF'].'" name="product_edit" id="product_edit">
	 '.$this->_CODE.'
	 </form>
	 </body>
	 </html>';
    }

    /**
     * Прорисовка визуального редактора
     * @param string $editor
     */
    function setEditor($editor='fckeditor') {
        $this->editor=$editor;
        $editor_path= "../editors/".$this->editor."/editor.php";
        if(is_file($editor_path))  include($editor_path);

    }
    /**
     * Перевод строки
     * @param string $value текст
     * @return string
     */
    function setLine($value=false) {
        $CODE='
	 <div style="clear:both;">'.$value.'</div>';
        return $CODE;
    }
    /**
     * Прорисовка элемента Fieldset с легендой
     * @param string $title заголовок легенды
     * @param string $content содержание
     * @param string $float параметр float стиля
     * @param int $margin_left отступ слева
     * @param int $padding_top отступ свеоху
     * @return string
     */
    function setField($title,$content,$float="none",$margin_left=0,$padding_top=false) {
        $CODE='
	 <FIELDSET style="float:'.$float.';padding:'.$this->padding.'px;margin-left:'.$margin_left.'px;padding-top:'.$padding_top.'">
	 <LEGEND>'.$title.'</LEGEND>
	 '.$content.'
	 </FIELDSET>';
        return $CODE;
    }

    /**
     * Прорисовка элемента Input
     * @param string $type тип [text,password,button и т.д]
     * @param string $name имя
     * @param mixed $value значение
     * @param int $float float
     * @param int $size размер
     * @param string $onclick экшен по клику, имя javascript функции
     * @param string $class имя класса стиля
     * @param string $action привязка к экшену, имя php функции
     * @param string $caption текст перед элементом
     * @param string $description текст после элемента
     * @return string
     */
    function setInput($type,$name,$value,$float="none",$size=200,$onclick="return true",$class=false,$action=false,$caption=false,$description=false) {
        $CODE='
	 <div style="float:'.$float.';padding:'.$this->padding.'px;">
             '.$caption.' <input type="'.$type.'" value="'.$value.'" name="'.$name.'" id="'.$name.'" style="width:'.$this->chekSize($size).';"
                 class="'.$class.'" onclick="'.$onclick.'"> '.$description.'</div>';

        // Слушатель действия
        if($action == true ) $this->action[$name]=$action;

        return $CODE;
    }
    /**
     * Прорисовка элемента InputText
     * @param string $caption текст перед элементом
     * @param string $name имя
     * @param mixed $value значение
     * @param int $size размер
     * @param string $description текст после элемента
     * @param string $float  float
     * @param string $class имя класса стиля
     * @return string
     */
    function setInputText($caption,$name,$value,$size=300,$description=false,$float="none",$class=false) {
        return $this->setInput('text',$name,$value,$float,$size,false,$class,false,$caption,$description) ;
    }
    /**
     * Прорисовка дополнительного элемента
     * @param mixed $val код элемента
     */
    function setMyTag($val) {
        $this->_CODE.='
	 '.$val;
    }
    /**
     * Прорисовка заголовка формы окна
     * @param string $name заголовок
     * @param string $title описание
     * @param string $icon иконка
     */
    function setHeader($name,$title,$icon) {
        $this->_CODE.='
	 <table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
     <tr bgcolor="#ffffff">
     <td style="padding:10">
     <b>'.$name.'</b><br>
     &nbsp;&nbsp;&nbsp;'.$title.'
     </td>
     <td align="right">
     <img src="'.$icon.'" border="0" hspace="10">
     </td>
     </tr>
     </table>';
    }
    /**
     * Прорисовка закладок Tab
     * <code>
     * // example:
     * $PHPShopGUI->setTab(array("Заголовок1","Содержание1","Высота"),array("Заголовок2","Содержание2","Высота"));
     * </code>
     */
    function setTab() {
        $this->_CODE.='
	 <!-- begin tab pane -->
     <div class="tab-pane" id="article-tab" style="margin-top:'.$this->margin.'px;">
     <script type="text/javascript">
     tabPane = new WebFXTabPane( document.getElementById( "article-tab" ), true );
     </script>
	 ';

        $Arg=func_get_args();


        if(!empty($this->TrialOff)) {
            $count=count($Arg)-1;
            foreach($Arg as $key=>$val)
                if($key<$count) unset($Arg[$key]);
        }

        foreach($Arg as $key=>$val) {

            $this->_CODE.='
     <div class="tab-page" id="tab_'.$key.'" style="height:'.$val[2].'">
     <h2 class="tab">'.$val[0].'</h2>
     <script type="text/javascript">
     tabPane.addTabPage( document.getElementById( "tab_'.$key.'" ) );
     </script>
	 '.$val[1].'
	 </div>';
            $this->tab_key++;
        }
        $this->_CODE.='</div>';
    }

    /**
     * Добавление закладки для модулей
     * <code>
     * // example:
     * $PHPShopGUI->setTab(array("Заголовок1","Содержание1","Высота"),array("Заголовок2","Содержание2","Высота"));
     * $PHPShopGUI->addTab(array("Заголовок3","Содержание3","Высота"));
     * </code>
     */
    function addTab() {
        $Arg=func_get_args();
        foreach($Arg as $key=>$val) {
            $this->_CODE.='
     <div class="tab-page" id="tab_'.($this->tab_key+$key).'" style="height:'.$val[2].'">
     <h2 class="tab">'.$val[0].'</h2>
     <script type="text/javascript">
     tabPane.addTabPage( document.getElementById( "tab_'.($this->tab_key+$key).'" ) );
     </script>
	 '.$val[1].'
	 </div>';
        }
        $this->_CODE.='</div>
	 ';
    }


    /**
     * Проверка размера
     * @param mixed $size
     * @return string
     */
    function chekSize($size) {
        if(!strpos($size, '%') and !strpos($size, 'px')) $size.='px';
        return $size;
    }

    /**
     * Добавление JS файлов
     */
    function addJSFiles() {
        $Arg=func_get_args();
        foreach($Arg as $val) {
            $this->includeJava.='<script type="text/javascript" src="'.$val.'"></script>';
        }
    }

    /**
     * Добавление CSS файлов
     */
    function addCSSFiles() {
        $Arg=func_get_args();
        foreach($Arg as $val) {
            $this->includeCss.='<link href="'.$val.'" type=text/css rel=stylesheet>';
        }
    }
    /**
     * Прорисовка элемента Div
     * @param string $align align
     * @param string $code содержание
     * @param string $style имя стиля css
     * @nane string $name имя блока
     * @return string
     */
    function setDiv($align,$code,$style=false,$name='div1') {
        $CODE='
	 <div align="'.$align.'" style="'.$style.'" name="'.$name.'" id="'.$name.'">
	 '.$code.'
	 </div>
	 ';
        return $CODE;
    }
    /**
     * Прорисовка подвала
     * @param string $code содержание
     */
    function setFooter($code) {
        $this->_CODE.=$this->setDiv("right",$code);

        // Слушатель
        if(is_array($this->action))
            foreach($this->action as $name=>$function)
                $this->_CODE.=$this->setInput("hidden","actionList[$name]",$function);
    }
    /**
     * Прорисовка элемета Textarea
     * @param string $name имя
     * @param mixed $value значение
     * @param string $float float
     * @param mixed $width длина элемента
     * @param mixed $height ширина элемента
     * @return string
     */
    function setTextarea($name,$value,$float="none",$width='99%',$height='50px') {
        $CODE='
	 <textarea style="float:'.$float.';margin:'.$this->margin.'px;height:'.$height.';width:'.$width.'" name="'.$name.'" id="'.$name.'">'.$value.'</textarea>
	 ';
        return $CODE;
    }

    /**
     * Прорисовка календаря
     * @param string $name имя поля для вставки даты
     * @param string $align выравнивание
     * @param string $icon кинока
     * @return string
     */
    function setCalendar($name,$align='right',$icon='../icon/date.gif',$float='left',$style="width:50px") {
        $CODE='<div style="float:'.$float.';padding:'.$this->padding.'px;'.$style.'">'.$this->setImage($icon,16,16,$align='right',0,'','popUpCalendar(this, product_edit.'.$name.', \'dd-mm-yyyy\');').'</div>';
        return $CODE;
    }

    /**
     * Прорисовка блока инструкции с прокруткой
     * @param string $value содержание text
     * @param string $height высота
     * @param string $width ширина
     * @param string $align выравнивание
     * @return string
     */
    function setInfo($value,$height=false,$width='100%',$align="left") {
        return $this->setDiv($align,$value,'width:'.$this->chekSize($width).';height:'.$this->chekSize($height).';background-color:white;padding:10px;border:1px;border-style: inset;overflow:auto;');
    }
    /**
     * Прорисовка элемента Select
     * <code>
     * // example:
     * $value[]=array(123,'моя цифра 1','selected');
     * $value[]=array(567,'моя цифра 2 ',false);
     * $PHPShopGUI->setSelect('my',$value,100);
     * </code>
     * @param string $name имя
     * @param array $value значенение в виде массива
     * @param int $width ширина
     * @param string $float float
     * @param string $caption текст перед элементом
     * @param string $onchange имя javascript функции по экшену onchange
     * @param int $height высота
     * @param int $size размер
     * @return string
     */
    function setSelect($name,$value,$width,$float="none",$caption=false,$onchange="return true",$height=false,$size=1) {
        $CODE=$caption.' <select name="'.$name.'" id="'.$name.'" size="'.$size.'" style="float:'.$float.';margin:'.$this->margin.'px;width:'.$width.'px;height:'.$height.'px" onchange="'.$onchange.'">';
        if(is_array($value))
            foreach($value as $val) $CODE.='<option value="'.$val[1].'" '.@$val[2].'>'.$val[0].'</option>';
        $CODE.='</select>
	 ';
        return $CODE;
    }

    /**
     * Заполнение элемента Select
     * @param int $n
     * @return array
     */
    function setSelectValue($n) {
        $i=1;
        while($i<=10) {
            if($n==$i) $s="selected"; else $s="";
            $select[]=array($i,$i,$s);
            $i++;
        }
        return $select;
    }


    /**
     * Прорисовка элемента
     * @param string $name имя
     * @param string $value значение
     * @param string $caption описание
     * @param string $checked checked
     * @param string $onchange имя javascript функции по экшену onchange
     * @return string
     */
    function setCheckbox($name,$value,$caption,$checked="checked",$onchange="return true") {

        if($checked==1) $checked="checked";

        $CODE='
	 <input type="checkbox" value="'.$value.'" name="'.$name.'" id="'.$name.'" '.$checked.' onchange="'.$onchange.'"> '.$caption.'
	 ';
        return $CODE;
    }
    /**
     * Прорисовка элемента Radio
     * @param string $name имя
     * @param string $value значение
     * @param string $caption описание
     * @param string $checked checked
     * @param string $onchange имя javascript функции по экшену onchange
     * @return string
     */
    function setRadio($name,$value,$caption,$checked="checked",$onchange="return true") {
        $CODE='
	 <input type="radio" value="'.$value.'" name="'.$name.'" id="'.$name.'" '.$checked.' onchange="'.$onchange.'"> '.$caption.'
	 ';
        return $CODE;
    }
    /**
     * Прорисовка текста
     * @param string $value текст
     * @param string $float float
     * @param string $style имя стиля css
     * @return string
     */
    function setText($value,$float="left",$style=false) {
        $CODE='<div style="float:'.$float.';padding:'.$this->padding.'px;'.$style.'">'.$value.'</div>';
        return $CODE;
    }
    /**
     * Прорисовка элемента image
     * @param string $src адрес изображения
     * @param int $width ширина
     * @param int $height высота
     * @param string $align align
     * @param Int $hspace hspace
     * @param string $style имя стиля css
     * @return string
     */
    function setImage($src,$width,$height,$align='absmiddle',$hspace="5",$style=false,$onclick="return false") {
        $CODE='<img src="'.$src.'" width="'.$width.'" height="'.$height.'" border="0" align="'.$align.'" hspace="'.$hspace.'" style="'.$style.'" onclick="'.$onclick.'">';
        return $CODE;
    }
    /**
     * Прорисовка ссылки
     * @param string $href адрес ссылки
     * @param string $caption текст ссылки
     * @param string $target target
     * @param string $style имя стиля css
     * @return string
     */
    function setLink($href,$caption,$target='_blank',$style=false) {
        $CODE='<a href="'.$href.'" target="'.$target.'" title="'.$caption.'" style="'.$style.'">'.$caption.'</a>';
        return $CODE;
    }
    /**
     * Закрытие окна
     */
    function setClose() {
        $this->_CODE.='<script type="text/javascript">window.close();</script>';
    }
    /**
     * Сообщение об ошибке
     * @param string $name имя ошибки
     * @param string $action описание ошибки
     */
    function setError($name,$action) {
        $this->_CODE.='<p><span style="color:red">Ошибка обработчика события: </span> <strong>'.$name.'()</strong>
	 <br><em>'.$action.'</em></p>';
    }
    /**
     * Перезагрузка окна
     */
    function setReload() {
        if(!$this->debug) {
            $this->_CODE.='
	 <script type="text/javascript">
	 try{
	 ';
            if ($this->reload == "left") $this->_CODE.=' window.opener.top.frame1.location.reload();';
            if ($this->reload == "right") $this->_CODE.=' window.opener.top.frame2.location.reload();';
            if ($this->reload == "top")

            // Поддержка Ajax
                if($this->ajax) $this->_CODE.=" DoReloadMainWindowModule(".$this->ajax.");";
                else $this->_CODE.=' window.opener.location.reload();';

            $this->_CODE.='
	 }catch(e){
         ';
            // Отладка, не закрывем окно
            if(empty($this->debug))
                $this->_CODE.='self.close();';

            $this->_CODE.='}';

            if($this->debug_close_window) $this->_CODE.='window.close();';
            $this->_CODE.='</script>';
        }
    }

    /**
     * Назначение экшена
     * @param string $name переменная для анализа
     * @param string $function имя функции php обработчика
     * @param bool $reload параметр перезагрузки после выполнения
     */
    function setAction($name,$function,$reload=false) {
        if(!empty($name)) {
            if(function_exists($function)) {
                $action = call_user_func($function);
                if($action !== true) $this->setError($function,$action);
                else {
                    if($reload != "none") $this->setReload();
                    $this->Compile();
                }
            } else $this->setError($function,"function do not exists");
        }
    }
    /**
     * Назначение загрузчика
     * @param string $name переменная для анализа (выполнятетя, если переменная не определена)
     * @param string $function имя функции php обработчика
     */
    function setLoader($name,$function) {
        if(empty($name))
            if(function_exists($function)) {
                $action = call_user_func($function);
                if(!$action) $this->setError($function,$action);
                else {
                    $this->Compile();
                }
            } else $this->setError($function,"function do not exists");
    }
    /**
     * Проверка на экшен
     */
    function getAction() {
        if(!empty($_POST['actionList']) and is_array($_POST['actionList']))
            foreach($_POST['actionList'] as $action=>$function)
                if(!empty($_POST[$action])) {
                    $this->setAction($action,$function);
                    //$this->Compile();
                }
    }

    /**
     * Прорисовка элемента Button
     * @param string $value значение
     * @param string $img иконка
     * @param string $width ширина
     * @param string $height высота
     * @param string $float float
     * @param string $onclick имя javascript функции по экшену onclick
     * @return string
     */
    function setButton($value,$img,$width,$height,$float="none",$onclick="return false") {
        $CODE='
	 <div style="float:'.$float.';padding:'.$this->padding.'px;">
	 <BUTTON style="width:'.$width.'; height:'.$height.'; margin-left:5"  onclick="'.$onclick.'">
     <img src="'.$this->imgPath.$img.'" width="16" height="16" border="0" align="absmiddle" hspace="3">
     '.$value.'
     </BUTTON></div>
	 ';
        return $CODE;
    }



    /**
     * Прорисовка формы о модуле
     * @return string
     */
    function setPay($serial,$pay=false,$version=false,$update=false) {
        global $PHPShopModules;

        $mes=null;
        $path=$PHPShopModules->path;
        PHPShopObj::loadClass("date");

        if(!empty($path)) {
            $data=$PHPShopModules->checkKeyBase();
            if($data) {
                $this->TrialOff=true;

                if(!$PHPShopModules->checkKey($serial,$path))
                    $mes='<br>Срок ознакомительного периода до <b>'.PHPShopDate::dataV($data,false).'</b>';
            }
        }

        
        $PHPShopInterface = new PHPShopInterface();
        $PHPShopInterface->size="500,400";
        $PHPShopInterface->window=true;
        $PHPShopInterface->idRows='p';
        $PHPShopInterface->imgPath="../../../admpanel/img/";
        $PHPShopInterface->setCaption(array("Название","20%"),array("Версия","20%"),array("Описание","80%"));

        // Описание модуля
        $db=$PHPShopModules->getXml("../install/module.xml");
        if($db['version']>$version and !empty($update)){
            PHPShopObj::loadClass('text');
            $version_info=PHPShopText::notice('Версия ядра '.$db['version'].' не соответствует версии БД '.$version);
            $version_info.=$this->setInput("submit","modupdate","Обновить","center",100,"","but","actionBaseUpdate");
        }
        else $version_info=$db['version'];
        $PHPShopInterface->setRow(1,$db['name'],$version_info,$db['description'].$mes);

        $CODE=$PHPShopInterface->Compile();


        if(!$pay)
            return $CODE;

        $CODE.=$this->setLine('<br>');

        if($PHPShopModules->checkKey($serial,$path)) {
            $CODE.=$this->setField('Серийный номер',$this->setInputText('EEM-','serial_new',$serial,$size=110,$this->setImage($PHPShopInterface->imgPath.'icon-activate.gif',16,16,$align='absmiddle',0).' * Активация выполнена',false,'serial_done'));
            $this->TrialOff=false;
            $PHPShopModules->setKeyBase($path);
            return $CODE;
        }

        $status_serial_img=null;
        $status_serial=null;

        if(!empty($serial)) {
            $status_serial='serial_fail';
            $status_serial_img=$this->setImage($PHPShopInterface->imgPath.'error.gif',16,16,$align='absmiddle',0);
        }

        $CODE.=$this->setField('Серийный номер',$this->setInputText('EEM-','serial_new',$serial,110,$status_serial_img.' * Формат серийного  номера: 11111-22222-33333',false,$status_serial));

        if(!$PHPShopModules->checkKey($serial,$path) and !empty($db['base']) )
            $CODE.=$this->setButton("Купить лицензию ".$db['price'],'../../../admpanel/icon/key.png',200,false,$float="none",
                    $onclick="window.open('http://www.phpshop.ru/order/?bay_mod=".$db['base']."')");

        return $CODE;
    }

}

/**
 * Библиотека табличных административных интерфейсов
 * @author PHPShop Software
 * @version 1.3
 * @package PHPShopGUI
 */
class PHPShopInterface extends PHPShopGUI {
    /**
     * @var string ссылка ячейки (открытие окна для редактирование)
     */
    var $link;
    /**
     * @var string размер
     */
    var $razmer;
    /**
     * @var string путь до иконок
     */
    var $imgPath="img/";
    /**
     * @var int отступ
     */
    var $padding=5;
    /**
     * @var int отступ
     */
    var $margin=5;
    /**
     * @var bool это окно?
     */
    var $window=false;
    /**
     * @var string id сетки для таблицы. Если рисуются 2 таблицы, то idRows должен быть уникальный для каждой сетки.
     */
    var $idRows='r';

    /**
     * Конструктор
     */
    function PHPShopInterface() {
        $this->n=1;
        $this->numRows=0;
        $this->razmer=$this->winsize;

        if(empty($_SESSION['theme'])) $this->theme="classic";
        else $this->theme=$_SESSION['theme'];
    }

    /**
     * Прорисовка заголовка
     * @return string
     */
    function setHeader() {
        return '<html>
            <head>
<meta http-equiv="Content-Type" content="text/html; charset='.$this->charset.'">
<link href="'.$this->dir.'skins/'.$this->theme.'/texts.css" type=text/css rel=stylesheet>'.$this->includeCss.'
<script src="'.$this->dir.'java/phpshop.js" type="text/javascript"></script>
</head>
<body bottommargin="0" rightmargin="0" topmargin="0" leftmargin="0" bgcolor="ffffff">';
    }
    /**
     * Компиляция результата
     * @return string
     */
    function Compile() {
        $compile='';
        if($this->numRows>10 and !$this->razmer) $this->razmer="height:580px;";

        if($this->header) $compile.=$this->setHeader();

        $compile.='<div id="interfacesWin" name="terfacesWin" align="left" style="width:100%;'.$this->razmer.';overflow:auto">
     <table width="100%"  cellpadding="0" cellspacing="0" style="border:1px;border-style:inset;">
     <tr>
	    <td valign="top">
	       <table cellpadding="0" cellspacing="1" width="100%" border="0">
		   '.$this->_CODE.'
		   </table>
	    </td>
     </tr>
     </table></div>'.$this->_CODE_ADD_BUTTON;
        if(empty($this->window)) echo $compile;
        else return $compile;
    }
    /**
     * Прорисовка заголовка столбцов таблицы
     */
    function setCaption() {
        $Arg=func_get_args();

        foreach($Arg as $val)
            @$CODE.='
     <td width="'.$val[1].'"><button class="pane"><img src="'.$this->imgPath.'arrow_d.gif" width="7" height="7" border="0" hspace="5">'.$val[0].'</button></td>
     ';
        $this->_CODE.='<tr>'.$CODE.'</tr>';
    }
    /**
     * Прорисовка ячеек таблицы
     */
    function setRow() {
        $this->numRows++;
        $Arg=func_get_args();


        if(!empty($this->link)) $javaAction='onclick="miniWin(\''.$this->link.'?id='.$Arg[0].'\','.$this->size.')"';
        else $javaAction="";

        foreach($Arg as $key=>$val) {

            if(empty($this->window)) {
                if($key == 1) $align="center"; else $align="left";
            } else $align="left";

            if($key>0)
                @$CODE.='
     <td align="'.$align.'">'.$val.'</td>
     ';
        }
        $this->_CODE.='<tr class="row" onmouseover="show_on(\''.$this->idRows.$this->n.'\')" id="'.$this->idRows.$this->n.'" onmouseout="show_out(\''.$this->idRows.$this->n.'\')" '.$javaAction.'>'.$CODE.'</tr>';
        //$this->_CODE.='<tr class="row" id="'.$this->idRows.$this->n.'" onmouseover="PHPShopJS.rowshow_on(this)" onmouseout="PHPShopJS.rowshow_out(this)" '.$javaAction.'>'.$CODE.'</tr>';
        $this->n++;
    }
    /**
     * Назначение кнопки создания новой позиции
     * @param string $link ссылка на новое окно
     */
    function setAddItem($link) {
        $this->_CODE_ADD_BUTTON='
	 <div align="right" style="padding:10"><BUTTON style="width: 15em; height: 2.2em; margin-left:5"  onclick="miniWin(\''.$link.'\','.$this->size.')">
     <img src="'.$this->imgPath.'icon-move-banner.gif" width="16" height="16" border="0" align="absmiddle">
     Новая позиция
     </BUTTON>
	 </div>
	 ';
    }
    /**
     * Прорисовка иконки вкл/./выкл
     * @param bool $flag вкл
     * @return string
     */
    function icon($flag) {
        if(empty($flag)) $imgchek='<img src="'.$this->imgPath.'icon-deactivate.gif" width="16" height="16" border="0">';
        else $imgchek='<img src="'.$this->imgPath.'icon-activate.gif" width="16" height="16" border="0">';
        return $imgchek;
    }
}


/**
 * Библиотека навигационных административных интерфейсов
 * @author PHPShop Software
 * @version 1.3
 * @package PHPShopGUI
 */
class PHPShopIcon extends PHPShopGUI {
    /**
     * Конструктор
     */
    function PHPShopIcon() {
        $this->iconNum=0;
        $this->imgPath="icon/";
    }
    /**
     * Прорисовка иконки навигации
     * @param string $icon адрес икноки
     * @param string $alt описание alt
     * @param string $onclick имя javascript функции по экшену onclick
     * @return string
     */
    function setIcon($icon,$alt,$onclick) {
        $this->iconNum++;
        $CODE='<td id="but'.$this->iconNum.'" class="butoff"><img title="'.$alt.'" src="'.$icon.'" alt="'.$alt.'" width="16" height="16" border="0" onmouseover="ButOn('.$this->iconNum.')" onmouseout="ButOff('.$this->iconNum.')" onclick="'.$onclick.'"></td>
   <td width="3"></td>';
        $this->iconNum++;
        return $CODE;
    }
    /**
     * Прорисовка бордюра разделителя
     * @return string
     */
    function setBorder() {
        $CODE='<td width="1" bgcolor="#ffffff"></td>
     <td width="1" class="menu_line"></td>
     <td width="3"></td>';
        return $CODE;
    }

    /**
     * Прорисовка формы под иконку
     * @param string $CODE код иконки
     */
    function setTab($CODE) {
        $this->_CODE.='
	 <table cellpadding="0" cellspacing="0" border="0" width="100%">
	 <tr>
		   '.$CODE.'<td align="right">&nbsp;</td>
     </tr>
	 </table>
	 ';
    }

    /**
     * Компиляция результата
     */
    function Compile() {
        $compile = '
     <table width="100%" cellpadding="0" cellpadding="0" style="border: 1px;border-style: outset;">
     <tr>
       <td style="padding-left:5">
		   '.$this->_CODE.'
	    </td>
     </tr>
     </table>';
        echo $compile;
    }
}

/**
 * Библиотека внешних интерфейсов
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopGUI
 */
class PHPShopFrontInterface extends PHPShopInterface {
    var $css;
    var $js;

    /**
     * Компиляция результата
     * @return string
     */
    function Compile() {

        if($this->numRows>10 and !$this->razmer) $this->razmer="height:450px;";

        if(!empty($this->css)) $compile.='<LINK href="'.$this->css.'" type="text/css" rel="stylesheet">';
        if(!empty($this->js)) $compile.='<SCRIPT language="JavaScript" src="'.$this->js.'"></SCRIPT>';

        $compile.='<div style="'.$this->razmer.';overflow:auto;">
	       <table cellpadding="0" class="phpshop-gui" cellspacing="1" border="0">'.$this->_CODE.'</table></div>';
        return $compile;
    }


    /**
     * Выдача содержимого
     * @return string
     */
    function getContent() {
        return $this->_CODE;
    }


}

/**
 * Библиотека дерева каталогов
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopGUI
 */
class CatalogTree {

    /**
     * Конструктор
     */
    function CatalogTree($table) {
        $this->table=$table;
        PHPShopObj::loadClass("lang");
        $this->dis="<script type=\"text/javascript\">
    <!--
    d = new dTree('d');
       ";
    }

    /**
     * Добавление каталога в дерево
     * @param int $n идентификатор
     * @param int $id родитель
     * @param string $name наименование
     * @param string $icon иконка
     */
    function addcat($n,$id,$name,$icon=false) {
        $name=__($name);
        $this->dis.="d.add($n,$id,'$name','$name','','','','$icon');";
    }


    /**
     * Запрос к БД
     * @param string $sql SQL запрос
     * @return mixed
     */
    function sql($sql) {
        $PHPShopOrm = new PHPShopOrm($this->table);
        return $PHPShopOrm->query($sql);
    }

    /**
     * Проверка на наличие каталогов
     * @param int $n ид катлога
     * @return int
     */
    function chek($n) {
        return mysql_num_rows($this->sql("select id from ".$this->table." where parent_to='$n'"));
    }

    /**
     * Рекурсионный проход по каталогам
     * @param int $n идентификатор
     * @return string
     */
    function add($n) {
        $disp='';
        $result=$this->sql("select * from ".$this->table." where parent_to='$n' order by num");
        while($row = mysql_fetch_array($result)) {
            $i=0;
            $id=$row['id'];
            $name=$row['name'];
            $parent_to=$row['parent_to'];
            $num=$this->chek($id);

            if($i<$num)// если есть еще каталоги
            {
                $disp.="d.add($id,$n,'$name','');
                        ".$this->add($id);
            }
            else// если нет каталогов
            {
                $disp.="d.add($id,$n,'$name','".$this->name($parent_to,$name)."');";
            }
        }
        return $disp;
    }


    /**
     * Имя каталога по идентификатору
     * @param int $n идентификатор каталога
     * @return string
     */
    function name($n) {
        $result=$this->sql("select name from ".$this->table." where id='$n'");
        $row = mysql_fetch_array($result);
        $name=$row['name'];
        return $name." => ".$catalog;
    }

    /**
     * Вывод дерева каталогов
     */
    function disp() {
        $this->dis.="
         document.write(d);
        //-->
       </script>";

        // Локализация
        writeLangFile();

        echo $this->dis;
    }


    /**
     * Рассчет дерева каталогов
     */
    function create() {
        $result=$this->sql("select * from ".$this->table." where parent_to=0 order by num");
        $i=0;
        $j=0;
        $dis='';
        while($row = mysql_fetch_array($result)) {
            $id=$row['id'];
            $name=$row['name'];
            $num=$this->chek($id);
            if($num>0)
                $this->dis.="
  d.add($id,0,'$name','');
                        ".$this->add($id)."
  ";
            else $this->dis.="
  d.add($id,0,'$name','$name');
                        ".$this->add($id)."
  ";
            $i++;
        }


        // Открытие категории
        if(!empty($_GET['category'])) {
            $this->dis.="d.openTo(".$_GET['category'].", true);";
        }

    }
}
?>