<?php

include_once dirname(__FILE__) . '/../class/WbSeller.php';
$TitlePage = __('Заказы из WB');

function actionStart() {
    global $PHPShopInterface, $PHPShopSystem, $TitlePage, $select_name;

    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->addJSFiles('./js/bootstrap-datetimepicker.min.js', '../modules/wbseller/admpanel/gui/order.gui.js');
    $PHPShopInterface->addCSSFiles('./css/bootstrap-datetimepicker.min.css');
    $PHPShopInterface->setActionPanel($TitlePage, $select_name, false);
    $PHPShopInterface->setCaption(array("&#8470; Задания", "7%"), array("Создано", "15%"), array("Иконка", "7%"), array("Наименование", "40%"), array("Итого", "10%", array('align' => 'right')));

    // Знак рубля
    if ($PHPShopSystem->getDefaultValutaIso() == 'RUB' or $PHPShopSystem->getDefaultValutaIso() == 'RUR')
        $currency = ' <span class="rubznak hidden-xs">p</span>';
    else
        $currency = $PHPShopSystem->getDefaultValutaCode();

    if (isset($_GET['date_start']))
        $date_start = $_GET['date_start'];
    else
        $date_start = PHPShopDate::get((time() - 2592000), false, true);

    if (isset($_GET['date_end']))
        $date_end = $_GET['date_end'];
    else
        $date_end = PHPShopDate::get((time() - 1), false, true);

    if (empty($_GET['status']))
        $_GET['status'] = 'new';

    $WbSeller = new WbSeller();

    // Заказы
    $orders = $WbSeller->getOrderList($date_start, $date_end, $_GET['status'])['orders'];

    $total = 0;

    if (is_array($orders))
        foreach ($orders as $row) {

            // Заказ уже загружен
            if ($WbSeller->checkOrderBase($row['id']))
                continue;

            if ($WbSeller->type == 2) {
                $type_name = __('Арт');
                $type = 'uid';
            } else {
                $type_name = 'ID';
                $type = 'id';
            }

            // Данные по товару
            $prod = (new PHPShopOrm($GLOBALS['SysValue']['base']['products']))->getOne(['id,uid,name,pic_small'], [$type => '="' . (string) $row['article'].'"']);
            if (empty($prod))
                continue;

            if (!empty($prod['pic_small']))
                $icon = '<img src="' . $prod['pic_small'] . '" onerror="this.onerror = null;this.src = \'./images/no_photo.gif\'" class="media-object">';
            else
                $icon = '<img class="media-object" src="./images/no_photo.gif">';

            // Артикул
            if (!empty($prod['uid']))
                $uid = '<div class="text-muted">' . __('Арт') . ' ' . $prod['uid'] . '</div>';
            else
                $uid = null;


            $PHPShopInterface->setRow(['name' => $row['id'], 'link' => '?path=modules.dir.wbseller.order&id=' . $row['id'] . '&date1=' . $_GET['date_start'] . '&date2=' . $_GET['date_end']], $WbSeller->getTime($row['createdAt']), $icon, array('name' => $prod['name'], 'addon' => $uid, 'link' => '?path=product&id=' . $prod['id']), round($row['price'] / 100) . $currency);
        }

    $order_status_value[] = array(__('Новые заказы'), 'new', $_GET['status']);
    $order_status_value[] = array(__('Все заказы'), 'all', $_GET['status']);

    /*
      foreach ($OzonSeller->status_list as $k => $status_val) {
      $order_status_value[] = array(__($status_val), $k, $_GET['status']);
      } */


    $searchforma = $PHPShopInterface->setInputDate("date_start", $date_start, 'margin-bottom:10px', null, 'Дата начала отбора');
    $searchforma .= $PHPShopInterface->setInputDate("date_end", $date_end, false, null, 'Дата конца отбора');
    $searchforma .= $PHPShopInterface->setInputArg(array('type' => 'hidden', 'name' => 'path', 'value' => $_GET['path']));
    $searchforma .= $PHPShopInterface->setSelect('status', $order_status_value, '100%');
    $searchforma .= $PHPShopInterface->setButton('Показать', 'search', 'btn-order-search pull-right');

    if (isset($_GET['date_start']))
        $searchforma .= $PHPShopInterface->setButton('Сброс', 'remove', 'btn-order-cancel pull-left');
    else
        $searchforma .= $PHPShopInterface->setButton('Сброс', 'remove', 'btn-order-cancel hide pull-left');


    // Правый сайдбар
    if ($total > 0) {
        $stat = '<div class="order-stat-container">' . __('Сумма:') . ' <b>' . number_format($total, 2, ',', ' ') . '</b> ' . $currency . '<br>' . __('Количество:') . ' <b>' . count($orders) . '</b> ' . __('шт.');
        $sidebarright[] = array('title' => 'Статистика', 'content' => $stat);
    }

    $sidebarright[] = array('title' => 'Интервал', 'content' => $PHPShopInterface->setForm($searchforma, false, "order_search", false, false, 'form-sidebar'));

    $PHPShopInterface->setSidebarRight($sidebarright, 2, 'hidden-xs');

    $PHPShopInterface->Compile(2);
}
