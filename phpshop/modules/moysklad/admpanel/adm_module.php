<?php
PHPShopObj::loadClass('order');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['moysklad']['moysklad_system']);

function actionUpdate() {
    global $PHPShopOrm;
    $PHPShopOrm->debug = false;

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id=' . $_GET['id']);
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm,$_classPath;

    $data = $PHPShopOrm->select();
    include_once($_classPath . 'modules/moysklad/class/MoySklad.php');
    $MoySklad = new MoySklad();
    
        // Доступые статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();
    $order_status_value[] = array(__('Новый заказ'), 0, $data['status']);
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status)
            $order_status_value[] = array($order_status['name'], $order_status['id'], $data['status']);

    $Tab1 .= $PHPShopGUI->setField('Токен', $PHPShopGUI->setInputText(false, 'token_new', $data['token'], 400, '<a target="_blank" href="https://online.moysklad.ru/app/#token">'.__('Получить').'</a>'));

    if(empty($data['token'])) {
        $Tab1 .= $PHPShopGUI->setField(null,$PHPShopGUI->setAlert('Для доступа к дополнительным настройкам, введите "Токен" и нажмите "Сохранить"', 'warning',true,400));
    } else {
        try {
            $Tab1 .= $PHPShopGUI->setField('Организация', $PHPShopGUI->setSelect('organization_new', $MoySklad->getOrganizations($data['organization']), 400, null, false, false, false, 1, false));
            $Tab1 .= $PHPShopGUI->setField('Валюта в заказе', $PHPShopGUI->setSelect('currency_new', $MoySklad->getCurrencys($data['currency']), 400, null, false, false, false, 1, false));
            $Tab1 .= $PHPShopGUI->setField('Типы цен', $PHPShopGUI->setSelect('pricetype_new', $MoySklad->getPricetype($data['pricetype']), 400, null, false, false, false, 1, false));
            $Tab1 .= $PHPShopGUI->setField('Передача при статусе:', $PHPShopGUI->setSelect('status_new', $order_status_value, 400));
        } catch (\Exception $exception) {
            $Tab1 .= $exception->getMessage();
        }
    }

    // Инструкция
    $info = '
    <h4>Как подключиться к МойСклад?</h4>
<ol>
 <li>Зарегистрироваться на сайте <a href="https://moysklad.ru/register/?p=F264" target="_blank">МойСклад</a>
</li></ol>     


        <h4>Настройка модуля</h4>
        <ol>
<li>В поле "Токен" ввести токен к вашему аккаунту в системе МойСклад.</li>
<li>Выбрать организацию.</li>
<li>Выбрать валюту в заказе.</li>
<li>Выбрать тип цен.</li>
<li>Выбрать статус заказа для передачи данных.</li>
</ol>';

    $Tab2 = $PHPShopGUI->setInfo($info);
    $Tab3 = $PHPShopGUI->setPay($serial = false, false, $data['version'], true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true), array("Инструкция", $Tab2), array("О Модуле", $Tab3), array("Журнал операций", null, '?path=modules.dir.moysklad'));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>