<?php

function addMoysklad($data) {
    global $PHPShopGUI;

    $Tab = $PHPShopGUI->setField("������� ���", $PHPShopGUI->setInputText(null, 'moysklad_client_id_new', $data['moysklad_client_id'], 300));
    $PHPShopGUI->addTab(array("��������", $Tab, true));
}

$addHandler = array(
    'actionStart' => 'addMoysklad',
    'actionDelete' => false,
    'actionUpdate' => false
);
?>