<?php
function orderOzonRocketHook($obj, $row, $rout)
{
    global $PHPShopSystem;

    if ($rout == 'MIDDLE') {
        include_once 'phpshop/modules/ozonrocket/class/OzonRocketWidget.php';

        $OzonRocketWidget = new OzonRocketWidget();
        $PHPShopCart = new PHPShopCart();
        $cart = $OzonRocketWidget->getCart($PHPShopCart->getArray(), false);

        if (!empty($OzonRocketWidget->options['default_city'])) {
            $defaultCity = $OzonRocketWidget->options['default_city'];
        }
        if($OzonRocketWidget->options['fee'] > 0){
            if((int) $OzonRocketWidget->options['fee_type'] === 1){
                PHPShopParser::set('ozonrocket_fee', 'deliverypricemarkuppercent=' . $OzonRocketWidget->options['fee']);
            } else {
                PHPShopParser::set('ozonrocket_fee', 'deliverypricemarkupfix=' . $OzonRocketWidget->options['fee']);
            }
        } else {
            PHPShopParser::set('ozonrocket_fee', '');
        }

        $params = [
            'token' => $OzonRocketWidget->options['token'],
            'defaultcity' => PHPShopString::win_utf8($defaultCity),
            'hidepvz' => (bool) $OzonRocketWidget->options['hide_pvz']?'true':'false',
            'hidepostamat' => (bool) $OzonRocketWidget->options['hide_postamat']?'true':'false',
            'showdeliverytime' => (bool) $OzonRocketWidget->options['show_delivery_time']?'true':'false',
            'fromplaceid'=> $OzonRocketWidget->options['from_place_id'],
            'showdeliveryprice' => (bool) $OzonRocketWidget->options['show_delivery_price']?'true':'false',
            'packages' =>  $cart
        ];

        PHPShopParser::set('ozonrocket_params', http_build_query($params));
        $obj->set('order_action_add', ParseTemplateReturn($GLOBALS['SysValue']['templates']['ozonrocket']['ozonrocket_template'], true). '<script type="text/javascript" src="phpshop/modules/ozonrocket/js/ozonrocket.js?v=1.0"></script>', true);
    }
}
$addHandler = ['order' => 'orderOzonRocketHook'];


