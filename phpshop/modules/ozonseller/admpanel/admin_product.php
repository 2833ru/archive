<?php

include_once dirname(__FILE__) . '/../class/OzonSeller.php';

function actionStart() {
    global $PHPShopInterface, $PHPShopModules, $TitlePage, $select_name;

    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->setActionPanel($TitlePage, $select_name, false);
    $PHPShopInterface->setCaption(array("Иконка", "7%"), array("Название", "50%"), array("Ошибки", "45%"), array("Статус", "10%"));

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
    $PHPShopOrm->debug = false;

    $data = $PHPShopOrm->select(array('*'), array('export_ozon' => "='1'"), array('order' => 'datas DESC'), array('limit' => 10000));
    $OzonSeller = new OzonSeller();

    $status = ['imported' => '<span class="text-success">'.__('Загружен').'</span>', 'error' => '<span class="text-warning">'.__('Ошибка').'</span>'];
    if (is_array($data))
        foreach ($data as $row) {

            $error = null;
            $info = $er = $result = [];

            if (!empty($row['pic_small']))
                $icon = '<img src="' . $row['pic_small'] . '" onerror="this.onerror = null;this.src = \'./images/no_photo.gif\'" class="media-object">';
            else
                $icon = '<img class="media-object" src="./images/no_photo.gif">';

            if($row['export_ozon_task_status'] == 'imported'){
                $info['status'] = $row['export_ozon_task_status'];
            }
            else if (!empty($row['export_ozon_task_id'])) {
                $result = $OzonSeller->sendProductsInfo($row);
                $info = $result['result']['items'][0];
            } else {
                $products[] = $row;
                $result = $OzonSeller->sendProducts($products);
                $task_id = $data['export_ozon_task_id'] = $result['result']['task_id'];

                if (!empty($task_id)) {
                    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                    $PHPShopOrm->update(['export_ozon_task_id_new' => $task_id], ['id' => '=' . $data['id']]);

                    $info['errors'][] = ['description'=>$result['message']];

                } else{
                    
                    $info['errors'][] = ['description'=>$result['message']];
                }
                
            }


            if (empty($info['status']))
                $info['status'] = 'error';

            if (is_array($info['errors'])) {
                foreach ($info['errors'] as $k => $er) {

                    // Ссылки
                    if (!empty($er['description']))
                        $er['description'] = preg_replace("~(http|https|ftp|ftps)://(.*?)(\s|\n|[,.?!](\s|\n)|$)~", '<a href="$1://$2" target="_blank">[ссылка]</a>$3', $er['description']);
                    $error .= ($k + 1) . ' - ' . PHPShopString::utf8_win1251($er['description']) . '<br>';
                    
                }
            }
            else {
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
