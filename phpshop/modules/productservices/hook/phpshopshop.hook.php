<?php

function UIDProductServices($obj,$row,$rout){

    if($rout === 'MIDDLE'){
        $services = explode(',', $row['productservices_products']);
        $services = array_diff($services, array(''));

        if(is_array($services) && count($services) > 0) {
            $tpl = '';
            foreach ($services as $service) {
                if((int) $service > 0) {
                    $productService = new PHPShopProduct((int) $service);

                    $obj->set('product_services_id', $productService->objID);
                    $obj->set('product_services_name', $productService->getName());
                    $obj->set('product_services_price', $productService->getPrice());

                    $tpl .= parseTemplateReturn('./phpshop/modules/productservices/templates/service.tpl', true);

                    $obj->set('productservices_service', $tpl);
                }
            }

            $html = parseTemplateReturn('./phpshop/modules/productservices/templates/services_list.tpl', true);

            $obj->set('productservices_list', $html);
        } else {
            $obj->set('productservices_list', '');
        }
    }
}

$addHandler = array (
    'UID' => 'UIDProductServices'
);
?>