<?php

include_once dirname(__FILE__) . '/../class/WbSeller.php';

function addWbsellerProductTab($data) {
    global $PHPShopGUI;

    // Размер названия поля
    $PHPShopGUI->field_col = 4;

    $tab = $PHPShopGUI->setField(null, $PHPShopGUI->setCheckbox('export_wb_new', 1, 'Включить экспорт в WB', $data['export_wb']));
    if (!empty($data['export_wb_task_status']))
        $tab .= $PHPShopGUI->setField('Статус товара', $PHPShopGUI->setText('<span class="text-success">Загружен ' . PHPShopDate::get($data['export_wb_task_status'], true) . '</span>'));

    // Валюты
    $PHPShopValutaArray = new PHPShopValutaArray();
    $valuta_array = $PHPShopValutaArray->getArray();
    if (is_array($valuta_array))
        foreach ($valuta_array as $val) {
            if ($data['baseinputvaluta'] == $val['id']) {
                $valuta_def_name = $val['code'];
            }
        }

    $tab .= $PHPShopGUI->setField('Цена WB', $PHPShopGUI->setInputText(null, 'price_wb_new', $data['price_wb'], 150, $valuta_def_name), 2);
    $tab .= $PHPShopGUI->setField('Артикул WB', $PHPShopGUI->setInputText(null, 'export_wb_id_new', $data['export_wb_id'], 150, $PHPShopGUI->setLink('https://www.wildberries.ru/catalog/' . $data['export_wb_id'] . '/detail.aspx', '<span class=\'glyphicon glyphicon-eye-open\'></span>', '_blank', false, __('Перейте на сайт WB'))));
    $tab .= $PHPShopGUI->setField("Баркод WB", $PHPShopGUI->setInputText(null, 'barcode_wb_new', $data['barcode_wb'], 150));

    $PHPShopGUI->addTab(array("WB", $tab, true));
}

function WbsellerUpdate() {

    // Отключение Ozon
    if (!isset($_POST['export_wb_new']) and ! isset($_POST['ajax'])) {
        $_POST['export_wb_new'] = 0;
        $_POST['export_wb_task_status_new'] = '';
        $_POST['export_wb_id_new'] = '';
    }

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
    $data = $PHPShopOrm->getOne(['*'], ['id' => '=' . (int) $_POST['rowID']]);

    if (isset($_POST['enabled_new']) and empty($_POST['enabled_new']))
        $_POST['items_new'] = $_POST['export_wb_new'] = 0;

    if (isset($_POST['items_new']))
        $data['items'] = (int) $_POST['items_new'];

    if (isset($_POST['price_new']))
        $data['price'] = $_POST['price_new'];

    if (isset($_POST['export_wb_new']))
        $data['export_wb'] = (int) $_POST['export_wb_new'];

    if (isset($_POST['barcode_wb_new']))
        $data['barcode_wb'] = (string) $_POST['barcode_wb_new'];

    if (!empty($data['export_wb'])) {

        $WbSeller = new WbSeller();
        if (empty($data['export_wb_task_status'])) {

            // Загрузка
            $result = $WbSeller->sendProducts([$data]);
            //$WbSeller->sendImages($data);

            if (is_array($result) and empty($result['error'])) {
                $PHPShopOrm->update(['export_wb_task_status_new' => time()], ['id' => '=' . (int) $_POST['rowID']]);
            }
        }

        // Фото
        $WbSeller->sendImages($data);

        // Склад
        $WbSeller->setProductStock([$data]);

        // Информация
        if (empty($data['export_wb_id'])) {

            $export_wb_id = $WbSeller->getProduct([$data['uid']])['data'][0]['nmID'];

            if (!empty($export_wb_id))
                $PHPShopOrm->update(['export_wb_id_new' => $export_wb_id], ['id' => '=' . (int) $_POST['rowID']]);
        }
    } else
        $PHPShopOrm->update(['export_wb_task_status_new' => '', 'export_wb_id_new' => 0], ['id' => '=' . (int) $_POST['rowID']]);
}

$addHandler = array(
    'actionStart' => 'addWbsellerProductTab',
    'actionDelete' => false,
    'actionUpdate' => 'WbsellerUpdate'
);
?>