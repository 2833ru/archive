<?php

/**
 * Вывод полной информации по заказу пользователя
 * @author PHPShop Software
 * @version 1.3
 * @package PHPShopCoreFunction
 * @param obj $obj объект класса
 * @param Int $tip флаг вызова [1 - личный кабинет], [2 - онлайн проверка заказа]
 */
function action_order_info($obj, $tip) {

    // Проверка личный кабинет 
    if ($tip == 1) {
        $order_info = $_GET['order_info'];
        $where = array('uid' => '="' . $order_info . '"');
    }
    // Он-лайн проверка заказа
    elseif ($tip == 2) {
        $order_info = $_REQUEST['order'];
        $where = array('uid' => '="' . $order_info . '"', 'user' => '=0', 'datas' => '<' . time("U") - ($obj->order_live * 2592000));
    }
    if (PHPShopSecurity::true_order($order_info)) {

        $PHPShopOrm = new PHPShopOrm($obj->getValue('base.orders'));
        $PHPShopOrm->debug = $obj->debug;
        $row = $PHPShopOrm->select(array('*'), $where, false, array('limit' => 1));

        // Библиотека работы с заказом
        $PHPShopOrderFunction = new PHPShopOrderFunction(false);

        // Валюта
        $currency = $PHPShopOrderFunction->default_valuta_code;

        // Статусы заказов
        $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();


        if (is_array($row)) {

            // Импортируем данные
            $PHPShopOrderFunction->import($row);

            // Проверка он-лайн метода
            if ($tip == 2) {
                if (PHPShopSecurity::true_email($_REQUEST['mail']))
                    if ($_REQUEST['mail'] != $PHPShopOrderFunction->getMail())
                        return $obj->action_index();
            }

            // Список покупок
            $cart = $PHPShopOrderFunction->cart('usercartforma', array('obj' => $obj, 'currency' => $currency));

            // Цифровой контент
            if ($PHPShopOrderStatusArray->getParam($row['statusi'] . '.sklad_action') == 1 and $obj->PHPShopSystem->getSerilizeParam('admoption.digital_product_enabled') == 1) {
                $files = PHPShopText::tr('<b>' . __('Файлы') . '</b>', $PHPShopOrderFunction->cart('userfiles', array('obj' => $obj)), '-');
            }


            // Заголовок
//            $title = PHPShopText::div(PHPShopText::b(__('Информация по заказу №') . $row['uid']) . __('от') . PHPShopDate::dataV($row['datas']), $align = "left", $style = false, $id = 'allspec');
            $title = PHPShopText::div(PHPShopText::notice(__('Информация по заказу №') . $row['uid'] . __(' от ') . PHPShopDate::dataV($row['datas'],false)));


            // Доставка
            $delivery = $PHPShopOrderFunction->delivery('userdeleveryforma', array('obj' => $obj, 'currency' => $currency, 'row' => $row));

            // Юр. данные
            $yurData = $PHPShopOrderFunction->yurData($row);

            // Итого
            $total = PHPShopText::tr(PHPShopText::b(__('Итого с учетом скидки ') . $PHPShopOrderFunction->getDiscount() . '%'), PHPShopText::b($PHPShopOrderFunction->getNum() + 1) . ' ' . __('шт.'), PHPShopText::b($PHPShopOrderFunction->getTotal()) . ' ' . $currency);

            // Комментарии по заказу
            if ($PHPShopOrderFunction->getSerilizeParam('status.maneger') != '' and $PHPShopOrderFunction->getParam('ddelivery_token') != '')
                $comment = PHPShopText::p(PHPShopText::message($PHPShopOrderFunction->getSerilizeParam('status.maneger')));
            else
                $comment = null;



            // Документооборот
            $docs = userorderdoclink($row, $obj);

            // Таблица
            $slide = PHPShopText::slide('Order');
            $slide .= PHPShopText::slide('checkout');
            $table = $slide . $title;


            $editTime = $PHPShopOrderFunction->getStatusTime();
            if (!$editTime)
                $editTime = "не обработан";
            // Время обработки заказа
            $time = PHPShopText::b($PHPShopOrderFunction->getStatus($PHPShopOrderStatusArray), 'color:' . $PHPShopOrderFunction->getStatusColor($PHPShopOrderStatusArray)) .
                    PHPShopText::br() . PHPShopText::b(__('Время обработки заказа:')) . ' ' .
                    $editTime . $comment;
            // Способ оплаты
            $payment = userorderpaymentlink($obj, $PHPShopOrderFunction, $tip, $row);
            // Описание столбцов
            $caption = $obj->caption(__('Статус заказа'), __('Способ оплаты'));
            $table .= PHPShopText::p(PHPShopText::table($caption . $payment = PHPShopText::tr($time, $payment), 3, 1, 'left', '99%', false, 0, 'allspecwhite', 'list table table-striped table-bordered'));

            // Описание столбцов
            if (!empty($yurData)) {
                $caption = $obj->caption(__('Вариант доставки'), __('Адрес доставки'), __('Юридические данные'));
                $temp = PHPShopText::tr($delivery['name'], $delivery['adres'], $yurData);
            } else {
                $caption = $obj->caption(__('Вариант доставки'), __('Адрес доставки'));
                $temp = PHPShopText::tr($delivery['name'], $delivery['adres']);
            }

            $table .= PHPShopText::p(PHPShopText::table($caption . $temp, 3, 1, 'left', '99%', false, 0, 'allspecwhite', 'list table table-striped table-bordered'));

            // содержание заказа.
            // Описание столбцов
            $caption = $obj->caption(__('Наименование'), __('Кол-во'), __('Сумма'));
            $table .= PHPShopText::p(PHPShopText::table($caption . $cart . $delivery['tr'] . $total . $docs . $files, 3, 1, 'left', '99%', false, 0, 'allspecwhite', 'list table table-striped table-bordered'));


            $obj->set('formaContent', $table, true);
        }
        else
            $obj->action_index();
    }
}

