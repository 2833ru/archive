<?php


/**
 * Списываем количество со склада
 * @param array $obj объект
 * @param array $data массив данных
 * @param string $rout роутер места вызовы модуля [START|MIDDLE|END]
 */
function cancellation_from_warehouse($obj, $data, $rout) {
    
    if ($rout == 'START') {
        $carts = $obj->PHPShopCart->getArray();
        foreach ($carts as $cart) {
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
            $row = $PHPShopOrm->select(array('items'), array('id' => '=' . intval($cart['id'])));
            if (is_array($row)) {
                $PHPShopOrm->clean();
                $items = $row['items'] - $cart['num'];
                $PHPShopOrm->update(array('items_new' => $items), array('id' => '=' . $cart['id']));
            }
        }
    }
     
     
}

$addHandler=array
        (
            'write' => 'cancellation_from_warehouse'

);
?>
