<?php

include_once dirname(__FILE__) . '/../class/OzonSeller.php';

function addOzonsellerProductTab($data) {
    global $PHPShopGUI;

    $OzonSeller = new OzonSeller();

    // Размер названия поля
    $PHPShopGUI->field_col = 4;

    $tab = $PHPShopGUI->setField(null, $PHPShopGUI->setCheckbox('export_ozon_new', 1, 'Включить экспорт в OZON', $data['export_ozon']));
    $tab .= $PHPShopGUI->setInput("hidden", "export_ozon_task_id", $data['export_ozon_task_id']);
    $status = ['imported' => '<span class="text-success">Загружен</span>', 'error' => '<span class="text-warning">Ошибка</span>'];
    $error = null;
    if (!empty($data['export_ozon']) and $data['export_ozon_task_status'] != 'imported') {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);

        if (empty($data['export_ozon_task_id'])) {

            $products[] = $data;
            $result = $OzonSeller->sendProducts($products);
            $task_id = $data['export_ozon_task_id'] = $result['result']['task_id'];

            if (!empty($task_id)) {
                $PHPShopOrm->update(['export_ozon_task_id_new' => $task_id], ['id' => '=' . $data['id']]);
            } else
                $error = $result['message'];
        }

        $info = $OzonSeller->sendProductsInfo($data)['result']['items'][0];
        $PHPShopOrm->update(['export_ozon_task_status_new' => $info['status']], ['id' => '=' . $data['id']]);

        if (empty($info['status']))
            $info['status'] = 'error';

        if (is_array($info['errors'])) {
            foreach ($info['errors'] as $k => $er) {

                // Ссылки
                $er['description'] = preg_replace("~(http|https|ftp|ftps)://(.*?)(\s|\n|[,.?!](\s|\n)|$)~", '<a href="$1://$2" target="_blank">[ссылка]</a>$3', $er['description']);
                $error .= ($k + 1) . ' - ' . PHPShopString::utf8_win1251($er['description']) . '<br>';
            }
        }
    }

    if (empty($info['status']))
        $info['status'] = $data['export_ozon_task_status'];

    if (!empty($info['status']))
        $tab .= $PHPShopGUI->setField('Статус товара', $PHPShopGUI->setText($status[$info['status']]));

    // Валюты
    $PHPShopValutaArray = new PHPShopValutaArray();
    $valuta_array = $PHPShopValutaArray->getArray();
    if (is_array($valuta_array))
        foreach ($valuta_array as $val) {
            if ($data['baseinputvaluta'] == $val['id']) {
                $valuta_def_name = $val['code'];
            }
        }

    if (!empty($error))
        $tab .= $PHPShopGUI->setField('Ошибки', $PHPShopGUI->setText($error));

    $tab .= $PHPShopGUI->setField('Цена OZON', $PHPShopGUI->setInputText(null, 'price_ozon_new', $data['price_ozon'], 150, $valuta_def_name), 2);


    $PHPShopGUI->addTab(array("OZON", $tab, true));
}

function OzonsellerUpdate($data) {

    // Отключение Ozon
    if (!isset($_POST['export_ozon_new']) and isset($_POST['content_new'])) {
        $_POST['export_ozon_new'] = 0;
        $_POST['export_ozon_task_id_new'] = 0;
    }
}

$addHandler = array(
    'actionStart' => 'addOzonsellerProductTab',
    'actionDelete' => false,
    'actionUpdate' => 'OzonsellerUpdate'
);
?>