/**
 * Цифровой контент
 */
function userfiles($val, $option) {
    global $PHPShopModules;

    $dis = null;

    // Перехват модуля в начале функции
    $hook = $PHPShopModules->setHookHandler(__FUNCTION__, __FUNCTION__, $val, $option, 'START');
    if ($hook)
        return $hook;


    $PHPShopOrm = new PHPShopOrm($option['obj']->getValue('base.products'));
    $row = $PHPShopOrm->select(array('files'), array('id' => '=' . $val['id']), false, array('limit' => 1));
    if (is_array($row)) {
        $files = unserialize($row['files']);
        if (is_array($files)) {
            foreach ($files as $cfile) {
                $dis.=PHPShopText::img('images/shop/action_save.gif', 3, 'absmiddle');
                $F = $option['obj']->link_encode($cfile['path']);
                $link = '../files/filesSave.php?F=' . $F;
                $dis.=PHPShopText::a($link, urldecode($cfile['name']), urldecode($cfile['name']), false, false, '_blank');
                $dis.=PHPShopText::br();
            }
        }
    }

    return $dis;
}

/**
 * Корзина покупок в заказе
 */
function usercartforma($val, $option) {
    global $PHPShopModules;

    // Перехват модуля в начале функции
    $hook = $PHPShopModules->setHookHandler(__FUNCTION__, __FUNCTION__, $val, $option, 'START');
    if ($hook)
        return $hook;

    // Проверка подтипа товара, выдача ссылки главного товара
    if (empty($val['parent']))
        $link = '/shop/UID_' . $val['id'] . '.html';
    else
        $link = '/shop/UID_' . $val['parent'] . '.html';

//    $icon = PHPShopText::img('phpshop/lib/templates/icon/accept.png', $hspace = 5, 'absmiddle');
    $dis = PHPShopText::tr(PHPShopText::a($link, $icon . $val['name'], $val['name'], false, false, '_blank', 'b'), $val['num'], $val['total']. ' ' . $option['currency']);
    return $dis;
}

/**
 * Доставка
 */
function userdeleveryforma($val, $option) {
    global $PHPShopModules;

    // Перехват модуля в начале функции
    $hook = $PHPShopModules->setHookHandler(__FUNCTION__, __FUNCTION__, $option, $val,  'START');
    if ($hook)
        return $hook;

    $data_fields = unserialize($val['data_fields']);
    if (is_array($data_fields)) {
        $num = $data_fields[num];
        asort($num);
        $enabled = $data_fields[enabled];
        foreach ($num as $key => $value) {
            if ($enabled[$key]['enabled'] == 1) {
                $adres .= PHPShopText::b($enabled[$key][name] . ": ") . $option['row'][$key] . "<br>";
            }
        }
    }

    if (!$adres)
        $adres = "Не требуется";

    $icon = PHPShopText::img('phpshop/lib/templates/icon/lorry.gif', $hspace = 5, 'absmiddle');
    $dis = PHPShopText::tr($icon . __('Доставка') . ' - ' . $val['name'], 1, $val['price'] . ' ' . $option['currency']);
    return array('tr' => $dis, 'name' => $val['name'], 'adres' => $adres);
}

/**
 * Документооборот
 */
