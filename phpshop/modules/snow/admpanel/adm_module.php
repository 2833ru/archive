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
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.snow.snow_system"));


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $PHPShopOrm->debug=false;
    $action = $PHPShopOrm->update($_POST);
    return $action;
}


function actionStart() {
    global $PHPShopGUI,$_classPath,$PHPShopOrm;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля Снег";
    $PHPShopGUI->size="500,450";

    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);
    
    // Вывод
    switch($flag) {
        case 1: $s1='selected';
            break;
        case 2: $s2='selected';
            break;
    }

    $e_value[]=array('JQuery Snow 2.0',1,$s1);
    $e_value[]=array('Snow 1.0',2,$s2);

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Снег'","Настройки подключения",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");


    $Tab1.=$PHPShopGUI->setField('Тип подключения',$PHPShopGUI->setSelect('flag_new',$e_value,150).'<br>* JQuery Snow требует подключения отдельно библиотеки <a href="http://jquery.com/" target="_blank">JQuery</a>. Подходит для новых шаблонов White_brick и подобных.');
    $Tab1.=$PHPShopGUI->setInputText('Цвет снега', 'color_new',$color,100);

    // Форма регистрации
    $Tab3=$PHPShopGUI->setPay($serial,false);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,270),array("О Модуле",$Tab3,270));

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