<?php

include_once dirname(__FILE__) . '/../class/OzonSeller.php';

PHPShopObj::loadClass("order");
PHPShopObj::loadClass("delivery");

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_system"));
$OzonSeller = new OzonSeller();

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
    global $PHPShopModules, $OzonSeller, $PHPShopOrm;

    // Синхронизация категорий
    if (!empty($_POST['load']))
        actionUpdateCategory();

    // Корректировка пустых значений
    $PHPShopOrm->updateZeroVars('link_new');

    // Складs
    if (is_array($_POST['warehouse'])) {


        $getWarehouse = $OzonSeller->getWarehouse();
        if (is_array($getWarehouse['result']))
            foreach ($getWarehouse['result'] as $warehouse) {

                if (is_array($_POST['warehouse'])) {
                    foreach ($_POST['warehouse'] as $val)
                        if ($warehouse['warehouse_id'] == $val)
                            $_POST['warehouse_new'][] = ['name' => PHPShopString::utf8_win1251($warehouse['name']), 'id' => $warehouse['warehouse_id']];
                }
            }


        $_POST['warehouse_new'] = serialize($_POST['warehouse_new']);
    } else
        $_POST['warehouse_new'] = "";

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_system"));
    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);

    header('Location: ?path=modules&id=' . $_GET['id']);

    return $action;
}

function setChildrenCategory($tree_array, $parent_to) {
    global $PHPShopModules;

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_categories"));

    if (is_array($tree_array)) {
        foreach ($tree_array as $category) {
            $PHPShopOrm->insert(['name_new' => PHPShopString::utf8_win1251($category['title']), 'id_new' => $category['category_id'], 'parent_to_new' => $parent_to]);

            if (is_array($category['children'])) {
                foreach ($category['children'] as $children) {
                    $PHPShopOrm->insert(['name_new' => PHPShopString::utf8_win1251($children['title']), 'id_new' => $children['category_id'], 'parent_to_new' => $category['category_id']]);
                    if (is_array($children['children']))
                        setChildrenCategory($children['children'], $children['category_id']);
                }
            }
        }
    }
}

// Синхронизация категорий
function actionUpdateCategory() {
    global $PHPShopModules, $OzonSeller;

    $getTree = $OzonSeller->getTree(['category_id' => 0]);
    $tree_array = $getTree['result'];

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_categories"));
    $PHPShopOrm->debug = false;

    // Очистка
    $PHPShopOrm->query('TRUNCATE TABLE `' . $PHPShopModules->getParam("base.ozonseller.ozonseller_categories") . '`');

    if (is_array($tree_array)) {
        foreach ($tree_array as $category) {
            $PHPShopOrm->insert(['name_new' => PHPShopString::utf8_win1251($category['title']), 'id_new' => $category['category_id'], 'parent_to_new' => 0]);

            if (is_array($category['children'])) {
                foreach ($category['children'] as $children) {
                    $PHPShopOrm->insert(['name_new' => PHPShopString::utf8_win1251($children['title']), 'id_new' => $children['category_id'], 'parent_to_new' => $category['category_id']]);
                    if (is_array($children['children']))
                        setChildrenCategory($children['children'], $children['category_id']);
                }
            }
        }
    }
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules, $OzonSeller;

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


    $Tab1 = $PHPShopGUI->setField('Пароль защиты файла', $PHPShopGUI->setInputText($_SERVER['SERVER_NAME'] . '/yml/?marketplace=ozon&pas=', 'password_new', $data['password'], '100%'));
    $Tab1 .= $PHPShopGUI->setField('Client id', $PHPShopGUI->setInputText(false, 'client_id_new', $data['client_id'], '100%'));
    $Tab1 .= $PHPShopGUI->setField('API key', $PHPShopGUI->setInputText(false, 'token_new', $data['token'], '100%'));
    $Tab1 .= $PHPShopGUI->setField('Статус нового заказа', $PHPShopGUI->setSelect('status_new', $order_status_value, '100%'));
    $Tab1 .= $PHPShopGUI->setField('Ключ обновления', $PHPShopGUI->setRadio("type_new", 1, "ID товара", $data['type']) . $PHPShopGUI->setRadio("type_new", 2, "Артикул товара", $data['type']));

    $PHPShopOrmCat = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_categories"));
    $category = $PHPShopOrmCat->select(['COUNT(`id`) as num']);

    $Tab1 .= $PHPShopGUI->setField('База категорий', $PHPShopGUI->setText($category['num'] . ' ' . __('записей в локальной базе'), null, false, false) . '<br>' . $PHPShopGUI->setCheckbox('load', 1, 'Обновить базу категорий товаров для OZON', 0));

    $Tab1 .= $PHPShopGUI->setField('Ссылка на товар', $PHPShopGUI->setCheckbox('link_new', 1, 'Показать ссылку на товар в OZON', $data['link']));

    $Tab1 = $PHPShopGUI->setCollapse('Настройки', $Tab1);

    if ($data['fee_type'] == 1) {
        $status_pre = '-';
    } else {
        $status_pre = '+';
    }

    $data['warehouse'] = unserialize($data['warehouse']);

    $getWarehouse = $OzonSeller->getWarehouse();
    if (is_array($getWarehouse['result']))
        foreach ($getWarehouse['result'] as $warehouse) {

            if (is_array($data['warehouse'])) {
                $selected = null;
                foreach ($data['warehouse'] as $val)
                    if ($warehouse['warehouse_id'] == $val['id'])
                        $selected = "selected";
            }

            $warehouse_value[] = array(PHPShopString::utf8_win1251($warehouse['name'], true), $warehouse['warehouse_id'], $selected);
        }

    $Tab3 = $PHPShopGUI->setCollapse('Цены', $PHPShopGUI->setField('Колонка цен OZON', $PHPShopGUI->setSelect('price_new', $PHPShopGUI->setSelectValue($data['price'], 5), 100)) .
            $PHPShopGUI->setField('Наценка', $PHPShopGUI->setInputText($status_pre, 'fee_new', $data['fee'], 100, '%')) .
            $PHPShopGUI->setField('Действие', $PHPShopGUI->setRadio("fee_type_new", 1, "Понижение", $data['fee_type']) . $PHPShopGUI->setRadio("fee_type_new", 2, "Повышение", $data['fee_type'])) .
            $PHPShopGUI->setField("Основной склад", $PHPShopGUI->setSelect('warehouse[]', $warehouse_value, '100%', false, false, false, false, 1, true), 1, 'OZON склады, сопоставляемые с главным складом магазина')
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

    $PHPShopOrm = new PHPShopOrm('phpshop_modules_ozonseller_categories');
    $data = $PHPShopOrm->getList(['*'], ['name' => " LIKE '%" . PHPShopString::utf8_win1251($_POST['words'], true) . "%'", 'parent_to' => '!=0']);
    if (is_array($data)) {
        foreach ($data as $row) {

            $parent = $PHPShopOrm->getOne(['name'], ['id' => '=' . $row['parent_to']])['name'];

            $result .= '<a href=\'#\' class=\'select-search\' data-id=\'' . $row['id'] . '\'  data-name=\'' . PHPShopString::utf8_win1251($row['name'], true) . '\'    >' . $parent . ' &rarr; ' . $row['name'] . '</a><br>';
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
