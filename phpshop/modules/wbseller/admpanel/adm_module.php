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
    global $PHPShopModules;


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


    $Tab1 = $PHPShopGUI->setField('API key', $PHPShopGUI->setTextarea('token_new', $data['token'],false, '100%','100'));
    $Tab1 .= $PHPShopGUI->setField('Статус нового заказа', $PHPShopGUI->setSelect('status_new', $order_status_value, '100%'));
    $Tab1 .= $PHPShopGUI->setField('Ключ обновления', $PHPShopGUI->setRadio("type_new", 1, "ID товара", $data['type']) . $PHPShopGUI->setRadio("type_new", 2, "Артикул товара", $data['type']));

    $Tab1 = $PHPShopGUI->setCollapse('Настройки', $Tab1);

    $info = '<h4>Настройка модуля</h4>
    <ol>
        <li>Зарегистрироваться в <a href="https://seller.wildberries.ru" target="_blank">WB Partners</a>.</li>
        <li>В личном кабинете WB Seller открыть <a href="https://seller.wildberries.ru/supplier-settings/access-to-api" target="_blank">Настройки - Доступ к  API</a>, создать новый токен (тип токена <kbd>Стандартный</kbd>, имя токена любое). Необходимо скопировать значение токена в поле <kbd>API key</kbd> в настройках модуля.
        </li>
        <li>В настройках модуля выбрать статус заказов, поступающих с WB.</li>
        <li>В настройках модуля выбрать склад WB. Если склад не создан, то его нужно предварительно создать в <a href="https://seller.wildberries.ru/marketplace-pass/warehouses" target="_blank">Мои склады и пропуска</a>.</li>
    </ol>

   <h4>Выгрузка товаров в WB</h4>
   WB принимает данные по товарам с четко указанными данными по категориям и характеристикам из базы WB.
   <ol>
    <li>В карточке редактирования категории в магазине сопоставить выбранную свою категорию с категорией WB в закладке <kbd>WB</kbd>, поле <kbd>Размещение в WB</kbd>. При наборе имени категории будет показано всплывающее окно категорий WB, доступных по поиску вводимых данных. Сохранить выбор и перегрузить страницу, после чего появится блок "Сопоставление характеристик с WB".</li>
    <li>Сопоставить или создать необходимые характеристики с указанными значениями.</li>
    <li>В карточке редактирования товара в магазине через закладку "Модули - WB" включить опцию <kbd>Включить экспорт в WB</kbd> и сохранить данные. Список товаров для выгрузки в WB доступен в разделе "Модули - WB Partners - Товары для WB".</li>
    <li>После успешной выгрузки товары появятся в разделе <a href="https://seller.wildberries.ru/new-goods" target="_blank">Товары - Карточки товаров - Созданные</a> в WB.</li>
    <li>Для выгрузки цен и остатков товаров в WB следует добавить новую задачу в модуль <a href="https://docs.phpshop.ru/moduli/razrabotchikam/cron" target="_blank">Задачи</a> с адресом запускаемого файла <code>phpshop/modules/wbseller/cron/products.php</code>. Остатки и цены выгружаются так же при редактировании карточки товара в магазине.</li>
  </ol>
  
  <h4>Загрузка заказов с WB</h4>
   <ol>
    <li>Список заказов для загрузки из WB доступен в разделе "Модули - WB Partners - Заказы из WB". По клику на номер заказа откроется карточка с описанием данных по заказу с WB. Для загрузки заказа используется кнопка <kbd>Загрузить заказ</kbd>. Загруженный заказ будет иметь статус, выбранный в настройках модуля. В поле "Примечания администратора" загруженного заказа будет информация о загрузке с WB и его номер. Для повторной загрузки заказа следует удалить его из базы заказов в магазине.</li>
    <li>В закладке "Дополнительно" предпросмотра заказа с WB выводится полная информация по заказу в виде массива данных.</li>
  </ol>
  
 <h4>Загрузка товаров с WB</h4>
   <ol>
    <li>Список товаров для загрузки из WB доступен в разделе "Модули - WB Partners- Товары из WB". По клику на название товара откроется карточка с описанием данных по товару с WB. Для загрузки товара используется кнопка <kbd>Загрузить товар</kbd>. Для повторной загрузки товара следует удалить его из базы товаров в магазине. Из WB загрузятся данные по товару, в том числе изображения и характеристики.</li>
  </ol>
';

    if ($data['fee_type'] == 1) {
        $status_pre = '-';
    } else {
        $status_pre = '+';
    }
    
    $getWarehouse = $WbSeller->getWarehouse();
    if (is_array($getWarehouse))
        foreach ($getWarehouse as $warehouse)
            $warehouse_value[] = array($warehouse['name'], $warehouse['id'], $data['warehouse']);

    $Tab3 = $PHPShopGUI->setCollapse('Цены', $PHPShopGUI->setField('Колонка цен WB', $PHPShopGUI->setSelect('price_new', $PHPShopGUI->setSelectValue($data['price'], 5), 100)) .
            $PHPShopGUI->setField('Наценка', $PHPShopGUI->setInputText($status_pre, 'fee_new', $data['fee'], 100, '%')) .
            $PHPShopGUI->setField('Действие', $PHPShopGUI->setRadio("fee_type_new", 1, "Понижение", $data['fee_type']) . $PHPShopGUI->setRadio("fee_type_new", 2, "Повышение", $data['fee_type'])) .
            $PHPShopGUI->setField("Склад WB", $PHPShopGUI->setSelect('warehouse_new', $warehouse_value, '100%'))
    );

    $Tab2 = $PHPShopGUI->setInfo($info);

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
 * Подбор пользователей
 */
function actionCategorySearch() {
    global $WbSeller;
    
    $data = $WbSeller->getTree(PHPShopString::win_utf8($_POST['words']))['data'];

    if (is_array($data)) {
        foreach ($data as $row) {

            $result .= '<a href=\'#\' class=\'select-search\' data-name=\'' . PHPShopString::utf8_win1251($row['objectName'],true) . '\'>' . PHPShopString::utf8_win1251($row['parentName'],true) . ' &rarr; ' . PHPShopString::utf8_win1251($row['objectName'],true) . '</a><br>';
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
