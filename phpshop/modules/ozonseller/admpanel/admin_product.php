<?php

include_once dirname(__FILE__) . '/../class/OzonSeller.php';

function actionStart() {
    global $PHPShopInterface, $PHPShopModules, $TitlePage, $select_name;

    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->setActionPanel($TitlePage, $select_name, false);
    $PHPShopInterface->setCaption(array("Иконка", "7%"), array("Название", "40%"), array("Ошибки", "30%"), array("Статус", "20%"));

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
    $PHPShopOrm->debug = false;

    $data = $PHPShopOrm->select(array('*'), array('export_ozon' => "='1'"), array('order' => 'export_ozon_task_status DESC'), array('limit' => 10000));
    $OzonSeller = new OzonSeller();
    $import_count = 0;

    $status = [
        'imported' => '<span class="text-success">' . __('Загружен') . '</span>',
        'error' => '<span class="text-warning">' . __('Ошибка') . '</span>',
        'wait' => '<span class="text-mutted">' . __('В очереди на загрузку') . '</span>',
        'pending' => '<span class="text-mutted">' . __('в ожидании') . '</span>',
    ];
    if (is_array($data))
        foreach ($data as $row) {

            $error = null;
            $info = $er = $result = [];

            if (!empty($row['pic_small']))
                $icon = '<img src="' . $row['pic_small'] . '" onerror="this.onerror = null;this.src = \'./images/no_photo.gif\'" class="media-object">';
            else
                $icon = '<img class="media-object" src="./images/no_photo.gif">';

            // Статус загрузки
            $info['status'] = $row['export_ozon_task_status'];

            if (empty($data['export_ozon_task_id'])) {

                if ($import_count < 10) {

                    $products[] = $row;
                    $result = $OzonSeller->sendProducts($products);
                    $task_id = $data['export_ozon_task_id'] = $result['result']['task_id'];
                    $import_count++;

                    if (!empty($task_id)) {
                        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                        $PHPShopOrm->update(['export_ozon_task_id_new' => $task_id, 'export_ozon_task_status_new' => 'imported'], ['id' => '=' . $row['id']]);
                        $info['status'] = 'imported';

                        $info['errors'][] = ['description' => $result['message']];
                    } else {

                        $info['errors'][] = ['description' => $result['message']];
                    }
                } else {
                    $info['status'] = 'wait';
                }
            }
            else $info['status'] = 'imported';


            if (empty($info['status']))
                $info['status'] = 'error';

            if (is_array($info['errors'])) {

                foreach ($info['errors'] as $k => $er) {

                    // Ссылки
                    if (!empty($er['description'])) {
                        $er['description'] = preg_replace("~(http|https|ftp|ftps)://(.*?)(\s|\n|[,.?!](\s|\n)|$)~", '<a href="$1://$2" target="_blank">[ссылка]</a>$3', $er['description']);
                        $error .= ($k + 1) . ' - ' . PHPShopString::utf8_win1251($er['description']) . '<br>';
                    }
                }
            } else {
                $error = $result['message'];
            }
            

            // Артикул
            if (!empty($row['uid']))
                $uid = '<div class="text-muted">' . __('Арт') . ' ' . $row['uid'] . '</div>';
            else
                $uid = null;


            $PHPShopInterface->setRow($icon, array('name' => $row['name'], 'addon' => $uid, 'link' => '?path=product&id=' . $row['id']), $error, $status[$info['status']]);
        }
    $PHPShopInterface->Compile();
}
