<?
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
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.w4aphotogal_2.w4aphotogal_2_system"));


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

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
    $PHPShopGUI->setHeader("Настройка Photo Gallery","Настройки",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");
    
         $info='Модуль создает уникальную галерею в карточке товара. <a href="http://www.web4.su/dorabotka_funktsionala_phpshop.html" target="blank">Подробнее на сайте разработчика</a>.
            <p>
        <p></p>
';
    $Tab2=$PHPShopGUI->setInfo($info, 200, '96%');
	
     $w4a_info='			
		<div style="padding: 0 0 15px 0; font-size: 20px; color:#63b6e6; text-align:center;">
								Функционал разработан веб-студией:<br/>
			</div>
			<div style="text-align:center;">
			<a href="http://www.web4.su" target="_blank"  tabindex="1000" title="Перейти на сайт разработчика"><img style="border:0;width: 180px;" src="http://web4.su/UserFiles/logo_02.png"></a>
			</div>
			<div style="padding: 15px 0 0 0; font-size: 12px;text-align:center;">
								Официальный представитель PHPShop <br/>
			<span style="color:#63b6e6;"> дизайн, верстка, кодинг, интеграция и<br/>
								доработки любого уровня сложности  для PHPShop</span><br/>
								<a href="http://www.web4.su" target="_blank"  tabindex="1000">Перейти на сайт разработчика</a>
			</div>
';
	$w4aTab4=$PHPShopGUI->setInfo($w4a_info, 200, '96%');   
    
    $Tab3=$PHPShopGUI->setPay($serial,false);
    
    // Вывод формы закладки
   $PHPShopGUI->setTab(array("О Модуле",$Tab3,270),array("О разработчике",$w4aTab4,270));
    
    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            $PHPShopGUI->setInput("button","","Закрыть","right",70,"return onCancel();","but");
    
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


