<?php

function filial_mod_element_hook($obj, $data, $rout) {

    if ($rout == 'START') {

        $objBase = $GLOBALS['SysValue']['base']['page'];
        $PHPShopOrm = new PHPShopOrm($objBase);
        $data = $PHPShopOrm->select(array('name', 'link', 'target_url_page'), array("category" => "=1000", 'enabled' => "='1'"), array('order' => 'num'), array("limit" => 100));
        if (is_array($data))
            foreach ($data as $row) {
                if (empty($row['target_url_page']) or strstr($_SERVER['SERVER_NAME'],$row['target_url_page'])) {

                    // Определяем переменные
                    $obj->set('topMenuName', $row['name']);
                    $obj->set('topMenuLink', $row['link']);

                    // Активная страница
                    if ($row['link'] == $obj->PHPShopNav->getName(true))
                        $obj->set('topMenuActive', 'active');
                    else
                        $obj->set('topMenuActive', '');

                    // Перехват модуля
                    $obj->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

                    // Подключаем шаблон
                    $dis.=$obj->parseTemplate($obj->getValue('templates.top_menu'));
                }
                
            }

        return $dis;
    }
}

$addHandler = array
    (
    'topMenu' => 'filial_mod_element_hook'
);
?>