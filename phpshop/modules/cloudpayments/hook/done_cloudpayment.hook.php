<?php

function send_to_order_mod_cloudpayment_hook($obj, $value, $rout) {
    global $PHPShopSystem;
    global $PHPShopCart;

    if ($rout == 'MIDDLE' and $value['order_metod'] == 10014) {

        $aCart = $obj->PHPShopCart->getArray();

        // ��������� ������
        include_once(dirname(__FILE__) . '/mod_option.hook.php');
        $PHPShopcloudpaymentArray = new PHPShopcloudpaymentArray();
        $option = $PHPShopcloudpaymentArray->getArray();

        // ������
        $currency = $PHPShopSystem->getDefaultValutaIso();

        // �������� ������ �� ������� ������
        if (empty($option['status'])) {
            // ����� �����
            $mrh_ouid = explode("-", $value['ouid']);
            $inv_id = $mrh_ouid[0] . "-" .$mrh_ouid[1];

            // ����� �������
            $out_summ = $obj->get('total');

            foreach ($aCart as $key => $arItem) {
                    
                    $amount = intval($arItem['price']) * intval($arItem['num']);

                    $aItem[] = array(
                                        "label"     => PHPShopString::json_safe_encode($arItem[name]),
                                        "price"     => $arItem['price'],
                                        "quantity"  => $arItem['num'],
                                        "amount"    => $amount,
                                        "vat"       => 0

                                );

            }

$json = json_encode($aItem, JSON_UNESCAPED_UNICODE);

            // ��������� �����
            $data = '<script src="https://widget.cloudpayments.ru/bundles/cloudpayments"></script>';
            $data .= '<script type="text/javascript">

    this.pay = function () {

        var data = {
            "cloudPayments": {
                "customerReceipt": {
                    "Items": '. $json. ',
                    "taxationSystem": 0, 
                    "email": "' .$_POST["mail"]. '", 
                    "phone": "' .$_POST["tel_new"]. '" 
                }
            }
        };

    var widget = new cp.CloudPayments();
    console.log(data);
    widget.charge({ 
            publicId: "' .$option["publicId"]. '",  
            description: "' .$option["description"]. '", 
            amount: ' .$out_summ. ', 
            currency: "' .$currency. '", 
            invoiceId: "' .$inv_id. '", 
            accountId: "' .$_POST["mail"]. '", 
            data: { data }
        },
        function (options) { // success
             location="http://' . $_SERVER['HTTP_HOST'] . '/success/?result=success";
        },
        function (reason, options) { // fail
            location="http://' . $_SERVER['HTTP_HOST'] . '/success/?result=fail";
        });
};    
            
</script>


<button id="pay" class="btn btn-primary">'.$option["title"].'</button>
<script type="text/javascript">
    

    $("#pay").click(function(event){
          event.preventDefault();
        pay();
        return false;

    });
        
</script>';

            // ������� �������
            unset($_SESSION['cart']);
        }else{
            $obj->set('mesageText', $option['title_end'] );
            $data = ParseTemplateReturn($GLOBALS['SysValue']['templates']['order_forma_mesage']);
        }

        $obj->set('orderMesage', $data);
    }
}

$addHandler = array
    (
    'send_to_order' => 'send_to_order_mod_cloudpayment_hook'
);
?>