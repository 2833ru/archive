<?php

$_classPath="../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
$PHPShopBase->chekAdmin();

PHPShopObj::loadClass("system");
$PHPShopSystem = new PHPShopSystem();

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->title="Регистрация модулей";


// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name19']);

// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");

function Modules($mod_id=1) {
    global $SysValue,$PHPShopGUI,$PHPShopModules;
    $dir="../../modules/";

    // Данные из сбоника модулей
    @$db=$PHPShopModules->getXml("http://phpshopcms.ru/modmoney/modinfo.php");

    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {



                if($file!="." and $file!=".." and $file!="index.html")
                    foreach($db as $k=>$v)
                        if($v['name']==$file) {
                            if($mod_id == $v['id'])
                                $sel="selected";
                            else $sel="";
                            $value[$file]=array($file,$v['id'],$sel);
                        }
            }
            closedir($dh);
        }

    }

    return $PHPShopGUI->setSelect('mod_id',$value,200,'left',false,false,225,5);
}

function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm,$PHPShopModules;

    // Выборка
    $data = $PHPShopOrm->select(array('login,mail'),array('id'=>'='.$_SESSION['idPHPSHOP']),false,array('limit'=>1));
    extract($data);

    $option=unserialize($admoption);
    if($option['image_save_source']==1) $image_save_source="checked";
    if($option['rss_graber_enabled']==1) $rss_graber_enabled="checked";
    if($option['cart_enabled']==1) $cart_enabled="checked";
    if($option['editor_enabled']==1) $editor_enabled="checked";


    if(!empty($spec_num)) $fl="checked";
    if(!empty($option['image_save_source'])) $image_save_source="checked";

    $PHPShopGUI->dir="../";
    $PHPShopGUI->size="510,460";
    $PHPShopGUI->imgPath="../icon/";

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Регистрация модулей","Укажите данные для добровольной региcтрации.",$PHPShopGUI->dir."img/i_visa_med[1].gif");

    // Рег. данные
    if(empty($_POST['login'])) $_POST['login']=$login;
    if(empty($_POST['mail'])) $_POST['mail']=$mail;

    // Содержание закладки 2
    $Tab1=$PHPShopGUI->setField('Доступные модули',Modules($_REQUEST['mod_id']),'left');
    $Tab1.=$PHPShopGUI->setField('Имя',$PHPShopGUI->setInput('text','name',$_POST['login'],$float="none",$size=200));
    $Tab1.=$PHPShopGUI->setField('E-mail',$PHPShopGUI->setInput('text','mail',$_POST['mail'],$float="none",$size=200));
    $Tab1.=$PHPShopGUI->setInput("hidden","reg","send","right",70,"","but");



    // Вывод формы закладки
    if(isset($_POST['reg'])) {
        $tab_caption='Шаг-2';

        // Добавление в базу
        @$db=$PHPShopModules->getXml("http://phpshopcms.ru/modmoney/reg.php?name=".$_POST['name']."&mail=".$_POST['mail']."&mod_id=".$_POST['mod_id']);
        $Tab1.=$PHPShopGUI->setField('Инструкция по оплате',$PHPShopGUI->setTextarea('sms',$db['sms'],$float="none",$width=210,$height=100));
    }
    else {
        $tab_caption='Шаг-1';
        $Tab1.=$PHPShopGUI->setButton("Добровольная регистрация",'key.png',230,50,$float="none",
                $onclick="GhekReg();");
    }

    $Info='<b>Уважаемый(ая) пользователь PHPShop CMS Free</b>
 <p>Если Вы используете понравившийся Вам модуль и хотите отблагодарить за его использование разработчика,
 то вы можете зарегистрировать этот модуль и получить персональный серийный номер, который можно использовать
 на неограниченном количестве своих сайтов.
 </p>
 <p>Для регистрации следует отправить SMS на короткий номер с определенным кодом, доступный после завершения регистрации.
 Стоимость SMS от 30 до 250 руб., в зависимости от тарифа, выбранным разработчиком модуля. </b>
 <p>Добровольная регистрация позволяет получать вознаграждение разработчикам, создающим дополнительные модули на безвозмездной основе.
 Вознаграждение позволит создавать больше полезных и нужных модулей для проекта <a href="http://phpshopcms.ru/doc/modules.html" target="_blank">PHPShop CMS Free</a>.
<p><b>Спасибо за поддержку проекта!</b>
';

    $Tab2=$PHPShopGUI->setInfo($Info,220);

    $PHPShopGUI->setTab(array($tab_caption,$Tab1,290),array('Описание',$Tab2,290));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=
            $PHPShopGUI->setInput("button","","Закрыть","right",70,"return onCancel();","but");

    // Футер
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