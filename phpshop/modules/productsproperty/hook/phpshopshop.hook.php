<?php

function productsproperty_UID_hook($obj, $row, $rout) {

    if ($rout == 'MIDDLE') {

        $productsproperty_array = unserialize($row['productsproperty_array']);

        if (is_array($productsproperty_array)) {
            
            $list=null;

            foreach ($productsproperty_array as $n=>$property) {

                if (!empty($property['name']))
                    $list .= PHPShopText::h5($property['name'], 'property-name');

                foreach ($property['property'] as $k => $val) {
                    if (!empty($val)) {

                        if ($row['id'] == $property['id'][$k]) {
                            $list .= PHPShopText::button($val, false, $class = 'btn btn-default property-value-'.($n+1).' active property-active') . ' ';
                        } else
                            $list .= PHPShopText::a('/shop/UID_' . $property['id'][$k] . '.html', $val, $val, false, false, false, 'btn btn-default property-value-'.($n+1)) . ' ';
                    }
                }
            }

            if (!empty($list)) {
                $obj->set('productsproperty_list', $list);
                $obj->set('productsproperty', PHPShopParser::file($GLOBALS['SysValue']['templates']['productsproperty']['productsproperty'], true, false, true));
            }
        }
    }
}

$addHandler = array
    (
    'UID' => 'productsproperty_UID_hook',
);
