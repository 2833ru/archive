<?php

$TitlePage = "Журнал операций";

function actionStart() {
    global $PHPShopInterface, $_classPath;

            $PHPShopInterface->size = "630,530";
        $PHPShopInterface->link = "../modules/paypal/admpanel/adm_paypalID.php";
    $PHPShopInterface->setCaption(array("Дата", "10%"), array("Заказ №", "10%"), array("Статус", "10%"), array("Функция", "50%"));

    // Настройки модуля
    PHPShopObj::loadClass("modules");
    $PHPShopModules = new PHPShopModules($_classPath . "modules/");

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.paypal.paypal_log"));
    $PHPShopOrm->debug = false;

    // Сортировка по дате
    if (empty($_REQUEST['var3']))
        $pole1 = date("U") - 86400;
    else
        $pole1 = PHPShopDate::GetUnixTime($_REQUEST['var3']) - 86400;

    if (empty($_REQUEST['var4']))
        $pole2 = date("U");
    else
        $pole2 = PHPShopDate::GetUnixTime($_REQUEST['var4']) + 86400;

    $where['date'] = ' BETWEEN ' . $pole1 . ' AND ' . $pole2;


    $data = $PHPShopOrm->select(array('*'), $where, array('order' => 'id DESC'), array('limit' => 100));

    if (is_array($data))
        foreach ($data as $row) {
            extract($row);

            $PHPShopInterface->setRow($id, PHPShopDate::get($date,true), $order_id, $status, $type);
        }

    $PHPShopIcon = new PHPShopIcon($start = 100);
    $PHPShopIcon->padding = 0;
    $PHPShopIcon->margin = 0;

    // Сортировка по дате
    if (empty($_REQUEST['var3']))
        $pole1 = PHPShopDate::get(date("U") - (86400), false);
    else
        $pole1 = $_REQUEST['var3'];

    if (empty($_REQUEST['var4']))
        $pole2 = PHPShopDate::get(date("U") + 86400, false);
    else
        $pole2 = $_REQUEST['var4'];

    // Календарь
    $Calendar = $PHPShopIcon->setForm(
            $PHPShopIcon->setInputText(false, 'pole1', $pole1, $size = 70, $description = false, $float = "left") .
            $PHPShopIcon->setImage("icon/date.gif", 16, 16, 'absmiddle', "5", $style = 'float:left', $onclick = "popUpCalendar(this, calendar.pole1, 'dd-mm-yyyy');") .
            $PHPShopIcon->setInputText(false, 'pole2', $pole2, $size = 70, $description = false, $float = "left") .
            $PHPShopIcon->setImage("icon/date.gif", 16, 16, 'absmiddle', "5", $style = 'float:left', $onclick = "popUpCalendar(this, calendar.pole2, 'dd-mm-yyyy');") .
            $PHPShopIcon->setInput("button", "date_but", "Показать", "right", 70, "DoReload('modules','paypal','paypal',calendar.pole1.value,calendar.pole2.value)")
            , $action = false, $name = "calendar", 'get');

    $Tab1.= $PHPShopIcon->add($Calendar, 270, 10, 5) .
            $PHPShopIcon->setBorder();

    $PHPShopIcon->setTab($Tab1);
    $PHPShopInterface->addTop($PHPShopIcon->Compile(true));

    $PHPShopInterface->Compile();
}

?>