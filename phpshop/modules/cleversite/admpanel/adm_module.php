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
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.cleversite.cleversite_system"));


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
    $PHPShopGUI->title="Настройка модуля Cleversite";
    $PHPShopGUI->size="500,450";

    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);  

    $e_value[]=array('JQuery cleversite 2.0',1,$s1);
    $e_value[]=array('cleversite 1.0',2,$s2);

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Cleversite'","Настройки подключения",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

	$ContentField1=$PHPShopGUI->setInputText('Логин:', 'client_new',$client,'100%');
	$ContentField1.=$PHPShopGUI->setInput('password', 'password_new',$password, "none", '100%', "return true", false, false, 'Пароль:', false);
	$Tab1 = $PHPShopGUI->setField("Данные для авторизации", $ContentField1);
	$Tab1.=$PHPShopGUI->setField('Адрес сайта:', $PHPShopGUI->setInputText(null, 'site_new',$site,'100%','* Данный адрес должен совпадать с введенным Вами в личном кабинете адресом сайта, на котором требуется отобразить модуль'),'none');
	
    $Info='<h4>Для вставки данного модуля следуйте инструкции:</h4>
        <ol>
        <li> Зарегистрируйтесь на сайте <a href="http://cleversite.ru/" target="_blank"> cleversite.ru</a>
		<li> Получите на почту письмо с регистрационными данными.
		<li> Выберете в личном кабинете какие виджеты Вы хотите отобразить на своем сайте.
        <li> Скопируйте Ваш Логин и вставьте его в поле "Логин" на вкладке "Основное" текущего окна настройки модуля.
		<li> Скопируйте Ваш Пароль и вставьте его в поле "Пароль" на вкладке "Основное" текущего окна настройки модуля.
		<li> Укажите адрес сайта, который вы добавили в настройки личного кабинета на сайте <a href="http://cleversite.ru/" target="_blank">cleversite.ru</a> 
		и вставьте его в поле "Сайт" на вкладке "Основное" текущего окна настройки модуля.
		<li> Сохраните введенные Вами данные.
		</ol>';
	$Tab2=$PHPShopGUI->setInfo($Info, '200px', '95%');
		
    // Форма регистрации
    $Tab3=$PHPShopGUI->setPay($serial,false);

    $About='Если у Вас возникли вопросы, то можите пишисать оператору на <a href="http://cleversite.ru/" target="_blank">нашем сайту</a> в онлайн-консультант или отправить сообщение на help@cleversite.ru, принимаем Ваши обращения 24 часа в сутки. Мы поможем установить код на Ваш сайт и начать работу в системе.';
    $Tab3.=$PHPShopGUI->setInfo($About,50,'95%');

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,270),array("Инструкция",$Tab2,270), array("О Модуле",$Tab3,270));

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