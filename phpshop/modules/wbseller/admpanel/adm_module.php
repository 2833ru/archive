<?php

include_once dirname(__FILE__) . '/../class/WbSeller.php';

PHPShopObj::loadClass("order");
PHPShopObj::loadClass("delivery");

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.wbseller.wbseller_system"));
$WbSeller = new WbSeller();

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $PHPShopOrm->update(array('version_new' => $new_version));
}

// Функция обновления
function actionUpdate() {
    global $PHPShopModules,$PHPShopOrm;

    // Корректировка пустых значений
    $PHPShopOrm->updateZeroVars('link_new');

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.wbseller.wbseller_system"));
    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);

    header('Location: ?path=modules&id=' . $_GET['id']);

    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $WbSeller, $PHPShopModules;

    $PHPShopGUI->field_col = 4;

    // Выборка
    $data = $PHPShopOrm->select();

    // Статус
    $status[] = [__('Новый заказ'), 0, $data['status']];
    $statusArray = (new PHPShopOrm('phpshop_order_status'))->getList(['id', 'name']);
    foreach ($statusArray as $statusParam) {
        $status[] = [$statusParam['name'], $statusParam['id'], $data['status']];
    }

    // Доступые статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();
    $order_status_value[] = array(__('Новый заказ'), 0, $data['status']);
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status)
            $order_status_value[] = array($order_status['name'], $order_status['id'], $data['status']);


    $Tab1 = $PHPShopGUI->setField('API key', $PHPShopGUI->setTextarea('token_new', $data['token'], false, '100%', '100'));
    $Tab1 .= $PHPShopGUI->setField('Статус нового заказа', $PHPShopGUI->setSelect('status_new', $order_status_value, '100%'));
    $Tab1 .= $PHPShopGUI->setField('Ключ обновления', $PHPShopGUI->setRadio("type_new", 1, "ID товара", $data['type']) . $PHPShopGUI->setRadio("type_new", 2, "Артикул товара", $data['type']));
    $Tab1 .= $PHPShopGUI->setField('Ссылка на товар', $PHPShopGUI->setCheckbox('link_new', 1, 'Показать ссылку на товар в Wildberries', $data['link']));

    $Tab1 = $PHPShopGUI->setCollapse('Настройки', $Tab1);


    if ($data['fee_type'] == 1) {
        $status_pre = '-';
    } else {
        $status_pre = '+';
    }

    $getWarehouse = $WbSeller->getWarehouse();
    if (is_array($getWarehouse))
        foreach ($getWarehouse as $warehouse)
            $warehouse_value[] = array(PHPShopString::utf8_win1251($warehouse['name'],true), $warehouse['id'], $data['warehouse']);

    $Tab3 = $PHPShopGUI->setCollapse('Цены', $PHPShopGUI->setField('Колонка цен WB', $PHPShopGUI->setSelect('price_new', $PHPShopGUI->setSelectValue($data['price'], 5), 100)) .
            $PHPShopGUI->setField('Наценка', $PHPShopGUI->setInputText($status_pre, 'fee_new', $data['fee'], 100, '%')) .
            $PHPShopGUI->setField('Действие', $PHPShopGUI->setRadio("fee_type_new", 1, "Понижение", $data['fee_type']) . $PHPShopGUI->setRadio("fee_type_new", 2, "Повышение", $data['fee_type'])) .
            $PHPShopGUI->setField("Склад WB", $PHPShopGUI->setSelect('warehouse_new', $warehouse_value, '100%'))
    );

    // Инструкция
    $Tab2 = $PHPShopGUI->loadLib('tab_info', $data, '../modules/' . $_GET['id'] . '/admpanel/');

    // Форма регистрации
    $Tab4 = $PHPShopGUI->setPay(false, false, $data['version'], true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1 . $Tab3, true, false, true), array("Инструкция", $Tab2), array("О Модуле", $Tab4));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * Подбор категорий
 */
function actionCategorySearch() {
    global $WbSeller;

    $data = $WbSeller->getTree(PHPShopString::win_utf8($_POST['words']))['data'];

    if (is_array($data)) {
        foreach ($data as $row) {

            $result .= '<a href=\'#\' class=\'select-search\' data-name=\'' . PHPShopString::utf8_win1251($row['objectName'], true) . '\'>' . PHPShopString::utf8_win1251($row['parentName'], true) . ' &rarr; ' . PHPShopString::utf8_win1251($row['objectName'], true) . '</a><br>';
        }
        $result .= '<button type="button" class="close pull-right" aria-label="Close"><span aria-hidden="true">&times;</span></button>';

        exit($result);
    } else
        exit();
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
