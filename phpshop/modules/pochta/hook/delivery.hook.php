<?php

include_once dirname(__DIR__) . '/class/include.php';

function pochta_delivery_hook($obj, $data) {

    $result = $data[0];
    $Pochta = new Pochta();

    if (($Pochta->isCourier((int) $data[1]) || $Pochta->isPostOffice((int) $data[1])) && (int) $_POST['index'] > 0) {
        try {
            $PHPShopOrder = new PHPShopOrderFunction();

            $result['delivery'] = $Pochta->getCost($data[1], (int) $_POST['index']);
            $result['total'] = $PHPShopOrder->returnSumma((float) $_REQUEST['sum'], $PHPShopOrder->ChekDiscount($_REQUEST['sum']),' ', $result['delivery']);
            $result['message'] = PHPShopString::win_utf8('—тоимость доставки по индексу ' . (int) $_POST['index'] . ' составит ' . $result['delivery'] . ' руб.');
        } catch (Exception $exception) {
            $result['success'] = false;
            $result['message'] = PHPShopString::win_utf8($exception->getMessage());
        }
        return $result;
    }
}

$addHandler = array(
    'delivery' => 'pochta_delivery_hook'
);
?>
