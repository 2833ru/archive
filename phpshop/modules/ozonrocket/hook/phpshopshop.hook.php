<?php
include_once dirname(__DIR__) . '/class/OzonRocketWidget.php';

function UIDOzonRocketWidgetHook($obj, $dataArray, $rout) {

    if ($rout == 'MIDDLE') {
        $OzonRocketWidget = new OzonRocketWidget();

        if (!empty($OzonRocketWidget->options['default_city'])) {
            $defaultCity = $OzonRocketWidget->options['default_city'];
        }

        $cart = $OzonRocketWidget->getCart([$dataArray], false);

        $params = [
            'token' => $OzonRocketWidget->options['token'],
            'defaultcity' => PHPShopString::win_utf8($defaultCity),
            'hideselect' => 'true',
            'hidepvz' => (bool) $OzonRocketWidget->options['hide_pvz']?'true':'false',
            'hidepostamat' => (bool) $OzonRocketWidget->options['hide_postamat']?'true':'false',
            'showdeliverytime' => (bool) $OzonRocketWidget->options['show_delivery_time']?'true':'false',
            'fromplaceid'=> $OzonRocketWidget->options['from_place_id'],
            'showdeliveryprice' => (bool) $OzonRocketWidget->options['show_delivery_price']?'true':'false',
            'packages' =>  $cart
        ];

        PHPShopParser::set('ozonrocket_params', http_build_query($params));
        $widget = ParseTemplateReturn($GLOBALS['SysValue']['templates']['ozonrocket']['ozonrocket_template'], true) . '<script type="text/javascript" src="phpshop/modules/ozonrocket/js/ozonrocket.js?v=1.0"></script>';

        $obj->set('ozonRocketWidgetProduct', $widget . sprintf('<button data-toggle="modal" data-target="#ozonrocketwidgetModal" class="btn btn-primary ozonwidgetproductstart" style="margin-top: 15px">%s</button>', $OzonRocketWidget->options['btn_text']));
    }
}

$addHandler = ['UID' => 'UIDOzonRocketWidgetHook'];