<?php

function getCartInfo($cart) {
    global $PHPShopSystem;
    $dis = null;
    $cart = unserialize($cart);
    $currency = ' ' . $PHPShopSystem->getDefaultValutaCode();
    if (is_array($cart))
        foreach ($cart as $val) {
            $dis.='<a href="?path=product&id=' . $val['id'] . '" data-toggle="tooltip" data-placement="top" title="' . $val['name'] . ' - ' . $val['price'] . $currency . '">' . $val['id'] . '</a>, ';
        }
    return substr($dis, 0, strlen($dis) - 2);
}

function getUserName($id, $ip) {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers']);
    $data = $PHPShopOrm->select(array('name'), array('id' => '=' . $id), false, array('limit' => 1));
    if (is_array($data))
        return array('name' => $data['name'], 'link' => '?path=shopusers&id=' . $id);
    else
        return $ip;
}

function getReferal($str) {
    $referal = explode(',', $str);
    $dis = null;
    if (is_array($referal)) {
        foreach ($referal as $val)
            $un_array[$val] = $val;

        foreach ($un_array as $val)
            $dis.=PHPShopText::a('http://' . $val, $val, false, false, false, '_blank') . '<br>';
    }
    return $dis;
}

function actionStart() {
    global $PHPShopInterface, $PHPShopModules, $TitlePage, $select_name;
    
        $PHPShopInterface->action_button['Выгрузить'] = array(
        'name' => 'Выгрузить корзины',
        'action' => '../modules/visualcart/admpanel/export.php',
        'class' => 'btn  btn-default btn-sm navbar-btn btn-action-panel-blank',
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-export'
    );
        
     unset($select_name[0]);
     unset($select_name[1]);

    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->setActionPanel($TitlePage, $select_name, array('Выгрузить'));
    $PHPShopInterface->setCaption(array("Пользователь", "25%"), array("Дата", "15%"), array("Товары", "25%"), array("Реферал", "25%"));

    // SQL
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.visualcart.visualcart_memory"));
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array("limit" => 1000));
    if (is_array($data))
        foreach ($data as $row) {
            $PHPShopInterface->setRow(getUserName($row['user'], $row['ip']), array('name'=>PHPShopDate::get($row['date'], true),'order'=>$row['date']), getCartInfo($row['cart']), getReferal($row['referal']));
        }
    $PHPShopInterface->Compile();
}

?>