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
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.countcat.countcat_system"));


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;
    $action = $PHPShopOrm->update($_POST);
    
    if(!empty($_POST['clean'])){
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
        $PHPShopOrm->update(array('count' => '0'), false, false);
    }
    return $action;
}


function actionStart() {
    global $PHPShopGUI,$_classPath,$PHPShopOrm;
    
    
    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля";
    $PHPShopGUI->size="500,450";
    
    
    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);
    
    
    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Count Cat'","Настройки",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");
    
    $info='Для вывода количества товаров в подкаталоге добавьте переменную <b>@catalogCount@</b> в шаблон вывода подкаталога 
        phpshop/templates/имя шаблона/catalog/pogcatalog_forma.tpl
        <p>При первом включении модуля будет прозведен автоматический расчет товаров в подкаталогах с занесением в базу модуля. 
        Для дальнейшей корректировки этого параметра используйте одноименное поле в карточке редактировния подкаталога в закладке
        "Count".</p>
';
    
    $Tab1=$PHPShopGUI->setInfo($info, 200, '96%');
    $Tab1.=$PHPShopGUI->setCheckbox("enabled_new",1,'Добавить количество товара к имени каталога',$enabled);
    $Tab1.=$PHPShopGUI->setLine().$PHPShopGUI->setCheckbox("clean",1,'Пересчитать ранее установленные значения кол-ва товара в категориях',0);
    
    $Tab2=$PHPShopGUI->setPay($serial,false);
    
    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Инструкция",$Tab1,270),array("О Модуле",$Tab2,270));
    
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


