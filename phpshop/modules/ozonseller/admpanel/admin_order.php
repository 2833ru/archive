<?php

include_once dirname(__FILE__) . '/../class/OzonSeller.php';
$TitlePage = __('Заказы из Ozon');

function actionStart() {
    global $PHPShopInterface, $PHPShopSystem, $TitlePage, $select_name;

    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->addJSFiles('./js/bootstrap-datetimepicker.min.js', '../modules/ozonseller/admpanel/gui/order.gui.js');
    $PHPShopInterface->addCSSFiles('./css/bootstrap-datetimepicker.min.css');
    $PHPShopInterface->setActionPanel($TitlePage, $select_name, false);
    $PHPShopInterface->setCaption(array("&#8470; Заказа", "15%"), array("Тип", "10%"), array("Статус", "15%"), array("Поступил", "15%"), array("Обработан", "15%"), array("Доставка", "15%"), array("Итого", "10%", array('align' => 'right')));

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

    $OzonSeller = new OzonSeller();


    // Заказы FBS
    $ordersFbs = $OzonSeller->getOrderListFbs($date_start, $date_end, $_GET['status']);
    if (is_array($ordersFbs['result'])) {
        foreach ($ordersFbs['result'] as $k => $order_list)
            $ordersFbs['result'][$k]['type'] = 'fbs';
    }

    // Отладка FBS
    /*
      $ordersFbs['result'][] = [
      'order_number' => '56274213-0001-1',
      'in_process_at' => '03.03.2022',
      'addressee' => ['name' => 'Денис', 'phone' => '98562853696'],
      'tracking_number'=>'',
      'product' => [['price' => 3000, 'quantity' => 1]]
      ]; */


    // Заказы FBO
    $ordersFbo = $OzonSeller->getOrderListFbo($date_start, $date_end, $_GET['status']);

    // Отладка FBO
    /*
      $ordersFbo['result'][] = [
      'posting_number' => '56274213-0001-2',
      'in_process_at' => '08.03.2022',
      'addressee' => ['name' => 'Семен', 'phone' => '98562853696'],
      'products' => [['price' => 5000, 'quantity' => 1]]
      ]; */

    if (is_array($ordersFbs['result']) and is_array($ordersFbo['result']))
        $orders = array_merge($ordersFbs['result'], $ordersFbo['result']);
    elseif(is_array($ordersFbs['result']))
        $orders =$ordersFbs['result'];
    else $orders =$ordersFbo['result'];

    $total = 0;

    if (is_array($orders))
        foreach ($orders as $row) {

            // Заказ уже загружен
            if ($OzonSeller->checkOrderBase($row['posting_number']))
                continue;

            $sum = 0;
            if (is_array($row['products']))
                foreach ($row['products'] as $product)
                    $sum += $product['price'] * $product['quantity'];

            if ($row['type'] == 'fbs')
                $type = "FBS";
            else
                $type = "FBO";

            $total += $sum;

            $PHPShopInterface->setRow(['name' => $row['posting_number'], 'link' => '?path=modules.dir.ozonseller.order&id=' . $row['posting_number'] . '&type=' . $type], $type, __($OzonSeller->getStatus($row['status'])), $OzonSeller->getTime($row['created_at']), $OzonSeller->getTime($row['in_process_at']), $OzonSeller->getTime($row['shipment_date']), $sum . $currency);
        }

    $order_status_value[] = array(__('Все заказы'), null, $_GET['status']);
    foreach ($OzonSeller->status_list as $k => $status_val) {
        $order_status_value[] = array(__($status_val), $k, $_GET['status']);
    }


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
