<?php

function addBoxberry($data) {
    global $PHPShopGUI;
    if (!function_exists('addRussianPostDelivery') and !function_exists('addDDelivery') and empty($data['is_folder'])) {
        $Tab3.=$PHPShopGUI->setField(__('Не изменять стоимость'), $PHPShopGUI->setRadio('is_mod_new', 1, __('Выключить'), $data['is_mod'], false, 'text-warning') .
                $PHPShopGUI->setRadio('is_mod_new', 2, __('Включить'), $data['is_mod']));

        $PHPShopGUI->addTab(array("Изменение стоимости доставки", $Tab3, true));
    }
}

$addHandler = array(
    'actionStart' => 'addBoxberry',
    'actionDelete' => false,
    'actionUpdate' => false
);
?>