<?
$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("date");
PHPShopObj::loadClass("string");


$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");

$PHPShopSystem = new PHPShopSystem();

// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->debug_close_window=true;
$PHPShopGUI->reload='top';
$PHPShopGUI->ajax="'modules','pechkin'";
$PHPShopGUI->includeJava='<SCRIPT language="JavaScript" src="../../../lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>';
$PHPShopGUI->dir=$_classPath."admpanel/";

include("../class/PHPechkin.php");
//Печкин
    $PHPechkinL = new PHPechkin();
    $PHPechkinL->__construct($_SESSION['pechkinLogin'],$_SESSION['pechkinPass']);

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.pechkin.pechkin_forms"));


// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm,$PHPechkinL;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Редактирование промо-акции";
    $PHPShopGUI->size="650,540";


    // Выборка
    $data = $PHPShopOrm->select(array('*'),array('id'=>'='.$_GET['id']));
    @extract($data);

    $PHPShopGUI->addJSFiles('../../../admpanel/java/popup_lib.js');


    // Графический заголовок окна
    $PHPShopGUI->setHeader("Редактирование авто-загрузки","",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    //подключение к печкину
    $lists_get = $PHPechkinL->lists_get($list_id);
    $base_name = PHPShopString::utf8_win1251($lists_get['row']['name']);


    $Tab1=$PHPShopGUI->setField('Адресная база: <b>'.$base_name.'</b> (ID: '.$list_id.')',
        $PHPShopGUI->setText($PHPShopGUI->setRadio("enabled_new", 1, "Включить", $enabled) .
        $PHPShopGUI->setRadio("enabled_new", 0, "Выключить",$enabled),'left')
    );

    $param_ar = explode('::', $param);
    foreach ($param_ar as $par) {
        if($par!='0') {
            if($par=='name') {
                $html_param .= 'Имя,';
            }
            if($par=='datas') {
                $html_param .= 'Дата создания пользователя,';
            }
            if($par=='tel') {
                $html_param .= 'Телефон,';
            }
            if($par=='tel_new') {
                $html_param .= 'Телефон,';
            }
            if($par=='country_new') {
                $html_param .= 'Страна,';
            }
            if($par=='state_new') {
                $html_param .= 'Регион,';
            }
            if($par=='city_new') {
                $html_param .= 'Город,';
            }
            if($par=='index_new') {
                $html_param .= 'Индекс,';
            }
            if($par=='house_new_new') {
                $html_param .= 'Дом,';
            }
            if($par=='porch_new') {
                $html_param .= 'Подъезд,';
            }
            if($par=='flat_new') {
                $html_param .= 'Квартира,';
            }

        }
    }

    
    $Tab1.=$PHPShopGUI->setField('Данные',
        $PHPShopGUI->setText(
            '<u>Имя адресной базы:</u> <b>'.$base_name.'</b><br>' .
            '<u>ID адресной базы:</u> <b>'.$list_id.'</b><br>' .
            '<u>Тип:</u> <b>'.($type==1 ? 'Подписчики' : 'Пользователи').'</b><br>' .
            '<u>Дополнительные поля:</u> <b>'.$html_param.'</b><br>' .
            '<u>Дата создания:</u> <b>'.$date_create.'</b><br>'
        ,'left')
    );
    
                            
    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,405));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            $PHPShopGUI->setInput("button","","Отмена","right",70,"return onCancel();","but").
            $PHPShopGUI->setInput("submit","delID","Удалить","right",70,"","but","actionDelete").
            $PHPShopGUI->setInput("submit","editID","ОК","right",70,"","but","actionUpdate");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

//Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;

    $action = $PHPShopOrm->update($_POST,array('id'=>'='.$_POST['newsID']));
    return $action;
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->delete(array('id'=>'='.$_POST['newsID']));
    return $action;
}

if($UserChek->statusPHPSHOP < 2) {

    // Вывод формы при старте
    $PHPShopGUI->setAction($_GET['id'],'actionStart','none');

    // Обработка событий
    $PHPShopGUI->getAction();

}else $UserChek->BadUserFormaWindow();

?>


