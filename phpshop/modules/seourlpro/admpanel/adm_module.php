<?php
$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("orm");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");

// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");

// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.seourlpro.seourlpro_system"));

// Преобразование символов в латиницу
function setLatin($str) {
    $str=strtolower($str);
    $str=str_replace("/", "", $str);
    $str=str_replace("\\", "", $str);
    $str=str_replace("(", "", $str);
    $str=str_replace(")", "", $str);
    $str=str_replace(":", "", $str);
    $str=str_replace(" ", "-", $str);
    $str=str_replace("\"", "", $str);
    $str=str_replace(".", "", $str);
    $str=str_replace("«", "", $str);
    $str=str_replace("»", "", $str);
    $str=str_replace("ь", "", $str);
    $str=str_replace("ъ", "", $str);

    $_Array=array(
            "а"=>"a",
            "б"=>"b",
            "в"=>"v",
            "г"=>"g",
            "д"=>"d",
            "е"=>"e",
            "ё"=>"e",
            "ж"=>"zh",
            "з"=>"z",
            "и"=>"i",
            "й"=>"i",
            "к"=>"k",
            "л"=>"l",
            "м"=>"m",
            "н"=>"n",
            "о"=>"o",
            "п"=>"p",
            "р"=>"r",
            "с"=>"s",
            "т"=>"t",
            "у"=>"u",
            "ф"=>"f",
            "х"=>"h",
            "ц"=>"c",
            "ч"=>"ch",
            "ш"=>"sh",
            "щ"=>"sh",
            "ы"=>"y",
            "э"=>"e",
            "ю"=>"uy",
            "я"=>"ya",
            "А"=>"a",
            "Б"=>"b",
            "В"=>"v",
            "Г"=>"g",
            "Д"=>"d",
            "E"=>"e",
            "Ё"=>"e",
            "Ж"=>"gh",
            "З"=>"z",
            "И"=>"i",
            "Й"=>"i",
            "К"=>"k",
            "Л"=>"l",
            "М"=>"m",
            "Н"=>"n",
            "О"=>"o",
            "П"=>"p",
            "Р"=>"r",
            "С"=>"s",
            "Т"=>"t",
            "У"=>"u",
            "Ф"=>"f",
            "Х"=>"h",
            "Ц"=>"c",
            "Ч"=>"ch",
            "Ш"=>"sh",
            "Щ"=>"sh",
            "Э"=>"e",
            "Ю"=>"uy",
            "Я"=>"ya",
            "."=>"",
            ","=>"",
            "$"=>"i",
            "%"=>"i",
            "&"=>"and");

    $chars = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);

    foreach($chars as $val)
        if(empty($_Array[$val])) @$new_str.=$val;
        else $new_str.=$_Array[$val];

    return $new_str;
}

// Автогенерация урлов каталогов фото
function setGenerationPhoto() {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name22']);
    $data = $PHPShopOrm->select(array('id','name'),false,false,array('limit'=>1000));
    
    if(is_array($data))
      foreach($data as $val){
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name22']);
        $array['seoname_new']=setLatin($val['name']);
        $PHPShopOrm->update($array,array('id'=>'='.$val['id']));
    }
          
}

// Автогенерация урлов каталогов
function setGeneration() {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name']);
    $data = $PHPShopOrm->select(array('id','name'),false,false,array('limit'=>1000));
    
    if(is_array($data))
      foreach($data as $val){
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name']);
        $array['seoname_new']=setLatin($val['name']);
        $PHPShopOrm->update($array,array('id'=>'='.$val['id']));
    }
          
}

// Автогенерация урлов новостей
function setGenerationNews() {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name8']);
    $data = $PHPShopOrm->select(array('id','title'),false,false,array('limit'=>1000));

    if(is_array($data))
      foreach($data as $val){
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name8']);
        $array['seo_name_new']=setLatin($val['title']);
        $PHPShopOrm->update($array,array('id'=>'='.$val['id']));
    }

}


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    
    // Автогенерация урлов
    if(!empty($_POST['generation'])) setGeneration();
    if(!empty($_POST['generationnews'])) setGenerationNews();
    if(!empty($_POST['generationphoto'])) setGenerationPhoto();
    
    $action = $PHPShopOrm->update($_POST);
    return $action;
}


function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm;
    
    
    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля";
    $PHPShopGUI->size="500,450";
    
    
    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);
    
    
    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'SeoUrlPro'","Настройки",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");
    

      // Содержание закладки
    $Info = '
<p>При включеном режиме "SEO пагинация" следует добавить переменную <b>@seourl_canonical@</b> в шаблон phpshop/templates/имя шаблона/main/shop.tpl, в результате будет добавлена ссылка link rel="canonical" с точным адресом для отсеивания дублей страниц описания списка товаров.</p>
';
    $Tab1=$PHPShopGUI->setField('SEO пагинация', $PHPShopGUI->setRadio('paginator_new', 2, 'Включить', $paginator).$PHPShopGUI->setRadio('paginator_new', 1, 'Выключить', $paginator));
    $Tab1.= $PHPShopGUI->setLine('<br>').$PHPShopGUI->setInfo($Info, 190, '95%');
    
    $Tab2=$PHPShopGUI->setPay($serial);
    
    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,270),array("О Модуле",$Tab2,270));
    
    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            $PHPShopGUI->setInput("button","","Отмена","right",70,"return onCancel();","but").
            $PHPShopGUI->setInput("submit","editID","ОК","right",70,"","but","actionUpdate");
    
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

if($UserChek->statusPHPSHOP < 2) {
    
    // Вывод формы при старте
    $PHPShopGUI->setLoader($_POST['editID'],'actionStart');
    
    // Обработка событий 
    $PHPShopGUI->getAction();
    
}else $UserChek->BadUserFormaWindow();

?>