function userorderdoclink($val, $obj) {

    $PHPShopOrm = new PHPShopOrm($obj->getValue('base.1c_docs'));
    $PHPShopOrm->debug = $obj->debug;
    $data = $PHPShopOrm->select(array('*'), array('uid' => '=' . $val['id']), false, array('limit' => 1000));

    if (is_array($data)) {

        // Описание столбцов
        $dis = $obj->caption(__('Документооборот'), __('Дата'), __('Загрузка'));
        $n = $val['id'];
        foreach ($data as $row) {

            // Счета
            if ($obj->PHPShopSystem->ifValue('1c_load_accounts')) {
                $link_def = '../files/docsSave.php?orderId=' . $n . '&list=accounts&datas=' . $row['datas'];
                $link_html = '../files/docsSave.php?orderId=' . $n . '&list=accounts&tip=html&datas=' . $row['datas'];
                $link_doc = '../files/docsSave.php?orderId=' . $n . '&list=accounts&tip=doc&datas=' . $row['datas'];
                $link_xls = '../files/docsSave.php?orderId=' . $n . '&list=accounts&tip=xls&datas=' . $row['datas'];
                $dis.=PHPShopText::tr(PHPShopText::a($link_def, __('Счет на оплату'), false, false, false, '_blank', 'b'), PHPShopDate::dataV($row['datas']), PHPShopText::a($link_html, __('HTML'), __('Формат Web'), false, false, '_blank', 'b') . ' ' .
                        PHPShopText::a($link_doc, __('DOC'), __('Формат Word'), false, false, '_blank', 'b') . ' ' .
                        PHPShopText::a($link_xls, __('XLS'), __('Формат Excel'), false, false, '_blank', 'b'));
            }

            // Счета-фактуры
            if (!empty($row['datas_f']) and $obj->PHPShopSystem->ifValue('1c_load_invoice')) {
                $link_def = '../files/docsSave.php?orderId=' . $n . '&list=invoice&datas=' . $row['datas'];
                $link_html = '../files/docsSave.php?orderId=' . $n . '&list=invoice&tip=html&datas=' . $row['datas'];
                $link_doc = '../files/docsSave.php?orderId=' . $n . '&list=invoice&tip=doc&datas=' . $row['datas'];
                $link_xls = '../files/docsSave.php?orderId=' . $n . '&list=invoice&tip=xls&datas=' . $row['datas'];
                $dis.=PHPShopText::tr(PHPShopText::a($link_def, __('Счет-фактура'), false, false, false, '_blank', 'b'), PHPShopDate::dataV($row['datas_f']), PHPShopText::a($link_html, __('HTML'), __('Формат Web'), false, false, '_blank', 'b') . ' ' .
                        PHPShopText::a($link_doc, __('DOC'), __('Формат Word'), false, false, '_blank', 'b') . ' ' .
                        PHPShopText::a($link_xls, __('XLS'), __('Формат Excel'), false, false, '_blank', 'b'));
            }
        }

        // Перехват модуля
        $hook = $obj->setHook(__FUNCTION__, __FUNCTION__, array('row' => $data, 'val' => $val));
        if ($hook) {
            return $hook;
        }

        return $dis;
    }
}

/**
 * Ссылка на оплату
 */
function userorderpaymentlink($obj, $PHPShopOrderFunction, $tip, $row) {
    global $PHPShopSystem, $PHPShopBase;

    $disp = null;
    PHPShopObj::loadClass('payment');
    $PHPShopPayment = new PHPShopPayment($PHPShopOrderFunction->order_metod_id);
    $path = $PHPShopPayment->getPath();
    $name = $PHPShopPayment->getName();
    $id = $row['id'];
    $datas = $row['datas'];
    $icon = $PHPShopPayment->getParam('icon');

    if (!empty($icon))
        $icon = PHPShopText::img($icon, 5, 'absmiddle','max-height:50px');

    // Ссылки на оплаты
    switch ($path) {

        // Сообщение
        case("message"):
            $disp.=$icon.PHPShopText::b($name);
            break;

        // Счет в банк
        case("bank"):
            if (!$PHPShopSystem->ifValue('1c_load_accounts')) {

                $disp = PHPShopText::a("phpshop/forms/account/forma.html?orderId=$id&tip=$tip&datas=$datas", $icon . $name, $name, false, false, '_blank', 'b');
            } else {
                $disp.=PHPShopText::b($name) . '.<br>' . __('Ожидайте счета, после проведения документа<br> он автоматически появится в данном разделе<br> личного кабинета.');
            }
            break;

        // Квитанция Сбербанка
        case("sberbank"):
            $disp.=PHPShopText::a("phpshop/forms/receipt/forma.html?orderId=$id&tip=$tip&datas=$datas", $icon . $name, $name, false, false, '_blank', 'b');
            break;

        /*
         * Попытка подключить функцию обработчик [name]_users_repay() из папки с именем платежной системы /payment/[name]/users.php
         * Пример реализации /payment/webmoney/users.php
         */
        default:
            $users_file = './payment/' . $path . '/users.php';
            $users_function = $path . '_users_repay';
            if (is_file($users_file)) {
                include_once($users_file);
                if (function_exists($users_function)) {
                    $disp = $icon.call_user_func_array($users_function, array(&$obj, $PHPShopOrderFunction));
                }
            }
            else
                $disp.=$icon.PHPShopText::b($name);
            break;
    }

    // Перехват модуля
    $hook = $obj->setHook(__FUNCTION__, __FUNCTION__, $PHPShopOrderFunction);
    if ($hook) {
        $disp.=$hook;
    }

    return $disp;
}

?>