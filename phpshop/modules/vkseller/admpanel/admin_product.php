<?php

include_once dirname(__FILE__) . '/../class/VkSeller.php';

function actionStart() {
    global $PHPShopInterface, $PHPShopModules, $TitlePage, $select_name;

    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->setActionPanel($TitlePage, $select_name, false);
    $PHPShopInterface->setCaption(array("Иконка", "7%"), array("Название", "40%"), array("Ошибки", "30%"), array("Статус", "20%"));

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
    $PHPShopOrm->debug = false;

    $VkSeller = new VkSeller();
    if ($VkSeller->model == 'API') {
        $data = $PHPShopOrm->select(array('*'), array('export_vk' => "='1'"), array('order' => 'export_vk_task_status'), array('limit' => 10000));
    }

    if (is_array($data)) {

        foreach ($data as $row) {

            if (!empty($row['pic_small']))
                $icon = '<img src="' . $row['pic_small'] . '" onerror="this.onerror = null;this.src = \'./images/no_photo.gif\'" class="media-object">';
            else
                $icon = '<img class="media-object" src="./images/no_photo.gif">';

            if (!empty($row['export_vk_task_status'])) {

                $status = '<span class="text-success">' . __('Загружен') . ' ' . PHPShopDate::get($data['export_vk_task_status'], true) . '</span>';
                $error = null;
            } else {

                // Фото главное
                $row['main_photo_id'] = $VkSeller->sendImages($row['id'], $row['pic_big'])['response'][0]['id'];

                // Фото дополнительные
                $images = $VkSeller->getImages($row['id'], $row['pic_big']);
                if (is_array($images))
                    foreach ($images as $image) {
                    
                        $photo_result = $VkSeller->sendImages($row['id'], $image)['response'][0]['id'];

                        if (!empty($photo_result))
                            $photo_ids[] = $photo_result;
                    }

                $row['photo_ids'] = implode(",", $photo_ids);
                unset($photo_ids);

                if (!empty($row['main_photo_id'])) {
                    $export_vk = $VkSeller->sendProduct($row);
                    $export_vk_id = $export_vk['response']['market_item_id'];
                }

                if (!empty($export_vk_id)) {
                    $PHPShopOrm->update(['export_vk_task_status_new' => time(), 'export_vk_id_new' => $export_vk_id], ['id' => '=' . (int) $row['id']]);
                    $error = null;
                    $status = '<span class="text-success">' . __('Загружен') . ' ' . PHPShopDate::get(time(), true) . '</span>';
                } else {
                    $error = PHPShopString::utf8_win1251($export_vk['error']['error_msg']);
                    $status = '<span class="text-warning">' . __('Ошибка') . '</span>';
                }
            }

            // Артикул
            if (!empty($row['uid']))
                $uid = '<div class="text-muted">' . __('Арт') . ' ' . $row['uid'] . '</div>';
            else
                $uid = null;

            $PHPShopInterface->setRow($icon, array('name' => $row['name'], 'addon' => $uid, 'link' => '?path=product&id=' . $row['id']), $error, $status);
        }
    }
    $PHPShopInterface->Compile();
}
