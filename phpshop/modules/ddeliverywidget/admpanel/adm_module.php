<?php

PHPShopObj::loadClass("order");
PHPShopObj::loadClass("delivery");

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ddeliverywidget.ddeliverywidget_system"));

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $action = $PHPShopOrm->update(array('version_new' => $new_version));

}

// Функция обновления
function actionUpdate() {
    global $PHPShopModules;


    // Доставки
    if (isset($_POST['delivery_id_new'])) {
        if (is_array($_POST['delivery_id_new'])) {
            foreach ($_POST['delivery_id_new'] as $val) {
                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['delivery']);
                $PHPShopOrm->update(array('is_mod_new' => 2), array('id' => '=' . intval($val)));
            }
            $_POST['delivery_id_new'] = @implode(',', $_POST['delivery_id_new']);
        }
    }

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ddeliverywidget.ddeliverywidget_system"));
    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);

    header('Location: ?path=modules&id=' . $_GET['id']);

    return $action;
}

function getLinkOption($key) {
    //global $_classPath;
    //include($_classPath . 'modules/ddeliverywidget/class/ddeliverywidget.class.php');
    //$DDeliveryHelper = new DDeliveryHelper();
    //$result = $DDeliveryHelper->request($key . '/pam.json', false);
    
    return 'https://ddelivery.ru/cabinet/widgets/cart?shopId='.$key;
    //return str_replace('http://','https://',$result['data']['url']);
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();

    $PHPShopGUI->addJSFiles('../modules/ddeliverywidget/admpanel/gui/ddeliverywidget.gui.js');

    $link = getLinkOption($data['shop_id']);

    // Доставка
    $PHPShopDeliveryArray = new PHPShopDeliveryArray(array('is_folder' => "!='1'", 'enabled' => "='1'"));

    $DeliveryArray = $PHPShopDeliveryArray->getArray();
    if (is_array($DeliveryArray))
        foreach ($DeliveryArray as $delivery) {

            // Длинные наименования
            if (strpos($delivery['city'], '.')) {
                $name = explode(".", $delivery['city']);
                $delivery['city'] = $name[0];
            }

            if (in_array($delivery['id'], @explode(",", $data['delivery_id'])))
                $delivery_id = $delivery['id'];
            else
                $delivery_id = null;

            $delivery_value[] = array($delivery['city'], $delivery['id'], $delivery_id);
        }


    $Tab1 = $PHPShopGUI->setField('API ключ', $PHPShopGUI->setInputText(false, 'key_new', $data['key'], 300));
    $Tab1.=$PHPShopGUI->setField('ID магазина', $PHPShopGUI->setInputText(false, 'shop_id_new', $data['shop_id'], 300, '<a id="link-activate-ddelivery" data-key="' . $data['key'] . '" href="#">Активировать</a>'));
    $Tab1.=$PHPShopGUI->setField('Доставка', $PHPShopGUI->setSelect('delivery_id_new[]', $delivery_value, 300, null, false, $search = false, false, $size = 1, $multiple = true));

    // Доступые статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();

    $status[] = array('Новый заказ', 0, $data['status']);
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status) {
            $status[] = array($order_status['name'], $order_status['id'], $data['status']);
        }

    // Статус заказа
    $Tab1.= $PHPShopGUI->setField('Статус для отправки', $PHPShopGUI->setSelect('status_new', $status, 300));
    
    // Карточный виджет
    $Tab1.=$PHPShopGUI->setField('Карточный виджет:', $PHPShopGUI->setRadio('prod_enabled_new', 1, 'Включить', $data['prod_enabled']) . $PHPShopGUI->setRadio('prod_enabled_new', 2, 'Выключить', $data['prod_enabled']),false,'Вывод карточного виджета на страницах товаров.');

    $Tab2 = $PHPShopGUI->setFrame('seopult', $link, '99%', '700', 'none', 0);


    $info = '<h4>Получение API ключа</h4>
       <ol>
        <li>Зарегистрироваться в <a href="https://ddelivery.ru/" target="_blank">Ddelivery.ru</a>.</li>
        <li>Перейти по ссылке  <a target="_blank" href="https://cabinet.ddelivery.ru/user2/#/shops">Склады и магазины</a>.</li>
        <li>"API ключ" скопировать в одноименное поле настроек модуля.</li>
        <li>"ID магазина" скопировать из интерфейса редактирования магазина (вызывается на предыдущем шаге) в виде 5-значной цифры.</li>
        </ol>
        
       <h4>Настройка модуля</h4>
        <ol>
        <li>Включить виджет доставки кликом по ссылке "Активировать".</li>
        <li>Выбрать имя доставки для активации модуля.</li>
        <li>Выбрать статус заказа для автоматической отправки заказа на сервер Ddelivery.ru.</li>
        </ol>

       <h4>Настройка карточного виджета</h4>
        <ol>
        <li>При включенном режиме "Карточный виджет" нужно добавить переменную <code>@ddeliveryCart@</code> в свой шаблон.</li>
        <li>Для персонализации формы вывода отредактируйте шаблон <code>phpshop/modules/ddeliverywidget/templates/product_widget.tpl</code></li>
        </ol>
        
       <h4>Настройка доставки</h4>
        <ol>
        <li>В карточке редактирования доставки в закладке <kbd>DDelivery</kbd> настроить дополнительный параметр сохранения стоимости доставки для модуля. Опция "Не изменять стоимость" должна быть активна.</li>
        </ol>

';

    $Tab3 = $PHPShopGUI->setInfo($info);

    // Форма регистрации
    $Tab4 = $PHPShopGUI->setPay($serial = false, false, $data['version'], true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true), array("Службы доставки", $Tab2), array("Инструкция", $Tab3), array("О Модуле", $Tab4));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>