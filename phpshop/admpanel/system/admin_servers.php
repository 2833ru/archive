<?php

$TitlePage = __("Витрины");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['servers']);

// Стартовый вид
function actionStart() {
    global $PHPShopInterface, $PHPShopOrm, $TitlePage;

    $PHPShopInterface->action_select['Инструкция'] = array(
        'name' => 'Инструкция',
        'url' => 'http://faq.phpshop.ru/page/showcase.html',
        'target' => '_blank'
    );

    $PHPShopInterface->setActionPanel($TitlePage, array('Инструкция', '|', 'Удалить выбранные'), array('Добавить'));
    $PHPShopInterface->setCaption(array(null, "3%"), array("Название", "30%"), array("Адрес", "30%"), array("", "10%"), array("Статус", "10%", array('align' => 'right')));

    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {

            $PHPShopInterface->setRow($row['id'], array('name' => $row['name'], 'link' => '?path=system.servers&id=' . $row['id'], 'align' => 'left'), array('name' => $row['host'], 'link' => 'http://' . $row['host'], 'target' => '_blank'), array('action' => array('edit', '|', 'delete', 'id' => $row['id']), 'align' => 'center'), array('status' => array('enable' => $row['enabled'], 'align' => 'right', 'caption' => array('Выкл', 'Вкл'))));
        }

    $sidebarleft[] = array('title' => 'Категории', 'content' => $PHPShopInterface->loadLib('tab_menu', false, './system/'));
    $PHPShopInterface->setSidebarLeft($sidebarleft, 2);
    $PHPShopInterface->Compile(2);
}

?>