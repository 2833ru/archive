<?
$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
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
//$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.button.button_system"));


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    //if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;
    //$action = $PHPShopOrm->update($_POST);
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля";
    $PHPShopGUI->size="500,450";




    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Promotions'","Настройки",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    $Info = '
    <p><b>1. Товары (имя_шаблона/product):</b></p>
    <ul>
        <li><b>@promotionInfo@</b> - описание акции <i>(Только в шаблон <u>phpshop/templates/имя шаблона/product/main_product_forma_full.tpl</u>)</i></p>
        <li><b>@promotionsIcon@</b> - иконка акции</li>
    </ul>';

    // Содержание закладки 1
    $Tab2=$PHPShopGUI->setInfo($Info, 100, '95%');
    
    // Содержание закладки 2
    $Tab3=$PHPShopGUI->setPay('О модуле',false);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Настройка",$Tab2,270), array("О Модуле",$Tab3,270));

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


