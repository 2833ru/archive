<?php

function addFilial($data) {
    global $PHPShopGUI;

    if ($data['category'] == 1000) {

        $licFile = PHPShopFile::searchFile('../../license/', 'getLicense', true);
        @$License = parse_ini_file_true("../../license/" . $licFile, 1);

        if (empty($License['License']['DomenLocked']))
            $License['License']['DomenLocked'] = $_SERVER['SERVER_NAME'];

        // Filial
        $Tab1 = $PHPShopGUI->setField("Таргетинг филиала:", $PHPShopGUI->setInputArg(array('type' => 'text', 'name' => 'target_url_page_new', 'value' => $data['target_url_page'], 'size' => 300, 'description' => '.' . $License['License']['DomenLocked'], 'caption' => 'http://')));
        $PHPShopGUI->addTab(array("Филиал", $Tab1, true));
    }
}

$addHandler = array(
    'actionStart' => 'addFilial',
    'actionDelete' => false,
    'actionUpdate' => false
);
?>