<?php

/**
 * Форматируем описание товара до 250 символов в длину
 * @param array $obj объект
 */
function product_grid_hook($obj, $row) {
    
    // Получаем информацию о скидках по действующим промоакциям
    $discount_info = promotion_get_discount($row);
    $discount = $discount_info['percent'];
    $discountsum = $discount_info['sum'];

    //Если есть скидка
    if (!empty($discount) || !empty($discountsum)) {

        $priceDiscount[] = $obj->price($row) - ($obj->price($row) * $discount);
        $priceDiscount[] = $obj->price($row) - $discountsum;
        $priceDiscounItog = min($priceDiscount);
        $priceDiscount = $priceDiscounItog;
        //Обнуляем если в минус уходит
        if ($priceDiscount < 0) {
            $priceDiscount = 0;
        }
        $productPrice = $priceDiscount;
        $productPriceNew = $obj->price($row);

        if ($productPrice < $productPriceNew) {
            $obj->set('productPrice', $productPrice);
            $obj->set('productPriceRub', PHPShopText::strike($productPriceNew . " " . $obj->currency()));
        }

        if ($productPrice < $productPriceNew||$discount==0||$discountsum==0) {
            //ставим лэйбл
            $obj->set('promotionsIcon', $discount_info['label']);
        }
    } else {
        $obj->set('promotionsIcon', '');
    }
}

$addHandler = array
    (
    'product_grid' => 'product_grid_hook'
);
?>