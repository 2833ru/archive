<?php

$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("date");
PHPShopObj::loadClass("file");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm("phpshop_orders");


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm,$_classPath;

    $PHPShopOrm->debug=false;
    $gz=true;

    $pole1=PHPShopDate::GetUnixTime($_POST['active_date_ot_new']);
    $pole2=PHPShopDate::GetUnixTime($_POST['active_date_do_new']);

    if($_POST['active_check_new']==1) {
        if($pole1!='') {
            $sort = "where datas<='$pole2' and datas>='$pole1'";
        }
    }

    $sql = "select * from `phpshop_orders` $sort order by id desc";
    $query = mysql_query($sql);
    $row = mysql_fetch_array($query);

    //$data = $PHPShopOrm->select($select = array('*'), $where, array('order' => 'id DESC'));
    do {
        $data[]= $row;
    }
    while ($row = mysql_fetch_array($query));

    $sqld = "select * from `phpshop_payment_systems`";
    $queryd = mysql_query($sqld);
    $rowd = mysql_fetch_array($queryd);
    do {
        $payment[] = $rowd;
    }
    while ($rowd = mysql_fetch_array($queryd));


    if(isset($data)) {
        foreach ($data as $key => $value) {
            $datas = PHPShopDate::dataV($value['datas']);
            $id = $value['id'];
            $uid = $value['uid'];

            if(isset($id)) {
                $orders = unserialize($value['orders']);
                $Person = $orders['Person'];
                $Cart = $orders['Cart']['cart'];
                $name_person = $value['fio'];
                $tel = $value['tel'];
                $adr_name = $value['city'].' '.$value['index'].' '.$value['street'].' '.$value['house'].' '.$value['flat'].' '.$value['dop_info'];
                $OrderMetodId = $Person['order_metod'];

                //Запрос доставки
                if($OrderMetodId!='') {
                    foreach ($payment as $pay) {
                        if($pay['id']==$OrderMetodId) {
                            $OrderMetod = $pay['name'];
                            break;
                        }
                    }
                }

                //пустая строка
                $csv_p = " ; ; ; ; ; ; ; ; ; \n";

                $n = 1;
                foreach ($Cart as $cart) {
                    if($n==1):
                        $csv .= $csv_p.$datas.";".$name_person.";".$uid.";".$cart['id'].";".$cart['name'].";".$cart['num'].";".$cart['price']*$cart['num'].";".$tel.";".$adr_name.";".$OrderMetod."\n";
                        $n++;
                    else:
                        $csv .= " ; ; ;".$cart['id'].";".$cart['name'].";".$cart['num'].";".$cart['price']*$cart['num']."; ; ; \n";
                    endif;
                }   
            }
        }
    }

    $csv = "Дата;Клиент;Номер заказа;ID;Корзина;Количество;Цена;Телефон;Адрес;Оплата\n" . $csv;

    $sorce = $_classPath."admpanel/csv/base_" . date("d_m_y_His") . ".csv";
    PHPShopFile::write($sorce, $csv);
    
    if($gz){
    //PHPShopFile::gzcompressfile($sorce);
    header("Location: " . $sorce);
    }
    else header("Location: " . $sorce);

    return $action;
}

/**
 * Выбор характеристики
 */
function getSortValue($n) {
    global $PHPShopGUI;
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']);
    $PHPShopOrm->debug=false;
    $data = $PHPShopOrm->select(array('*'),array('filtr'=>"='1'",'goodoption'=>"!='1'"),array('order'=>'num'),array('limit'=>100));
    if(is_array($data))
        foreach($data as $row) {

            if($n == $row['id']) $sel='selected';
            else $sel=false;

            $value[]=array($row['name'],$row['id'],$sel);
        }

    return $PHPShopGUI->setSelect('sort_new',$value,300);
}

function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля Подбор по параметрам";
    $PHPShopGUI->size="500,450";

    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);

    //Подключаем JS
    $PHPShopGUI->addJSFiles('../../../admpanel/java/popup_lib.js', '../../../admpanel/java/dateselector.js');
    $PHPShopGUI->addCSSFiles('../../../admpanel/skins/'.$_SESSION['theme'].'/dateselector.css');

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Экспорт заказов'","Настройки подключения",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    $Tab1.=$PHPShopGUI->setField($PHPShopGUI->setCheckbox("active_check_new", 1, "<b>Экспорт</b> <i>(Установите галочку, если нужно выбрать период)</i>", $active_check, "left"),
        $PHPShopGUI->setText('от','left', $style='padding:9px !important;').$PHPShopGUI->setInput("text", "active_date_ot_new", $active_date_ot, "left", 70, false, false, false, false, $PHPShopGUI->setImage("../../../admpanel/icon/date.gif", 16, 16, 'absmiddle', "5", $style = 'float:none', $onclick = "popUpCalendar(this, product_edit.active_date_ot_new, 'dd-mm-yyyy');")) .
        $PHPShopGUI->setText('до','left', $style='padding:9px !important;').$PHPShopGUI->setInput("text", "active_date_do_new", $active_date_do, "left", 70, false, false, false, false, $PHPShopGUI->setImage("../../../admpanel/icon/date.gif", 16, 16, 'absmiddle', "5", $style = 'float:none', $onclick = "popUpCalendar(this, product_edit.active_date_do_new, 'dd-mm-yyyy');"))        
    );

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,270));

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