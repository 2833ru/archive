<?php

$TitlePage = __("Покупатели - Статусы и скидки");
PHPShopObj::loadClass('user');

function actionStart() {
    global $PHPShopInterface;

    $PHPShopInterface->action_button['Добавить Статус'] = array(
        'name' => '',
        'action' => 'addNew',
        'class' => 'btn btn-default btn-sm navbar-btn',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-plus',
        'tooltip' => 'data-toggle="tooltip" data-placement="left" title="Добавить Статус"'
    );

    $PHPShopInterface->action_button['Скидки от заказа'] = array(
        'name' => 'Скидки от заказа',
        'action' => 'shopusers.discount',
        'class' => 'btn btn-default btn-sm navbar-btn btn-action-panel',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-shopping-cart'
    );


    $PHPShopInterface->action_select['Скидки от заказа'] = array(
        'name' => 'Скидки от заказа',
        'url' => '?path=shopusers.discount'
    );

    $PHPShopInterface->setActionPanel(__("Статусы и скидки покупателей"), array('Удалить выбранные', 'Скидки от заказа'), array('Добавить Статус','Скидки от заказа'));
    $PHPShopInterface->setCaption(array(null, "2%"), array("Название", "50%"), array("Колонка цен", "10%"), array("Скидка %", "10%"), array("Накопительная", "10%", array('align' => 'center')), array("", "10%"), array("Статус &nbsp;&nbsp;&nbsp;", "10%", array('align' => 'right')));

    // Таблица с данными
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers_status']);
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {

            if (!empty($row['cumulative_discount_check']))
                $cumulative_discount_check = '<span class="glyphicon glyphicon-ok"></span>';
            else
                $cumulative_discount_check = '<span class="glyphicon glyphicon-remove"></span>';

            $PHPShopInterface->setRow(
                    $row['id'], array('name' => $row['name'], 'link' => '?path=shopusers.status&id=' . $row['id'], 'align' => 'left'), $row['price'], $row['discount'], array('name' => $cumulative_discount_check, 'align' => 'center'), array('action' => array('edit', 'delete', 'id' => $row['id']), 'align' => 'center'), array('status' => array('enable' => $row['enabled'], 'align' => 'right', 'caption' => array('Выкл', 'Вкл'))));
        }
    $PHPShopInterface->Compile();
}

?>