<?php

function send_to_order_mod_kvk_hook($obj, $value, $rout)
{
    if ($rout == 'MIDDLE' && $value['order_metod'] == 10044) {
        // Настройки модуля
        include_once dirname(__FILE__) . '/mod_option.hook.php';
        $PHPShopKVKArray = new PHPShopKVKArray();
        $option = $PHPShopKVKArray->getArray();

        if ($option['dev_mode']==1) {
            $kvk_url = 'https://loans-qa.tcsbank.ru/api/partners/v1/lightweight/create';
            $kvk_shop_id = 'test_online';
            $kvk_showcase_id = 'test_online';
        } else {
            $kvk_url = 'https://loans.tinkoff.ru/api/partners/v1/lightweight/create';
            $kvk_shop_id = $option['shop_id'];
            $kvk_showcase_id = $option['showcase_id'];
        }
        
        // Форма
        $cart = $obj->PHPShopCart->getArray();
        $total = 0; $i = 0; $dis = '';
        foreach ($cart as $product) {
            $dis .= '<input name="itemVendorCode_'.$i.'" value="'.$product['id'].'" type="hidden">';
            $dis .= '<input name="itemName_'.$i.'" value="'.iconv("windows-1251", "utf-8", htmlspecialchars($product['name'], ENT_COMPAT, 'cp1251', true)).'" type="hidden">';
            $dis .= '<input name="itemQuantity_'.$i.'" value="'.$product['num'].'" type="hidden">';
            $price = $product['price'];
            $total += $price;
            $dis .= '<input name="itemPrice_'.$i.'" value="'.number_format($price, 2, '.', '').'" type="hidden">';
            
            $i++;
        }
        
        $kvk_pay = ceil($total/19);
        $obj->set('kvk_prod', $dis);
        $obj->set('kvk_ouid', $_POST['ouid']);
        $obj->set('kvk_mail', $_POST['mail']);
        $obj->set('kvk_tel', $_POST['tel_new']);
        $obj->set('kvk_url', $kvk_url);
        $obj->set('kvk_shop_id', $kvk_shop_id);
        if (!empty($kvk_showcase_id)) $obj->set('kvk_showcase_id', "<input name='showcaseId' value='$kvk_showcase_id' type='hidden'>");
        $obj->set('kvk_sum', number_format($total, 2, '.', ''));
        $obj->set('kvk_pay', "Перейти на сайт Tinkoff Credit");
        
        $form = ParseTemplateReturn($GLOBALS['SysValue']['templates']['kupivkredit']['kupivkredit_cart'], true);

        // Очищаем корзину
        unset($_SESSION['cart']);

        $obj->set('orderMesage', $form);
    }
}

$addHandler = array
(
    'send_to_order' => 'send_to_order_mod_kvk_hook'
);

?>